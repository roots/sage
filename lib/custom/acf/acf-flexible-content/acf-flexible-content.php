<?php
/*
Plugin Name: Advanced Custom Fields: Flexible Content Field
Plugin URI: http://www.advancedcustomfields.com/
Description: Adds the flexible content field
Version: 1.0.2
Author: Elliot Condon
Author URI: http://www.elliotcondon.com/
License: GPL
Copyright: Elliot Condon
*/


class acf_flexible_content_plugin
{
	var $settings;
	
	
	/*
	*  Constructor
	*
	*  @description: 
	*  @since 1.0.0
	*  @created: 23/06/12
	*/
	
	function __construct()
	{
		// vars
		$settings = array(
			'version' => '1.0.2',
			'remote' => 'http://download.advancedcustomfields.com/FC9O-H6VN-E4CL-LT33/info/',
			'basename' => plugin_basename(__FILE__),
		);
		
		
		// create remote update
		if( is_admin() )
		{
			if( !class_exists('acf_remote_update') )
			{
				include_once('acf-remote-update.php');
			}
			
			new acf_remote_update( $settings );
		}
		
		
		// actions
		add_action('acf/register_fields', array($this, 'register_fields'));

	}
	
	
	/*
	*  register_fields
	*
	*  @description: 
	*  @since: 3.6
	*  @created: 31/01/13
	*/
	
	function register_fields()
	{
		include_once('flexible-content.php');
	}
	
}

new acf_flexible_content_plugin();

?>
