<?php
/*
Plugin Name: Advanced Custom Fields: Date and Time Picker
Plugin URI: https://github.com/soderlind/acf-field-date-time-picker
Description: Date and Time Picker field for Advanced Custom Fields
Version: 2.0.11
Author: Per Soderlind
Author URI: http://soderlind.no
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/


class acf_field_date_time_picker_plugin
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
		$domain = 'acf-date_time_picker';
		$mofile = trailingslashit(dirname(__File__)) . 'lang/' . $domain . '-' . get_locale() . '.mo';
		load_textdomain( $domain, $mofile );


		// version 4+
		add_action('acf/register_fields', array($this, 'register_fields'));


		// version 3-
		add_action( 'init', array( $this, 'init' ));
	}


	/*
	*  Init
	*
	*  @description:
	*  @since: 3.6
	*  @created: 1/04/13
	*/

	function init()
	{
		if(function_exists('register_field'))
		{
			register_field('acf_field_date_time_picker', dirname(__File__) . '/date_time_picker-v3.php');
		}
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
		include_once('date_time_picker-v4.php');
	}

}

new acf_field_date_time_picker_plugin();

?>