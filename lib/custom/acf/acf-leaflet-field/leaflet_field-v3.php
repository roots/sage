<?php

class acf_field_leaflet_field extends acf_Field
{
	
	// vars
	var $settings, // will hold info such as dir / path
		$defaults; // will hold default field options

    // holds information about supported tile-providers
    static $map_providers = array(
        'openstreetmap' => array(
            'url'           => 'http://tile.openstreetmap.org/{z}/{x}/{y}.png',
            'requires_key'  => false,
            'nicename'      => 'OpenStreetMap',
            'attribution'   => 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors'
        ),
        'cloudmade'     => array(
            'url'           => "http://{s}.tile.cloudmade.com/{api_key}/997/256/{z}/{x}/{y}.png",
            'requires_key'  => true,
            'nicename'      => 'CloudMade',
            'attribution'   => 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://cloudmade.com">CloudMade</a>'
        )
    );
		
	/*--------------------------------------------------------------------------------------
	*
	*	Constructor
	*	- This function is called when the field class is initalized on each page.
	*	- Here you can add filters / actions and setup any other functionality for your field
	*
	*	@author Elliot Condon
	*	@since 2.2.0
	* 
	*-------------------------------------------------------------------------------------*/
	
	function __construct($parent)
	{
		// do not delete!
    	parent::__construct($parent);
    	
    	// set name / title
    	$this->name = 'leaflet_field';
		$this->title = __('Leaflet Field');
		$this->defaults = array(
			'lat'           => '55.606',
            'lng'           => '13.002',
            'zoom_level'    => 13,
            'height'        => 350,
            'api_key'       => '',
            'map_provider'  => 'openstreetmap'
		);
		
		
		// settings
		// settings
		$this->settings = array(
			'path' => $this->helpers_get_path( __FILE__ ),
			'dir' => $this->helpers_get_dir( __FILE__ ),
			'version' => '1.1.0'
		);
		
   	}
   	
   	
   	/*
    *  helpers_get_path
    *
    *  @description: calculates the path (works for plugin / theme folders)
    *  @since: 3.6
    *  @created: 30/01/13
    */
    
    function helpers_get_path( $file )
    {
        return trailingslashit(dirname($file));
    }
    
    
    
    /*
    *  helpers_get_dir
    *
    *  @description: calculates the directory (works for plugin / theme folders)
    *  @since: 3.6
    *  @created: 30/01/13
    */
    
    function helpers_get_dir( $file )
    {
        $dir = trailingslashit(dirname($file));
        $count = 0;
        
        
        // sanitize for Win32 installs
        $dir = str_replace('\\' ,'/', $dir); 
        
        
        // if file is in plugins folder
        $wp_plugin_dir = str_replace('\\' ,'/', WP_PLUGIN_DIR); 
        $dir = str_replace($wp_plugin_dir, WP_PLUGIN_URL, $dir, $count);
        
        
        if( $count < 1 )
        {
	        // if file is in wp-content folder
	        $wp_content_dir = str_replace('\\' ,'/', WP_CONTENT_DIR); 
	        $dir = str_replace($wp_content_dir, WP_CONTENT_URL, $dir, $count);
        }
        
        
        if( $count < 1 )
        {
	        // if file is in ??? folder
	        $wp_dir = str_replace('\\' ,'/', ABSPATH); 
	        $dir = str_replace($wp_dir, site_url('/'), $dir);
        }
        

        return $dir;
    }

	
	/*--------------------------------------------------------------------------------------
	*
	*	create_options
	*	- this function is called from core/field_meta_box.php to create extra options
	*	for your field
	*
	*	@params
	*	- $key (int) - the $_POST obejct key required to save the options to the field
	*	- $field (array) - the field object
	*
	*	@author Elliot Condon
	*	@since 2.2.0
	* 
	*-------------------------------------------------------------------------------------*/
	
