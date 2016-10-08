<?php

// exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// check if class already exists
if ( ! class_exists( 'Acf_Field_Sites' ) ) :

	class Acf_Field_Sites extends acf_field {

		// vars
		var $settings, // will hold info such as dir / path
			$defaults; // will hold default field options


		/*
		*  __construct
		*
		*  Set name / label needed for actions / filters
		*
		*  @since	3.6
		*  @date	23/01/13
		*/
		function __construct( $settings ) {
			// vars
			$this->name = 'sites';
			$this->label = __( 'Sites', 'acf-sites' );
			$this->category = __( 'Relation', 'acf-sites' );
			$this->defaults = array(
				'field_type'	=> 'checkbox',
				'status' => 'all',
				'hide_main' => 1,
				'allow_null' => 0,
				'return_value' => 'id',
				'site__not_in' => '',
			);

			// do not delete!
	    	parent::__construct();

	    	// settings
			$this->settings = $settings;

		}


		/*
		*  create_options()
		*
		*  Create extra options for your field. This is rendered when editing a field.
		*  The value of $field['name'] can be used (like below) to save extra data to the $field
		*
		*  @type	action
		*  @since	3.6
		*  @date	23/01/13
		*
		*  @param	$field	- an array holding all the field's data
		*/

		function create_options( $field ) {
			// defaults?
			$field = array_merge( $this->defaults, $field );

			// key is needed in the field names to correctly save the data
			$key = $field['name'];
			?>
			<tr class="field_option field_option_<?php echo $this->name; ?>">
				<td class="label">
					<label><?php _e( 'Appearance','acf-sites' ); ?></label>
					<p class="description"><?php _e( 'Select the appearance of this field','acf-sites' ); ?></p>
				</td>
				<td>
					<?php
					do_action('acf/create_field', array(
						'type'    => 'select',
						'name'    => 'fields[' . $key . '][field_type]',
						'value'   => $field['field_type'],
						'layout'  => 'horizontal',
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
					?>
				</td>
			</tr>
			<tr class="field_option field_option_<?php echo $this->name; ?>">
				<td class="label">
					<label><?php _e( 'Allow null', 'acf-sites' ); ?></label>
					<p class="description"></p>
				</td>
				<td>
					<?php
					do_action('acf/create_field', array(
						'type' => 'radio',
						'name' => 'fields[' . $key . '][allow_null]',
						'value' => $field['allow_null'],
						'layout' => 'horizontal',
						'choices' => array(
							1 => __( 'Yes' ),
							0 => __( 'No' ),
						),
					));
					?>
				</td>
			</tr>
			<tr class="field_option field_option_<?php echo $this->name; ?>">
				<td class="label">
					<label><?php _e( 'Site status', 'acf-sites' ); ?></label>
					<p class="description"><?php _e( 'WordPress currently only support limiting to single status.','acf-sites' ); ?></p>
				</td>
				<td>
					<?php
					do_action('acf/create_field', array(
						'type' => 'radio',
						'name' => 'fields[' . $key . '][status]',
						'value' => $field['status'],
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
					?>
				</td>
			</tr>
			<tr class="field_option field_option_<?php echo $this->name; ?>">
				<td class="label">
					<label><?php _e( 'Hide main site', 'acf-sites' ); ?></label>
					<p class="description"></p>
				</td>
				<td>
					<?php
					do_action('acf/create_field', array(
						'type' => 'radio',
						'name' => 'fields[' . $key . '][hide_main]',
						'value' => $field['hide_main'],
						'layout' => 'horizontal',
						'choices' => array(
							1 => __( 'Yes' ),
							0 => __( 'No' ),
						),
					));
					?>
				</td>
			</tr>
			<tr class="field_option field_option_<?php echo $this->name; ?>">
				<td class="label">
					<label><?php _e( 'Exclude sites', 'acf-sites' ); ?></label>
					<p class="description"><?php _e( 'Comma separated site IDs','acf-sites' ); ?></p>
				</td>
				<td>
					<?php
					do_action('acf/create_field', array(
						'type' => 'text',
						'name' => 'fields[' . $key . '][site__not_in]',
						'value' => $field['site__not_in'],
					));
					?>
				</td>
			</tr>
			<tr class="field_option field_option_<?php echo $this->name; ?>">
				<td class="label">
					<label><?php _e( 'Return value', 'acf-sites' ); ?></label>
					<p class="description"><?php _e( 'Specify the value returned','acf-sites' ); ?></p>
				</td>
				<td>
					<?php
					do_action('acf/create_field', array(
						'type' => 'radio',
						'name' => 'fields[' . $key . '][return_value]',
						'value' => $field['return_value'],
						'layout' => 'horizontal',
						'choices' => array(
							'id' => __( 'Site ID', 'acf-sites' ),
							'name' => __( 'Site name', 'acf-sites' ),
							'url' => __( 'Site URL', 'acf-sites' ),
							'all' => __( 'All (Array)', 'acf-sites' ),
						),
					));
					?>
				</td>
			</tr>
			<?php

		}

		/*
		*  create_field()
		*
		*  Create the HTML interface for your field
		*
		*  @param	$field - an array holding all the field's data
		*
		*  @type	action
		*  @since	3.6
		*  @date	23/01/13
		*/
		function create_field( $field ) {

			// vars
			$single_name = $field['name'];

			// value must be array!
			if ( ! is_array( $field['value'] ) ) {
				$field['value'] = array( $field['value'] );
			}

			?>
			<div class="acf-sites-field">
				<input type="hidden" name="<?php echo $field['name']; ?>" value="" />
			<?php

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

			if ( $field['multiple'] ) {
				$multiple = 'multiple="multiple" size="5"';
				$field['name'] .= '[]';
			}
			?>
			<select id="<?php echo $field['id']; ?>" name="<?php echo $field['name']; ?>" <?php echo $multiple; ?>>
				<?php if ( $field['allow_null'] ) : ?>
					<option value=""><?php _e( 'None', 'acf-sites' ); ?></option>
				<?php endif; ?>
				<?php foreach ( $sites as $site ) : ?>
					<?php $selected = ( in_array( $site->blog_id, $field['value'] ) ? 'selected' : '' ); ?>
					<option value="<?php echo $site->blog_id ?>" <?php echo $selected; ?>><?php echo $site->blogname; ?></option>
				<?php endforeach; ?>
			</select>
			<?php

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

			// checkbox saves an array
			if ( 'checkbox' == $field['field_type'] ) {
				$field['name'] .= '[]';

			}

			?>
			<div class="categorychecklist-holder">

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

			</div>
			<?php

		}


		/*
		*  update_value()
		*
		*  This filter is applied to the $value before it is updated in the db
		*
		*  @type	filter
		*  @since	3.6
		*  @date	23/01/13
		*
		*  @param	$value - the value which will be saved in the database
		*  @param	$post_id - the $post_id of which the value will be saved
		*  @param	$field - the field array holding all the field options
		*
		*  @return	$value - the modified value
		*/
		function update_value( $value, $post_id, $field ) {

			if ( ! is_array( $value ) ) {
				$value = array( $value );
			}

			return $value;
		}


		/*
		*  format_value_for_api()
		*
		*  This filter is applied to the $value after it is loaded from the db and before it is passed back to the API functions such as the_field
		*
		*  @type	filter
		*  @since	3.6
		*  @date	23/01/13
		*
		*  @param	$value	- the value which was loaded from the database
		*  @param	$post_id - the $post_id from which the value was loaded
		*  @param	$field	- the field array holding all the field options
		*
		*  @return	$value	- the modified value
		*/

		function format_value_for_api( $value, $post_id, $field ) {

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

endif; // class_exists check
