<?php

/*
Plugin Name: Advanced Custom Fields: Sites
Plugin URI: https://github.com/jonathan-dejong/acf-sites
Description: Adds a sites field type to ACF. Allows for selection of one or multiple sites in a multisite network.
Version: 2.0.0
Author: Jonathan de Jong
Author URI: https://github.com/jonathan-dejong/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// check if class already exists
if ( ! class_exists( 'Acf_Plugin_Sites' ) ) :

	class Acf_Plugin_Sites {

		/*
		 *  __construct
		 *
		 *  This function will setup the class functionality
		 *
		 *  @type	function
		 *  @date	17/02/2016
		 *  @since	1.0.0
		 *
		 *  @param	n/a
		 *  @return	n/a
		 */

		function __construct() {

			// vars
			$this->settings = array(
				'version'	=> '2.0.0',
				'url'		=> plugin_dir_url( __FILE__ ),
				'path'		=> plugin_dir_path( __FILE__ ),
			);

			// set text domain
			// https://codex.wordpress.org/Function_Reference/load_plugin_textdomain
			load_plugin_textdomain( 'acf-sites', false, plugin_basename( dirname( __FILE__ ) ) . '/lang' );

			// include field
			add_action( 'acf/include_field_types', 	array( $this, 'include_field_types' ) ); // v5
			add_action( 'acf/register_fields', 		array( $this, 'include_field_types' ) ); // v4

		}


		/*
		 *  include_field_types
		 *
		 *  This function will include the field type class
		 *
		 *  @type	function
		 *  @date	17/02/2016
		 *  @since	1.0.0
		 *
		 *  @param	$version (int) major ACF version. Defaults to false
		 *  @return	n/a
		 */
		function include_field_types( $version = false ) {

			// support empty $version
			if ( ! $version ) {
				$version = 4;
			}

			// include
			include_once( 'fields/acf-sites-v' . $version . '.php' );

		}

	}


	// initialize
	new Acf_Plugin_Sites();

endif; // class_exists check
