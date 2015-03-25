<?php
/*
Plugin Name: Advanced Custom Fields: Font Awesome
Description: Add a Font Awesome field type to Advanced Custom Fields
Version: 1.6.2
Author: Matt Keys
Author URI: http://mattkeys.me/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// Include field type for ACF5
function include_field_types_font_awesome( $version ) {
	
	include_once('acf-font-awesome-v5.php');
	
}

add_action('acf/include_field_types', 'include_field_types_font_awesome');	

// Include field type for ACF4
function register_fields_font_awesome() {
	
	include_once('acf-font-awesome-v4.php');
	
}

add_action('acf/register_fields', 'register_fields_font_awesome');
