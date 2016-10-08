<?php

// exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// check if class already exists
if ( ! class_exists( 'Acf_Field_Sites' ) ) {

	class Acf_Field_Sites extends acf_field {

		/*
		*  __construct
		*
		*  This function will setup the field type data
		*
		*  @type	function
		*  @date	5/03/2014
		*  @since	5.0.0
		*
		*  @param	n/a
		*  @return	n/a
		*/
		function __construct( $settings ) {

			/*
			*  name (string) Single word, no spaces. Underscores allowed
			*/
			$this->name = 'sites';

			/*
			*  label (string) Multiple words, can include spaces, visible when selecting a field type
			*/
			$this->label = __( 'Sites', 'acf-sites' );

			/*
			 *  category (string) basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME
			 */

			$this->category = 'relational';

			/*
			 *  defaults (array) Array of default settings which are merged into the field object. These are used later in settings
			 */

			$this->defaults = array(
				'field_type'	=> 'checkbox',
				'status' => 'all',
				'hide_main' => 1,
				'allow_null' => 0,
				'return_value' => 'id',
				'site__not_in' => '',
			);


			/*
			*  settings (array) Store plugin settings (url, path, version) as a reference for later use with assets
			*/

			$this->settings = $settings;

			// do not delete!
	    	parent::__construct();

		}


		/*
		*  render_field_settings()
		*
		*  Create extra settings for your field. These are visible when editing a field
		*
		*  @type	action
		*  @since	3.6
		*  @date	23/01/13
		*
		*  @param	$field (array) the $field being edited
		*  @return	n/a
		*/

		public function render_field_settings( $field ) {

			/*
			*  acf_render_field_setting
			*
			*  This function will create a setting for your field. Simply pass the $field parameter and an array of field settings.
			*  The array of settings does not require a `value` or `prefix`; These settings are found from the $field array.
			*
			*  More than one setting can be added by copy/paste the above code.
			*  Please note that you must also have a matching $defaults value for the field name (font_size)
			*/

			acf_render_field_setting( $field, array(
				'label' => __( 'Appearance','acf-sites' ),
				'instructions' => __( 'Select the appearance of this field','acf-sites' ),
				'type' => 'select',
				'name' => 'field_type',
				'optgroup' => true,
				'choices' => array(
					__( 'Multiple Values','acf-sites' ) => array(
						'checkbox' => __( 'Checkbox', 'acf-sites' ),
						'multi_select' => __( 'Multi Select', 'acf-sites' ),
					),
					__( 'Single Value', 'acf-sites' ) => array(
						'radio' => __( 'Radio Buttons', 'acf-sites' ),
						'select' => _x( 'Select', 'noun', 'acf-sites' ),
					),
				),
			));

			acf_render_field_setting( $field, array(
				'label' => __( 'Allow null','acf-sites' ),
				'instructions' => '',
				'type' => 'radio',
				'name' => 'allow_null',
				'layout' => 'horizontal',
				'choices' => array(
					1 => __( 'Yes' ),
					0 => __( 'No' ),
				),
			));

			acf_render_field_setting( $field, array(
				'label' => __( 'Site status','acf-sites' ),
				'instructions' => __( 'WordPress currently only support limiting to single status.','acf-sites' ),
				'type' => 'radio',
				'name' => 'status',
				'layout' => 'horizontal',
				'choices' => array(
					'all' => __( 'All', 'acf-sites' ),
					'public' => __( 'Public', 'acf-sites' ),
					'archived' => __( 'Archived', 'acf-sites' ),
					'mature' => __( 'Mature', 'acf-sites' ),
					'spam' => __( 'Spam', 'acf-sites' ),
					'deleted' => __( 'Deleted', 'acf-sites' ),
				),
			));

			acf_render_field_setting( $field, array(
				'label' => __( 'Hide main site','acf-sites' ),
				'instructions' => '',
				'type' => 'radio',
				'name' => 'hide_main',
				'layout' => 'horizontal',
				'choices' => array(
					1 => __( 'Yes' ),
					0 => __( 'No' ),
				),
			));

			acf_render_field_setting( $field, array(
				'label' => __( 'Exclude sites','acf-sites' ),
				'instructions' => __( 'Comma separated site IDs','acf-sites' ),
				'type' => 'text',
				'name' => 'site__not_in',
			));

			acf_render_field_setting( $field, array(
				'label' => __( 'Return value','acf-sites' ),
				'instructions' => __( 'Specify the value returned','acf-sites' ),
				'type' => 'radio',
				'name' => 'return_value',
				'layout' => 'horizontal',
				'choices' => array(
					'id' => __( 'Site ID', 'acf-sites' ),
					'name' => __( 'Site name', 'acf-sites' ),
					'url' => __( 'Site URL', 'acf-sites' ),
					'all' => __( 'All (Array)', 'acf-sites' ),
				),
			));

		}

		/*
		*  render_field()
		*
		*  Create the HTML interface for your field
		*
		*  @param	$field (array) the $field being rendered
		*
		*  @type	action
		*  @since	3.6
		*  @date	23/01/13
		*
		*  @param	$field (array) the $field being edited
		*  @return	n/a
		*/
		public function render_field( $field ) {

			// value must be array
			$field['value'] = acf_get_array( $field['value'] );

			/**
			 * This field requires multisite to work.
			 */
			if ( ! is_multisite() ) {
				echo '<p>' . __( 'You need to have set up a multisite network for this field to work', 'acf-sites' ) . '</p>';
				return;
			}

			/**
			 * Setup query.
			 */
			global $wpdb;
			$args = array();

			if ( $field['hide_main'] ) {
				$args['offset'] = 1;
			}

			if ( 'all' != $field['status'] ) {
				$args[ $field['status'] ] = '1';
			}

			if ( '' != $field['site__not_in'] ) {
				$excluded_sites = explode( ',', $field['site__not_in'] );
				$excluded_sites_2 = explode( ', ', $field['site__not_in'] );
				$excluded_merged = array_merge( $excluded_sites, $excluded_sites_2 );
				$args['site__not_in'] = $excluded_merged;
			}

			$sites = get_sites( $args );

			if ( empty( $sites ) ) {
				echo '<p>' . __( 'No sites found with the current field settings.', 'acf-sites' ) . '</p>';
			}

			// vars
			$div = array(
				'class'				=> 'acf-sites-field acf-soh',
				'data-type'			=> $field['field_type'],
			);
			?>
			<div <?php acf_esc_attr_e( $div ); ?>>
			<?php

			if ( 'select' == $field['field_type'] ) {

				$field['multiple'] = 0;

				$this->render_field_select( $field, $sites );

			} elseif ( 'multi_select' == $field['field_type'] ) {

				$field['multiple'] = 1;

				$this->render_field_select( $field, $sites );

			} elseif ( 'radio' == $field['field_type'] ) {

				$this->render_field_checkbox( $field, $sites );

			} elseif ( 'checkbox' == $field['field_type'] ) {

				$this->render_field_checkbox( $field, $sites );

			}

			echo '</div>';

		}


		/*
		*  render_field_select()
		*
		*  Create the HTML interface for your field
		*
		*  @type	action
		*  @since	3.6
		*  @date	23/01/13
		*
		*  @param	$field - an array holding all the field's data
		*/

		function render_field_select( $field, $sites ) {

			// Change Field into a select
			$field['type'] = 'select';
			$field['ui'] = 1;
			$field['ajax'] = 0;
			$field['choices'] = array();

			foreach ( $sites as $site ) {
				$field['choices'][ $site->blog_id ] = $site->blogname;
			}

			// render select
			acf_render_field( $field );

		}


		/*
		*  render_field_checkbox()
		*
		*  Create the HTML interface for your field
		*
		*  @type	action
		*  @since	3.6
		*  @date	23/01/13
		*
		*  @param	$field - an array holding all the field's data
		*/

		function render_field_checkbox( $field, $sites ) {

			// hidden input
			acf_hidden_input(array(
				'type'	=> 'hidden',
				'name'	=> $field['name'],
			));

			// checkbox saves an array
			if ( 'checkbox' == $field['field_type'] ) {

				$field['name'] .= '[]';

			}

			?><div class="categorychecklist-holder">

				<ul class="acf-checkbox-list acf-bl">

					<?php if ( 'radio' == $field['field_type'] && $field['allow_null'] ) : ?>
						<?php $checked = ( in_array( '', $field['value'] ) || empty( $field['value'] ) ? 'checked' : '' ); ?>
						<li>
							<label class="selectit">
								<input type="radio" name="<?php echo $field['name']; ?>" value="" <?php echo $checked; ?> /> <?php _e( 'None', 'acf-sites' ); ?>
							</label>
						</li>
					<?php endif; ?>

					<?php foreach ( $sites as $site ) : ?>
						<?php $checked = ( in_array( $site->blog_id, $field['value'] ) ? 'checked' : '' ); ?>
						<li>
							<label class="selectit">
								<input type="<?php echo $field['field_type'] ?>" name="<?php echo $field['name']; ?>" value="<?php echo $site->blog_id; ?>" <?php echo $checked; ?> /> <?php echo $site->blogname; ?>
							</label>
						</li>
					<?php endforeach; ?>
				</ul>

			</div><?php

		}


		/*
		*  input_admin_enqueue_scripts()
		*
		*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
		*  Use this action to add CSS + JavaScript to assist your render_field() action.
		*
		*  @type	action (admin_enqueue_scripts)
		*  @since	3.6
		*  @date	23/01/13
		*
		*  @param	n/a
		*  @return	n/a
		*/
		function input_admin_enqueue_scripts() {

			// vars
			$url = $this->settings['url'];
			$version = $this->settings['version'];

			// register & include JS
			wp_register_script( 'acf-input-sites', "{$url}assets/js/input.js", array( 'acf-input' ), $version );
			wp_enqueue_script( 'acf-input-sites' );

		}


		/*
		*  update_value()
		*
		*  This filter is applied to the $value before it is saved in the db
		*
		*  @type	filter
		*  @since	3.6
		*  @date	23/01/13
		*
		*  @param	$value (mixed) the value found in the database
		*  @param	$post_id (mixed) the $post_id from which the value was loaded
		*  @param	$field (array) the field array holding all the field options
		*  @return	$value
		*/

		function update_value( $value, $post_id, $field ) {

			if ( ! is_array( $value ) ) {
				$value = array( $value );
			}

			return $value;

		}


		/*
		*  format_value()
		*
		*  This filter is appied to the $value after it is loaded from the db and before it is returned to the template
		*
		*  @type	filter
		*  @since	3.6
		*  @date	23/01/13
		*
		*  @param	$value (mixed) the value which was loaded from the database
		*  @param	$post_id (mixed) the $post_id from which the value was loaded
		*  @param	$field (array) the field array holding all the field options
		*
		*  @return	$value (mixed) the modified value
		*/
		function format_value( $value, $post_id, $field ) {

			// bail early if no value
			if ( empty( $value ) ) {
				return $value;
			}

			// apply setting
			if ( 'id' == $field['return_value'] ) {
				return $value;

			}

			$formatted_values = array();
			foreach ( $value as $site_id ) {
				$site_details = get_blog_details( $site_id );
				if ( 'name' == $field['return_value'] ) {
					$formatted_values[] = $site_details->blogname;

				} elseif ( 'url' == $field['return_value'] ) {
					$formatted_values[] = $site_details->siteurl;

				} elseif ( 'all' == $field['return_value'] ) {
					$formatted_values[] = array(
						'id' => $site_details->blog_id,
						'url' => $site_details->siteurl,
						'name' => $site_details->blogname,
					);

				}
			}

			return $formatted_values;
		}

	}

	// initialize
	new Acf_Field_Sites( $this->settings );


}// class_exists check
