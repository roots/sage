<?php
/*
Plugin Name: Advanced Custom Fields: Gravity Forms Field
Plugin URI: https://github.com/stormuk/Gravity-Forms-ACF-Field
Description: ACF field to select one or many Gravity Forms
Version: 1.0.0
Author: @adam_pope of @stormuk
Author URI: http://www.stormconsultancy.co.uk
License: MIT
License URI: http://opensource.org/licenses/MIT
*/


class acf_field_gravity_forms_plugin
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

		// version 4+
		add_action('acf/register_fields', array($this, 'register_fields'));


		// version 3-
		add_action( 'init', array( $this, 'init' ), 5);
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
			register_field('acf_field_gravity_forms', dirname(__File__) . '/gravity_forms-v3.php');
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
		include_once('gravity_forms-v4.php');
	}

}

new acf_field_gravity_forms_plugin();

?>