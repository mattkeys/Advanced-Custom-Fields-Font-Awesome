<?php

/*
Plugin Name: Advanced Custom Fields: Font Awesome
Plugin URI: https://wordpress.org/plugins/advanced-custom-fields-font-awesome/
Description: Adds a new 'Font Awesome Icon' field to the popular Advanced Custom Fields plugin.
Version: 3.0.0-beta3
Author: mattkeys
Author URI: http://mattkeys.me/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'ACFFA_PUBLIC_PATH' ) ) {
	define( 'ACFFA_PUBLIC_PATH', plugin_dir_url( __FILE__ ) );
}

if ( is_admin() ) {
	require 'admin/class-ACFFA-Admin.php';
}

if ( ! class_exists('acf_plugin_font_awesome') ) :

	$acffa_settings			= get_option( 'acffa_settings' );
	$acffa_major_version	= isset( $acffa_settings['acffa_major_version'] ) ? intval( $acffa_settings['acffa_major_version'] ) : 4;

	define( 'ACFFA_MAJOR_VERSION', $acffa_major_version );

	if ( version_compare( $acffa_major_version, 5, '<' ) ) {
		require 'assets/inc/class-ACFFA-Loader-4.php';
	} else {
		require 'assets/inc/class-ACFFA-Loader-5.php';
	}

	class acf_plugin_font_awesome {

		public function __construct()
		{
			$this->settings = array(
				'version'	=> '3.0.0-beta3',
				'url'		=> plugin_dir_url( __FILE__ ),
				'path'		=> plugin_dir_path( __FILE__ )
			);

			load_plugin_textdomain( 'acf-font-awesome', false, plugin_basename( dirname( __FILE__ ) ) . '/lang' ); 

			add_action('acf/include_field_types', 	array($this, 'include_field_types'), 10 ); // v5
		}

		public function include_field_types( $version = false )
		{
			if ( ! $version ) {
				$version = 5;
			}

			include_once('fields/acf-font-awesome-v' . $version . '.php');
			
		}
		
	}

	new acf_plugin_font_awesome();

endif;
