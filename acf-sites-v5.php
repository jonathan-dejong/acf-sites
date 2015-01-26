<?php

class acf_field_sites extends acf_field {
	
	
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
	
	function __construct() {
		
		/*
		*  name (string) Single word, no spaces. Underscores allowed
		*/
		
		$this->name = 'sites';
		
		
		/*
		*  label (string) Multiple words, can include spaces, visible when selecting a field type
		*/
		
		$this->label = __('Sites', 'acf-sites');
		
		
		/*
		*  category (string) basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME
		*/
		
		$this->category = 'relational';
		
		
		/*
		*  defaults (array) Array of default settings which are merged into the field object. These are used later in settings
		*/
		
		$this->defaults = array(
			'checkbox_select'	=> 'select',
			'site_status' => '',
			'hide_main' => 'yes',
			'allow_null' => 'no',
			'return_value' => 'id'
		);
		
		
		/*
		*  l10n (array) Array of strings that are used in JavaScript. This allows JS strings to be translated in PHP and loaded via:
		*  var message = acf._e('sites', 'error');
		*/
		
		$this->l10n = array(
			'error'	=> __('Error! Please enter a higher value', 'acf-sites'),
		);
		
				
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
	
	function render_field_settings( $field ) {
		
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
			'label'			=> __('Show as checkboxes or select dropdown?','acf-sites'),
			'instructions'	=> __('','acf-sites'),
			'type'			=> 'radio',
			'name'			=> 'checkbox_select',
			'choices' =>  array(
				'checkbox' => __('Checkbox (allows multiple)'),
				'select' => __('Select'),
			)
		));
		
		acf_render_field_setting( $field, array(
			'label'			=> __('Select which site status to select','acf-sites'),
			'instructions'	=> __('WordPress currently only support fetching sites from one site status per query','acf-sites'),
			'type'			=> 'radio',
			'name'			=> 'site_status',
			'layout'  =>  'horizontal',
			'choices' =>  array(
				'public' => __('Public'),
				'archived' => __('Archived'),
				'mature' => __('Mature'),
				'spam' => __('Spam'),
				'deleted' => __('Deleted')
			)
		));
		
		acf_render_field_setting( $field, array(
			'label'			=> __('Hide main site?','acf-sites'),
			'instructions'	=> __('','acf-sites'),
			'type'			=> 'radio',
			'name'			=> 'hide_main',
			'layout'  =>  'horizontal',
			'choices' =>  array(
				'yes' => __('Yes'),
				'no' => __('No')
			)
		));
		
		acf_render_field_setting( $field, array(
			'label'			=> __('Allow null','acf-sites'),
			'instructions'	=> __('','acf-sites'),
			'type'			=> 'radio',
			'name'			=> 'allow_null',
			'layout'  =>  'horizontal',
			'choices' =>  array(
				'yes' => __('Yes'),
				'no' => __('No')
			)
		));
		
