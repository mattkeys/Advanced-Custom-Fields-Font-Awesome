<?php
/*
Plugin Name: Advanced Custom Fields: Font Awesome
Description: Add a Font Awesome field type to Advanced Custom Fields
Version: 1.1.1
Author: Matt Keys
Author URI: http://mattkeys.me/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/


class acf_field_font_awesome_plugin
{
	/*
	*  Construct
	*
	*  @description:
	*  @since: 3.6
	*  @created: 1/04/13
	*/

	function __construct()
	{
		// set text domain
		/*
		$domain = 'acf-font-awesome';
		$mofile = trailingslashit(dirname(__File__)) . 'lang/' . $domain . '-' . get_locale() . '.mo';
		load_textdomain( $domain, $mofile );
		*/


		// version 4+
		add_action('acf/register_fields', array($this, 'register_fields'));

	}

	/*
	*  register_fields
	*
	*  @description:
	*  @since: 3.6
	*  @created: 1/04/13
	*/

	function register_fields()
	{
		include_once('font-awesome-v4.php');
	}

}

new acf_field_font_awesome_plugin();

?>
