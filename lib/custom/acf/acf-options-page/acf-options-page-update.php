<?php

/*
*  acf_options_page_plugin_update
*
*  this class will connect with and download updates from the ACF website
*
*  IMPORTANT: 	This file must be removed from the add-on if you are distibuting this add-on within a plugin or theme.
*  				to read more about the terms & conditions regarding add-ons, please refer to the documentation here:
*				http://www.advancedcustomfields.com/terms-conditions/
*
*  @type	class
*  @date	13/07/13
*
*/

class acf_options_page_plugin_update
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
		$this->settings = array(
			'version'	=>	'',
			'remote'	=>	'http://download.advancedcustomfields.com/OPN8-FA4J-Y2LW-81LS/info/',
			'basename'	=>	plugin_basename( str_replace('-update.php', '.php', __FILE__) ),
			'slug'		=>	dirname( plugin_basename( str_replace('-update.php', '.php', __FILE__) ) )
		);
		
		
		// actions
		add_action('in_plugin_update_message-' . $this->settings['basename'], array($this, 'in_plugin_update_message'), 10, 2 );
		
		
		// filters
		add_filter('pre_set_site_transient_update_plugins', array($this, 'check_update'));
		add_filter('plugins_api', array($this, 'check_info'), 10, 3);
	}
	
	
	
	/*
	*  in_plugin_update_message
	*
	*  Displays an update message for plugin list screens.
	*  Shows only the version updates from the current until the newest version
	*
	*  @type	function
	*  @date	5/06/13
	*
	*  @param	{array}		$plugin_data
	*  @param	{object}	$r
	*/

	function in_plugin_update_message( $plugin_data, $r )
	{
		// vars
		$readme = wp_remote_fopen( str_replace( '/info/' , '/trunk/readme.txt', $this->settings['remote'] ) );
		$regexp = '/== Changelog ==(.*)= ' . $this->get_version() . ' =/sm';
		$o = '';
		
		
		// validate
		if( !$readme )
		{
			return;
		}


		// regexp
		preg_match( $regexp, $readme, $matches );
		
		
		if( ! isset($matches[1]) )
		{
			return;
		}
		
		
		// add style	
		$o .= '<style type="text/css">';
		$o .= '#advanced-custom-fields-options-page + .plugin-update-tr .update-message { background: #EAF2FA; border: #C7D7E2 solid 1px; padding: 10px; }';
		$o .= '</style>';


		// render changelog
		$changelog = explode('*', $matches[1]);
		array_shift( $changelog );


		if( !empty($changelog) )
		{
			$o .= '<div class="acf-plugin-update-info">';
			$o .= '<h3>' . __("What's new", 'acf') . '</h3>';
			$o .= '<ul>';

			foreach( $changelog as $item )
			{
				$o .= '<li>' . make_clickable( $item ) . '</li>';
			}

			$o .= '</ul></div>';
		}

		echo $o;


	}
	
	
	/*
	*  get_remote
	*
	*  @description: 
	*  @since: 3.6
	*  @created: 31/01/13
	*/
	
	function get_remote()
	{
		 // vars
        $info = false;
        
        
		// Get the remote info
        $request = wp_remote_post( $this->settings['remote'] );
        if( !is_wp_error($request) || wp_remote_retrieve_response_code($request) === 200)
        {
            $info = @unserialize($request['body']);
            $info->slug = $this->settings['slug'];
        }
        
        
        return $info;
	}
	
	
	/*
	*  check_update
	*
	*  @description: 
	*  @since: 3.6
	*  @created: 31/01/13
	*/
	
	function check_update( $transient )
	{
	    if( empty($transient->checked) )
	    {
            return $transient;
        }

        
        // vars
        $info = $this->get_remote();
        
        
        // validate
        if( !$info )
        {
	        return $transient;
        }
        
        
        // compare versions
        if( version_compare($info->version, $this->get_version(), '<=') )
        {
        	return $transient;
        }

        
        // create new object for update
        $obj = new stdClass();
        $obj->slug = $info->slug;
        $obj->new_version = $info->version;
        $obj->url = $info->homepage;
        $obj->package = $info->download_link;
        
        
        // add to transient
        $transient->response[ $this->settings['basename'] ] = $obj;
        
        
        return $transient;
	}
	
	
	/*
	*  check_info
	*
	*  @description: 
	*  @since: 3.6
	*  @created: 31/01/13
	*/
	
    function check_info( $false, $action, $arg )
    {
    	// validate
    	if( !isset($arg->slug) || $arg->slug != $this->settings['slug'] )
    	{
	    	return $false;
    	}
    	
    	
    	if( $action == 'plugin_information' )
    	{
	    	$false = $this->get_remote();
    	}
    	
    	        
        return $false;
    }
    
    
    /*
    *  get_version
    *
    *  This function will return the current version of this add-on 
    *
    *  @type	function
    *  @date	27/08/13
    *
    *  @param	N/A
    *  @return	(string)
    */
    
    function get_version()
    {
    	// populate only once
    	if( !$this->settings['version'] )
    	{
	    	$plugin_data = get_plugin_data( str_replace('-update.php', '.php', __FILE__) );
	    	
	    	$this->settings['version'] = $plugin_data['Version'];
    	}
    	
    	// return
    	return $this->settings['version'];
	}
}


// instantiate
if( is_admin() )
{
	new acf_options_page_plugin_update();
}

?>