		acf_render_field_setting( $field, array(
			'label'			=> __('Return value','acf-sites'),
			'instructions'	=> __('','acf-sites'),
			'type'			=> 'radio',
			'name'			=> 'return_value',
			'layout'  =>  'horizontal',
			'choices' =>  array(
				'id' => __('ID'),
				'name' => __('Name'),
				'siteurl' => __('URL')
			)
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
	
	function render_field( $field ) {
		
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
		
		$dir = plugin_dir_url( __FILE__ );
		
		
		// register & include JS
		wp_register_script( 'acf-input-sites', "{$dir}js/input.js" );
		wp_enqueue_script('acf-input-sites');
		
		
		// register & include CSS
		wp_register_style( 'acf-input-sites', "{$dir}css/input.css" ); 
		wp_enqueue_style('acf-input-sites');
		
		
	}
	
	
	
	
	/*
	*  input_admin_head()
	*
	*  This action is called in the admin_head action on the edit screen where your field is created.
	*  Use this action to add CSS and JavaScript to assist your render_field() action.
	*
	*  @type	action (admin_head)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*
		
	function input_admin_head() {
	
		
		
	}
	
	*/
	
	
	/*
   	*  input_form_data()
   	*
   	*  This function is called once on the 'input' page between the head and footer
   	*  There are 2 situations where ACF did not load during the 'acf/input_admin_enqueue_scripts' and 
   	*  'acf/input_admin_head' actions because ACF did not know it was going to be used. These situations are
   	*  seen on comments / user edit forms on the front end. This function will always be called, and includes
   	*  $args that related to the current screen such as $args['post_id']
   	*
   	*  @type	function
   	*  @date	6/03/2014
   	*  @since	5.0.0
   	*
   	*  @param	$args (array)
   	*  @return	n/a
   	*/
   	
   	/*
   	
   	function input_form_data( $args ) {
	   	
		
	
   	}
   	
   	*/
	
	
	/*
	*  input_admin_footer()
	*
	*  This action is called in the admin_footer action on the edit screen where your field is created.
	*  Use this action to add CSS and JavaScript to assist your render_field() action.
	*
	*  @type	action (admin_footer)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*
		
	function input_admin_footer() {
	
		
		
	}
	
	*/
	
	
	/*
	*  field_group_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is edited.
	*  Use this action to add CSS + JavaScript to assist your render_field_options() action.
	*
	*  @type	action (admin_enqueue_scripts)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*
	
	function field_group_admin_enqueue_scripts() {
		
	}
	
	*/

	
	/*
	*  field_group_admin_head()
	*
	*  This action is called in the admin_head action on the edit screen where your field is edited.
	*  Use this action to add CSS and JavaScript to assist your render_field_options() action.
	*
	*  @type	action (admin_head)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*
	
	function field_group_admin_head() {
	
	}
	
	*/


	/*
	*  load_value()
	*
	*  This filter is applied to the $value after it is loaded from the db
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
	function load_value( $value, $post_id, $field ) {
		
		$returnvalue = $field['return_value'];
		if($returnvalue == 'id'){
			return $value;
		}elseif($returnvalue == 'name'){
			$new_value = array();
			if($value){
				foreach($value as $site_id){
					$new_value[] = get_blog_details($site_id)->blogname;
				}
				return $new_value;
			}
		}elseif($returnvalue == 'siteurl'){
			$new_value = array();
			if($value){
				foreach($value as $site_id){
					$new_value[] = get_blog_details($site_id)->siteurl;
				}
				return $new_value;
			}
		}
		
		return $value;
		
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
	
	/*
	
	function update_value( $value, $post_id, $field ) {
		
		return $value;
		
	}
	
	*/
	
	
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
		if( empty($value) ){
			return $value;
		}

		// make sure even select returns an array, for consistency!
		$checkbox_select = $field['checkbox_select'];
		$value = ($checkbox_select == 'select' ? array($value) : $value);
		
		
		// return
		return $value;
	}
	
	
	
	/*
	*  validate_value()
	*
	*  This filter is used to perform validation on the value prior to saving.
	*  All values are validated regardless of the field's required setting. This allows you to validate and return
	*  messages to the user if the value is not correct
	*
	*  @type	filter
	*  @date	11/02/2014
	*  @since	5.0.0
	*
	*  @param	$valid (boolean) validation status based on the value and the field's required setting
	*  @param	$value (mixed) the $_POST value
	*  @param	$field (array) the field array holding all the field options
	*  @param	$input (string) the corresponding input name for $_POST value
	*  @return	$valid
	*/
	
	/*
	
	function validate_value( $valid, $value, $field, $input ){
		
		// Basic usage
		if( $value < $field['custom_minimum_setting'] )
		{
			$valid = false;
		}
		
		
		// Advanced usage
		if( $value < $field['custom_minimum_setting'] )
		{
			$valid = __('The value is too little!','acf-sites'),
		}
		
		
		// return
		return $valid;
		
	}
	
	*/
	
	
	/*
	*  delete_value()
	*
	*  This action is fired after a value has been deleted from the db.
	*  Please note that saving a blank value is treated as an update, not a delete
	*
	*  @type	action
	*  @date	6/03/2014
	*  @since	5.0.0
	*
	*  @param	$post_id (mixed) the $post_id from which the value was deleted
	*  @param	$key (string) the $meta_key which the value was deleted
	*  @return	n/a
	*/
	
	/*
	
	function delete_value( $post_id, $key ) {
		
		
		
	}
	
	*/
	
	
	/*
	*  load_field()
	*
	*  This filter is applied to the $field after it is loaded from the database
	*
	*  @type	filter
	*  @date	23/01/2013
	*  @since	3.6.0	
	*
	*  @param	$field (array) the field array holding all the field options
	*  @return	$field
	*/
	
	/*
	
	function load_field( $field ) {
		
		return $field;
		
	}	
	
	*/
	
	
	/*
	*  update_field()
	*
	*  This filter is applied to the $field before it is saved to the database
	*
	*  @type	filter
	*  @date	23/01/2013
	*  @since	3.6.0
	*
	*  @param	$field (array) the field array holding all the field options
	*  @return	$field
	*/
	
	/*
	
	function update_field( $field ) {
		
		return $field;
		
	}	
	
	*/
	
	
	/*
	*  delete_field()
	*
	*  This action is fired after a field is deleted from the database
	*
	*  @type	action
	*  @date	11/02/2014
	*  @since	5.0.0
	*
	*  @param	$field (array) the field array holding all the field options
	*  @return	n/a
	*/
	
	/*
	
	function delete_field( $field ) {
		
		
		
	}	
	
	*/
	
	
}


// create field
new acf_field_sites();

?>
