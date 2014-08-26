<?php

class acf_field_blogs extends acf_Field
{

	// vars
	var $settings, // will hold info such as dir / path
		$defaults; // will hold default field options


	/*--------------------------------------------------------------------------------------
	*
	*	Constructor
	*	- This function is called when the field class is initalized on each page.
	*	- Here you can add filters / actions and setup any other functionality for your field
	*
	*	@author Elliot Condon
	*	@since 2.2.0
	*
	*-------------------------------------------------------------------------------------*/

	function __construct($parent)
	{

			// do not delete!
	  	parent::__construct($parent);
	
	  	// set name / title
	  	$this->name = 'blogs';
	  	$this->title = __('Blogs');
		$this->defaults = array(
			'checkbox_select' => 'checkbox',
			'site_status' => 'public',
			'hide_main' => 'yes',
			'allow_null' => 'yes'
		);

		// settings
		$this->settings = array(
			'path' => $this->helpers_get_path(__FILE__),
			'dir' => $this->helpers_get_dir(__FILE__),
			'version' => '1.0.0'
		);

  }


 	/*
  *  helpers_get_path
  *
  *  @description: calculates the path (works for plugin / theme folders)
  *  @since: 3.6
  *  @created: 30/01/13
  */

  function helpers_get_path($file)
  {
    return trailingslashit(dirname($file));
  }


  /*
  *  helpers_get_dir
  *
  *  @description: calculates the directory (works for plugin / theme folders)
  *  @since: 3.6
  *  @created: 30/01/13
  */

  function helpers_get_dir($file)
  {
    $dir = trailingslashit(dirname($file));
    $count = 0;


    // sanitize for Win32 installs
    $dir = str_replace('\\', '/', $dir);


    // if file is in plugins folder
    $wp_plugin_dir = str_replace('\\', '/', WP_PLUGIN_DIR);
    $dir = str_replace($wp_plugin_dir, WP_PLUGIN_URL, $dir, $count);


    if($count < 1)
    {
      // if file is in wp-content folder
      $wp_content_dir = str_replace('\\', '/', WP_CONTENT_DIR);
      $dir = str_replace($wp_content_dir, WP_CONTENT_URL, $dir, $count);
    }


    if($count < 1)
    {
      // if file is in ??? folder
      $wp_dir = str_replace('\\', '/', ABSPATH);
      $dir = str_replace($wp_dir, site_url('/'), $dir);
    }

    return $dir;
  }


	/*--------------------------------------------------------------------------------------
	*
	*	create_options
	*	- this function is called from core/field_meta_box.php to create extra options
	*	for your field
	*
	*	@params
	*	- $key (int) - the $_POST obejct key required to save the options to the field
	*	- $field (array) - the field object
	*
	*	@author Elliot Condon
	*	@since 2.2.0
	*
	*-------------------------------------------------------------------------------------*/

	function create_options($key, $field)
	{
		// defaults?
		/*
		$field = array_merge($this->defaults, $field);
		*/


		// Create Field Options HTML
		?>

		<tr class="field_option field_option_<?php echo $this->name; ?>">
			<td class="label">
				<label><?php _e("Show as checkboxes or select dropdown?", 'acf'); ?></label>
				<p class="description"><?php _e("", 'acf'); ?></p>
			</td>
			<td>
				<?php
				$this->parent->create_field(array(
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
				$this->parent->create_field(array(
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
				$this->parent->create_field(array(
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
				$this->parent->create_field(array(
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


	/*--------------------------------------------------------------------------------------
	*
	*	create_field
	*	- this function is called on edit screens to produce the html for this field
	*
	*	@author Elliot Condon
	*	@since 2.2.0
	*
	*-------------------------------------------------------------------------------------*/

	function create_field($field)
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
							$atts = '';
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
						$atts = '';
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




	/*--------------------------------------------------------------------------------------
	*
	*	admin_print_scripts / admin_print_styles
	*	- this function is called in the admin_print_scripts / admin_print_styles where
	*	your field is created. Use this function to register css and javascript to assist
	*	your create_field() function.
	*
	*	@author Elliot Condon
	*	@since 3.0.0
	*
	*-------------------------------------------------------------------------------------*/


	function admin_print_styles()
	{
		// Note: This function can be removed if not used


		wp_register_style('acf-input-blogs', $this->settings['dir'] . 'css/input.css', array('acf-input'), $this->settings['version']);

		// styles
		wp_enqueue_style(array(
			'acf-input-blogs',
		));
	}



	/*--------------------------------------------------------------------------------------
	*
	*	get_value_for_api
	*	- called from your template file when using the API functions (get_field, etc).
	*	This function is useful if your field needs to format the returned value
	*
	*	@params
	*	- $post_id (int) - the post ID which your value is attached to
	*	- $field (array) - the field object.
	*
	*	@author Elliot Condon
	*	@since 3.0.0
	*
	*-------------------------------------------------------------------------------------*/

	function get_value_for_api($post_id, $field)
	{
		// Note: This function can be removed if not used

		// get value
		$value = $this->get_value($post_id, $field);
		
		// defaults?
		$field = array_merge($this->defaults, $field);

		// make sure even select returns an array, for consistency!
		$checkbox_select = $field['checkbox_select'];
		$value = ($checkbox_select == 'select' ? array($value) : $value);

		// return value
		return $value;

	}

}

?>
