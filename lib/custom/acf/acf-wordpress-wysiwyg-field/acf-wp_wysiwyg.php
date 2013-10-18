<?php
/*
Plugin Name: Advanced Custom Fields: WP WYSIWYG
Plugin URI: {{git_url}}
Description: Adds a native WordPress WYSIWYG field to the Advanced Custom Fields plugin. Please note this field does not work as a sub field.
Version: 1.0.0
Author: Elliot Condon
Author URI: http://www.elliotcondon.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/


class acf_field_wp_wysiwyg_plugin
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
		$domain = 'acf-wp_wysiwyg';
		$mofile = trailingslashit(dirname(__File__)) . 'lang/' . $domain . '-' . get_locale() . '.mo';
		load_textdomain( $domain, $mofile );
		*/
		
		
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
			register_field('acf_field_wp_wysiwyg', dirname(__File__) . '/wp_wysiwyg-v3.php');
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
		include_once('wp_wysiwyg-v4.php');
	}
	
}

new acf_field_wp_wysiwyg_plugin();
		
?>
