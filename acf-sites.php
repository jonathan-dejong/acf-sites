<?php

/*
Plugin Name: Advanced Custom Fields: Sites
Plugin URI: https://github.com/jonathan-dejong/acf-sites
Description: Extension for ACF which provides the user with either a dropdown or checkboxes to select a networks sites from. Returns the blog IDs to do with as you wish!
Version: 1.2.0
Author: Jonathan de Jong
Author URI: tigerton.se
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/




// 1. set text domain
// Reference: https://codex.wordpress.org/Function_Reference/load_plugin_textdomain
load_plugin_textdomain( 'acf-sites', false, dirname( plugin_basename(__FILE__) ) . '/lang/' ); 




// 2. Include field type for ACF5
// $version = 5 and can be ignored until ACF6 exists
function include_field_types_sites( $version ) {
	
	include_once('acf-sites-v5.php');
	
}

add_action('acf/include_field_types', 'include_field_types_sites');	




// 3. Include field type for ACF4
function register_fields_sites() {
	
	include_once('acf-sites-v4.php');
	
}

add_action('acf/register_fields', 'register_fields_sites');	



	
?>