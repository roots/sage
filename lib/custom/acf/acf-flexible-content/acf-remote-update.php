<?php

/*
*  acf_remote_update
*
*  @description: Handles all plugin updates
*  @since: 3.6
*  @created: 2/02/13
*/

class acf_remote_update
{
	var $settings;
	
	
	/*
	*  Constructor
	*
	*  @description: 
	*  @since 1.0.0
	*  @created: 23/06/12
	*/
	
	function __construct( $options )
	{
		// Add slug
		$options['slug'] = current( explode('/', $options['basename']) );
		
		
		// devine settins
		$this->settings = $options;
		

		// update
		add_filter('pre_set_site_transient_update_plugins', array($this, 'check_update'));
		add_filter('plugins_api', array($this, 'check_info'), 10, 3);

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
        if( version_compare($info->version, $this->settings['version'], '<=') )
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
}

?>