	function create_options($key, $field)
	{
		// defaults?
		$field = array_merge($this->defaults, $field);
		
		
		// Create Field Options HTML
		?>
			<tr class="field_option field_option_<?php echo $this->name; ?>">
                <td class="label">
                    <label><?php _e('Cloudmade API-key','acf'); ?></label>
                    <p class="description"><?php _e('Register for an API-key at ','acf'); ?><a href="http://account.cloudmade.com/register" target="_blank">CloudMade</a>.</p>
                </td>
                <td>
                    <?php
                    $this->parent->create_field(array(
                        'type'  => 'text',
                        'name'  => 'fields['.$key.'][api_key]',
                        'value' => $field['api_key']
                    ));
                    ?>
                </td>
            </tr>
            
            <tr class="field_option field_option_<?php echo $this->name; ?>">
                <td class="label">
                    <label><?php _e('Zoom level','acf'); ?></label>
                    <p class="description"><?php _e('','acf'); ?></p>
                </td>
                <td>
                    <?php
                    $this->parent->create_field(array(
                        'type'  => 'number',
                        'name'  => 'fields['.$key.'][zoom_level]',
                        'value' => $field['zoom_level']
                    ));
                    ?>
                </td>
            </tr>

            <tr class="field_option field_option_<?php echo $this->name; ?>">
                <td class="label">
                    <label><?php _e('Latitude','acf'); ?></label>
                    <p class="description"><?php _e('','acf'); ?></p>
                </td>
                <td>
                    <?php
                    $this->parent->create_field(array(
                        'type'  => 'number',
                        'name'  => 'fields['.$key.'][lat]',
                        'value' => $field['lat']
                    ));
                    ?>
                </td>
            </tr>
            
            <tr class="field_option field_option_<?php echo $this->name; ?>">
                <td class="label">
                    <label><?php _e('Longitude','acf'); ?></label>
                    <p class="description"><?php _e('','acf'); ?></p>
                </td>
                <td>
                    <?php
                    $this->parent->create_field(array(
                        'type'      => 'number',
                        'name'      => 'fields['.$key.'][lng]',
                        'value'     => $field['lng']
                    ));
                    ?>
                </td>
            </tr>

            <tr class="field_option field_option_<?php echo $this->name; ?>">
                <td class="label">
                    <label><?php _e('Height','acf'); ?></label>
                    <p class="description"><?php _e('The map needs a specified height to be rendered correctly.','acf'); ?></p>
                </td>
                <td>
                    <?php
                    $this->parent->create_field(array(
                        'type'      => 'number',
                        'name'      => 'fields['.$key.'][height]',
                        'value'     => $field['height']
                    ));
                    ?>
                </td>
            </tr>
		<?php
	}
	
	
	/*--------------------------------------------------------------------------------------
	*
	*	pre_save_field
	*	- this function is called when saving your acf object. Here you can manipulate the
	*	field object and it's options before it gets saved to the database.
	*
	*	@author Elliot Condon
	*	@since 2.2.0
	* 
	*-------------------------------------------------------------------------------------*/
	
	function pre_save_field($field)
	{
		// Note: This function can be removed if not used
		
		// do stuff with field (mostly format options data)
		
		return parent::pre_save_field($field);
	}
	
	
	/*--------------------------------------------------------------------------------------
	*
	*	create_field
	*	- this function is called on edit screens to produce the html for this field
	*
	*	@author Elliot Condon
	*	@since 2.2.0
	* 
	*-------------------------------------------------------------------------------------*/
	
	function create_field($field)
	{
		// defaults
        $field = array_merge($this->defaults, $field);

        // Build an unique id based on ACF's one.
        $pattern = array('/\[/', '/\]/');
        $replace = array('_', '');
        $uid = preg_replace($pattern, $replace, $field['name']);
        $field['id'] = $uid;

        // include the javascript
        include_once("js/input.js.php");
        ?>
            <div id="leaflet_field-wrapper_<?php echo $uid; ?>">
                <input type="hidden" value='<?php echo $field['value']; ?>' id="field_<?php echo $uid; ?>" name="<?php echo $field['name']; ?>" data-zoom-level="<?php echo $field['zoom_level']; ?>" data-lat="<?php echo $field['lat']; ?>" data-lng="<?php echo $field['lng']; ?>" />
                <div class="leaflet-map" data-uid="<?php echo $uid; ?>">
                    <ul class="tools">
                        <li class="tool tool-compass icon-compass"></li>
                        <li class="tool tool-marker icon-location active"></li>
                        <li class="tool tool-tag icon-comment-alt2-fill"></li>
                        <!--<li class="tool tool-path icon-share"></li>-->
                        <li class="tool tool-remove icon-cancel-circle red"></li>
                        <!--<li class="tool tool-reset icon-reload right red"></li>-->
                    </ul>
                    <div id="map_<?php echo $uid; ?>" style="height:<?php echo $field['height']; ?>px;"></div>
                </div>
            </div>
		<?php
	}
	
	
	/*--------------------------------------------------------------------------------------
	*
	*	admin_head
	*	- this function is called in the admin_head of the edit screen where your field
	*	is created. Use this function to create css and javascript to assist your 
	*	create_field() function.
	*
	*	@author Elliot Condon
	*	@since 2.2.0
	* 
	*-------------------------------------------------------------------------------------*/
	
