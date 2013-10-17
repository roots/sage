<?php
/*
Plugin Name: Advanced Custom Fields: Repeater Field
Plugin URI: http://www.advancedcustomfields.com/
Description: Adds the repeater field
Version: 1.0.1
Author: Elliot Condon
Author URI: http://www.elliotcondon.com/
License: GPL
Copyright: Elliot Condon
*/


class acf_repeater_plugin
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
			'version' => '1.0.1',
			'remote' => 'http://download.advancedcustomfields.com/QJF7-L4IX-UCNP-RF2W/info/',
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
		include_once('repeater.php');
	}
		
}

new acf_repeater_plugin();

?>
