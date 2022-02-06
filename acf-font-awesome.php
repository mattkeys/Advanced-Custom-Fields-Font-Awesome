<?php

/*
Plugin Name: Advanced Custom Fields: Font Awesome
Plugin URI: https://wordpress.org/plugins/advanced-custom-fields-font-awesome/
Description: Adds a new 'Font Awesome Icon' field to the popular Advanced Custom Fields plugin.
Version: 4.0.0
Author: mattkeys
Author URI: http://mattkeys.me/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'ACFFA_VERSION' ) ) {
	define( 'ACFFA_VERSION', '4.0.0' );
}

if ( ! defined( 'ACFFA_PUBLIC_PATH' ) ) {
	define( 'ACFFA_PUBLIC_PATH', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'ACFFA_BASENAME' ) ) {
	define( 'ACFFA_BASENAME', plugin_basename( __FILE__ ) );
}

if ( ! defined( 'ACFFA_DIRECTORY' ) ) {
	define( 'ACFFA_DIRECTORY', dirname( __FILE__ ) );
}

if ( ! class_exists('acf_plugin_font_awesome') ) :

	class acf_plugin_font_awesome {

		public function init()
		{
			$acffa_major_version = $this->get_major_version();

			if ( is_admin() ) {
				require 'admin/class-ACFFA-Admin.php';
			}

			$this->check_for_updates( $acffa_major_version );

			if ( $acffa_major_version == 4 ) {
				require 'assets/inc/class-ACFFA-Loader-4.php';
			} else if ( $acffa_major_version == 5 ) {
				require 'assets/inc/class-ACFFA-Loader-5.php';
			} else {
				require 'assets/inc/class-ACFFA-Loader-6.php';
			}

			$this->settings = [
				'version'	=> ACFFA_VERSION,
				'url'		=> plugin_dir_url( __FILE__ ),
				'path'		=> plugin_dir_path( __FILE__ )
			];

			load_plugin_textdomain( 'acf-font-awesome', false, plugin_basename( dirname( __FILE__ ) ) . '/lang' ); 

			if ( version_compare( $acffa_major_version, 6, '<' ) ) {
				include_once('fields/acf-font-awesome-v5.php');
			} else {
				include_once('fields/acf-font-awesome-v6.php');
			}
			
		}
		
		private function get_major_version()
		{
			$current_version		= get_option( 'ACFFA_current_version' );
			$acffa_settings			= get_option( 'acffa_settings', [] );
			$default_version		= ( $current_version && empty( $acffa_settings ) ) ? 4 : 6;

			$acffa_major_version	= isset( $acffa_settings['acffa_major_version'] ) ? intval( $acffa_settings['acffa_major_version'] ) : $default_version;
			$override_major_version	= (int) apply_filters( 'ACFFA_override_major_version', false );
			if ( $override_major_version ) {
				$override_major_version = floor( $override_major_version );

				if ( 4 == $override_major_version || 5 == $override_major_version || 6 == $override_major_version ) {
					if ( $acffa_major_version !== $override_major_version ) {
						$acffa_settings['acffa_major_version'] = $override_major_version;
						update_option( 'acffa_settings', $acffa_settings, false );

						do_action( 'ACFFA_refresh_latest_icons' );

						$acffa_major_version = $override_major_version;
					}

					if ( ! defined( 'ACFFA_OVERRIDE_MAJOR_VERSION' ) ) {
						define( 'ACFFA_OVERRIDE_MAJOR_VERSION', true );
					}
				}
			}

			if ( ! defined( 'ACFFA_MAJOR_VERSION' ) ) {
				define( 'ACFFA_MAJOR_VERSION', $acffa_major_version );
			}

			if ( ! isset( $acffa_settings['acffa_major_version'] ) ) {
				$acffa_settings['acffa_major_version'] = $acffa_major_version;
				$acffa_settings['acffa_plugin_version'] = ACFFA_VERSION;
				$acffa_settings['show_upgrade_notice'] = true;
				update_option( 'acffa_settings', $acffa_settings, false );
			}

			return $acffa_major_version;
		}

		private function check_for_updates( $acffa_major_version )
		{
			$acffa_settings			= get_option( 'acffa_settings', [] );
			$acffa_internal_version	= isset( $acffa_settings['acffa_plugin_version'] ) ? $acffa_settings['acffa_plugin_version'] : false;

			if ( ! $acffa_internal_version ) {
				return;
			}

			switch ( $acffa_major_version ) {
				case 4:
					if ( version_compare( $acffa_internal_version, '4.0.0', '<' ) ) {
						$acffa_settings['acffa_v5_compatibility_mode'] = 1;
					}
				case 5:
					if ( version_compare( $acffa_internal_version, '3.1.1', '<' ) ) {
						define( 'ACFFA_FORCE_REFRESH', true );
						do_action( 'ACFFA_refresh_latest_icons' );
					}
					if ( version_compare( $acffa_internal_version, '4.0.0', '<' ) ) {
						$acffa_settings['acffa_v5_compatibility_mode'] = 1;
					}
					break;
			}

			if ( $acffa_internal_version !== ACFFA_VERSION ) {
				$acffa_settings['acffa_plugin_version'] = ACFFA_VERSION;
				update_option( 'acffa_settings', $acffa_settings, false );
			}
		}
	}

	add_action( 'acf/include_field_types', [ new acf_plugin_font_awesome, 'init' ], 10 );

endif;