	function admin_head()
	{
		// Note: This function can be removed if not used
	}
	
	
	/*--------------------------------------------------------------------------------------
	*
	*	admin_print_scripts / admin_print_styles
	*	- this function is called in the admin_print_scripts / admin_print_styles where 
	*	your field is created. Use this function to register css and javascript to assist 
	*	your create_field() function.
	*
	*	@author Elliot Condon
	*	@since 3.0.0
	* 
	*-------------------------------------------------------------------------------------*/
	
	function admin_print_scripts()
	{
		// scripts
        wp_enqueue_script( 'jquery' );
        wp_register_script( 'leaflet', plugins_url( '/js/leaflet/leaflet.js', __FILE__ ), array(), '0.5.1', true );
        wp_enqueue_script( 'leaflet' );
	}
	
	function admin_print_styles()
	{
		// styles
        wp_enqueue_style( 'leaflet', plugins_url( '/js/leaflet/leaflet.css', __FILE__ ), array(), '0.5.1', 'all' );
        wp_enqueue_style( 'leaflet-ie', plugins_url( '/js/leaflet/leaflet.ie.css', __FILE__ ), array( 'leaflet' ), '0.5.1' );
        $GLOBALS['wp_styles']->add_data( 'leaflet-ie', 'conditional', 'lte IE 8' );
        wp_enqueue_style( 'icomoon', plugins_url( '/css/icomoon/style.css', __FILE__ ), array(), '1.0.0', 'all' );
        wp_enqueue_style( 'leaflet-field', plugins_url( '/css/input.css', __FILE__ ), array( 'leaflet', 'icomoon' ), '1', 'all' );
	}

	
	/*--------------------------------------------------------------------------------------
	*
	*	update_value
	*	- this function is called when saving a post object that your field is assigned to.
	*	the function will pass through the 3 parameters for you to use.
	*
	*	@params
	*	- $post_id (int) - usefull if you need to save extra data or manipulate the current
	*	post object
	*	- $field (array) - usefull if you need to manipulate the $value based on a field option
	*	- $value (mixed) - the new value of your field.
	*
	*	@author Elliot Condon
	*	@since 2.2.0
	* 
	*-------------------------------------------------------------------------------------*/
	
	function update_value($post_id, $field, $value)
	{
		// Note: This function can be removed if not used
		
		// do stuff with value
		
		// save value
		parent::update_value($post_id, $field, $value);
	}
	
	
	
	
	
	/*--------------------------------------------------------------------------------------
	*
	*	get_value
	*	- called from the edit page to get the value of your field. This function is useful
	*	if your field needs to collect extra data for your create_field() function.
	*
	*	@params
	*	- $post_id (int) - the post ID which your value is attached to
	*	- $field (array) - the field object.
	*
	*	@author Elliot Condon
	*	@since 2.2.0
	* 
	*-------------------------------------------------------------------------------------*/
	
	function get_value($post_id, $field)
	{
		// Note: This function can be removed if not used
		
		// get value
		$value = parent::get_value($post_id, $field);
		
		// format value
		
		// return value
		return $value;		
	}
	
	
	/*--------------------------------------------------------------------------------------
	*
	*	get_value_for_api
	*	- called from your template file when using the API functions (get_field, etc). 
	*	This function is useful if your field needs to format the returned value
	*
	*	@params
	*	- $post_id (int) - the post ID which your value is attached to
	*	- $field (array) - the field object.
	*
	*	@author Elliot Condon
	*	@since 3.0.0
	* 
	*-------------------------------------------------------------------------------------*/
	
	function get_value_for_api($post_id, $field)
	{
		// Note: This function can be removed if not used
		
		// get value
		$value = $this->get_value($post_id, $field);
		
		// format value
		$value = json_decode($value);

		// return value
		return $value;

	}
	
}

?>