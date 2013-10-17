<?php
/*
Plugin Name: Advanced Custom Fields: Gallery Field
Plugin URI: http://www.advancedcustomfields.com/
Description: Adds the gallery field
Version: 1.0.0
Author: Elliot Condon
Author URI: http://www.elliotcondon.com/
License: GPL
Copyright: Elliot Condon
*/


class acf_gallery_plugin
{

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
			'version' => '1.0.0',
			'remote' => 'http://download.advancedcustomfields.com/GF72-8ME6-JS15-3PZC/info/',
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
		include_once('gallery.php');
	}
	
}

new acf_gallery_plugin();

?>
