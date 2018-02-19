<?php

/*
Plugin Name: Advanced Custom Fields: Font Awesome
Plugin URI: https://wordpress.org/plugins/advanced-custom-fields-font-awesome/
Description: Adds a new 'Font Awesome Icon' field to the popular Advanced Custom Fields plugin.
Version: 2.1.2
Author: mattkeys
Author URI: http://mattkeys.me/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists('acf_plugin_font_awesome') ) :

	require 'assets/inc/class-ACFFAL.php';

	class acf_plugin_font_awesome {

		public function __construct()
		{
			$this->settings = array(
				'version'	=> '2.1.2',
				'url'		=> plugin_dir_url( __FILE__ ),
				'path'		=> plugin_dir_path( __FILE__ )
			);

			load_plugin_textdomain( 'acf-font-awesome', false, plugin_basename( dirname( __FILE__ ) ) . '/lang' ); 

			add_action('acf/include_field_types', 	array($this, 'include_field_types'), 10 ); // v5
			add_action('acf/register_fields', 		array($this, 'include_field_types'), 10 ); // v4		
		}

		public function include_field_types( $version = false )
		{
			if ( ! $version ) {
				$version = 4;
			}

			include_once('fields/acf-font-awesome-v' . $version . '.php');
			
		}
		
	}

	new acf_plugin_font_awesome();

endif;
