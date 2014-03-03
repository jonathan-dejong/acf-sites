<?php

class acf_field_sites extends acf_field
{
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

	function __construct()
	{
		// vars
		$this->name = 'sites';
		$this->label = __('sites');
		$this->category = __("Relational",'acf');
		$this->defaults = array(
			'checkbox_select' => 'checkbox',
			'site_status' => 'public',
			'hide_main' => 'yes',
			'allow_null' => 'yes'
		);


		// do not delete!
    parent::__construct();


    // settings
		$this->settings = array(
			'path' => apply_filters('acf/helpers/get_path', __FILE__),
			'dir' => apply_filters('acf/helpers/get_dir', __FILE__),
			'version' => '1.0.0'
		);

	}


	/*
	*  create_options()
	*
	*  Create extra options for your field. This is rendered when editing a field.
	*  The value of $field['name'] can be used (like bellow) to save extra data to the $field
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field	- an array holding all the field's data
	*/

	function create_options($field)
	{
		// defaults?
		$field = array_merge($this->defaults, $field);
		

		// key is needed in the field names to correctly save the data
		$key = $field['name'];

		// Create Field Options HTML
		?>
		<tr class="field_option field_option_<?php echo $this->name; ?>">
			<td class="label">
				<label><?php _e("Show as checkboxes or select dropdown?", 'acf'); ?></label>
				<p class="description"><?php _e("", 'acf'); ?></p>
			</td>
			<td>
				<?php
				do_action('acf/create_field', array(
					'type'    =>  'radio',
					'name'    =>  'fields[' . $key . '][checkbox_select]',
					'value'   =>  $field['checkbox_select'],
					'layout'  =>  'horizontal',
					'choices' =>  array(
						'checkbox' => __('Checkbox (allows multiple)'),
						'select' => __('Select'),
					)
				));
				?>
			</td>
		</tr>
		<tr class="field_option field_option_<?php echo $this->name; ?>">
			<td class="label">
				<label><?php _e("Select which site status to select", 'acf'); ?></label>
				<p class="description"><?php _e("WordPress currently only support fetching sites from one site status per query", 'acf'); ?></p>
			</td>
			<td>
				<?php
				do_action('acf/create_field', array(
					'type'    =>  'radio',
					'name'    =>  'fields[' . $key . '][site_status]',
					'value'   =>  $field['site_status'],
					'layout'  =>  'horizontal',
					'choices' =>  array(
						'public' => __('Public'),
						'archived' => __('Archived'),
						'mature' => __('Mature'),
						'spam' => __('Spam'),
						'deleted' => __('Deleted')
					)
				));
				?>
			</td>
		</tr>
		<tr class="field_option field_option_<?php echo $this->name; ?>">
			<td class="label">
				<label><?php _e("Hide main site?", 'acf'); ?></label>
				<p class="description"><?php _e("", 'acf'); ?></p>
			</td>
			<td>
				<?php
				do_action('acf/create_field', array(
					'type'    =>  'radio',
					'name'    =>  'fields[' . $key . '][hide_main]',
					'value'   =>  $field['hide_main'],
					'layout'  =>  'horizontal',
					'choices' =>  array(
						'yes' => __('Yes'),
						'no' => __('No'),
					)
				));
				?>
			</td>
		</tr>
		<tr class="field_option field_option_<?php echo $this->name; ?>">
			<td class="label">
				<label><?php _e("Allow null", 'acf'); ?></label>
				<p class="description"><?php _e("", 'acf'); ?></p>
			</td>
			<td>
				<?php
				do_action('acf/create_field', array(
					'type'    =>  'radio',
					'name'    =>  'fields[' . $key . '][allow_null]',
					'value'   =>  $field['allow_null'],
					'layout'  =>  'horizontal',
					'choices' =>  array(
						'yes' => __('Yes'),
						'no' => __('No'),
					)
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

	function create_field( $field )
	{
		// defaults?
		$field = array_merge($this->defaults, $field);
		

		// value must be array
		if( !is_array($field['value']) ){
			// perhaps this is a default value with new lines in it?
			if( strpos($field['value'], "\n") !== false ){
				// found multiple lines, explode it
				$field['value'] = explode("\n", $field['value']);
			}else{
				$field['value'] = array( $field['value'] );
			}
		}
		
		// trim value
		$field['value'] = array_map('trim', $field['value']);
		
		if(!is_multisite()){
			echo '<div><p>' . __('You need to have set up a multisite network for this field to work', 'acf_sites') . '</p></div>';
		}else{
			$checkbox_select = $field['checkbox_select'];
			$site_status = $field['site_status'];
			$hide_main = $field['hide_main'];
			$allow_null = $field['allow_null'];
			global $wpdb;
			$args = array();
			$args[$site_status] = true;
			if($hide_main == 'yes'){
				$args['offset'] = 1;
			}
			$sites = wp_get_sites($args);
			if(!empty($sites)){ // we have some sites
				echo '<input type="hidden" name="' .  esc_attr($field['name']) . '" value="" />';
				if($checkbox_select == 'select'){
					echo '<select name="' . esc_attr($field['name']) . '" class="' . esc_attr($field['class']) . '">';
					if($allow_null == 'yes'){
						echo '<option value="">' . __('select a site…') . '</option>';
					}
						foreach($sites as $site){
							if( in_array($site['blog_id'], $field['value']) ){
								$atts = 'selected="selected"';
							}
							if( isset($field['disabled']) && in_array($key, $field['disabled']) ){
								$atts .= ' disabled="true"';
							}
							$sitedetails = get_blog_details(array('blog_id' => $site['blog_id']));
							echo '<option value="' . $site['blog_id'] . '"' . $atts .  '>' . $sitedetails->blogname . '</option>';
						}
					echo '</select>';
				}else if($checkbox_select == 'checkbox'){
					foreach($sites as $site){
						if( in_array($site['blog_id'], $field['value']) ){
							$atts = 'checked="checked"';
						}
						if( isset($field['disabled']) && in_array($key, $field['disabled']) ){
							$atts .= ' disabled="true"';
						}
						$sitedetails = get_blog_details(array('blog_id' => $site['blog_id']));
						echo '<label for="selected_sites_' . esc_attr($site['blog_id']) . '"><input type="checkbox" name="' . esc_attr($field['name']) . '[]" id="selected_sites_' . esc_attr($site['blog_id']) . '" value="' . esc_attr($site['blog_id']) . '"' . $atts . ' />' . $sitedetails->blogname . '</label>';
					}
				}
			}else{
				echo '<div><p>' . __('No sites found, check your filter settings in the acf field', 'acf_sites') . '</p></div>';
			}
		}
	}


	/*
	*  input_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
	*  Use this action to add css + javascript to assist your create_field() action.
	*
	*  $info	http://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/

	function input_admin_enqueue_scripts()
	{
		// Note: This function can be removed if not used


		// register acf scripts
		wp_register_script('acf-input-sites', $this->settings['dir'] . 'js/input.js', array('acf-input'), $this->settings['version']);
		wp_register_style('acf-input-sites', $this->settings['dir'] . 'css/input.css', array('acf-input'), $this->settings['version']);


		// scripts
		wp_enqueue_script(array(
			'acf-input-sites',
		));

		// styles
		wp_enqueue_style(array(
			'acf-input-sites',
		));

	}




	/*
	*  format_value_for_api()
	*
	*  This filter is appied to the $value after it is loaded from the db and before it is passed back to the api functions such as the_field
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

	function format_value_for_api($value, $post_id, $field)
	{
		// defaults?
		$field = array_merge($this->defaults, $field);

		// make sure even select returns an array, for consistency!
		$checkbox_select = $field['checkbox_select'];
		$value = ($checkbox_select == 'select' ? array($value) : $value);

		// Note: This function can be removed if not used
		return $value;
	}


}


// create field
new acf_field_sites();

?>
