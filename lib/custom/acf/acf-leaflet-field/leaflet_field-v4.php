<?php

class acf_field_leaflet_field extends acf_field
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

	/*
	*  __construct
	*
	*  Set name / label needed for actions / filters
	*
	*  @since	3.6
	*  @date	23/01/13
	*/
	
	function __construct()
	{
		// vars
		$this->name = 'leaflet_field';
		$this->label = __( 'Leaflet Field' );
		$this->category = __( 'Content','acf' ); // Basic, Content, Choice, etc
		$this->defaults = array(
			'lat'           => '55.606',
            'lng'           => '13.002',
            'zoom_level'    => 13,
            'height'        => 350,
            'api_key'       => '',
            'map_provider'  => 'openstreetmap',
		);
		
		
		// do not delete!
    	parent::__construct();
    	
    	
    	// settings
		$this->settings = array(
			'path' => apply_filters( 'acf/helpers/get_path', __FILE__ ),
			'dir' => apply_filters( 'acf/helpers/get_dir', __FILE__ ),
			'version' => '1.0.0'
		);

        add_action( 'acf/field_group/admin_head', array( $this, 'conditional_options' ) );
	}
	
	
	/*
	*  create_options()
	*
	*  Create extra options for your field. This is rendered when editing a field.
	*  The value of $field['name'] can be used (like below) to save extra data to the $field
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field	- an array holding all the field's data
	*/
	
	function create_options( $field )
	{
		// defaults
        $field = array_merge($this->defaults, $field);

        // key is needed in the field names to correctly save the data
        $key = $field['name'];
        
        
        // Create Field Options HTML
        ?>
            <tr class="leaflet_field_map_provider_field field_option field_option_<?php echo $this->name; ?>">
                <td class="label">
                    <label><?php _e('Map provider','acf-leaflet-field'); ?></label>
                    <p class="description"><?php _e('Select map provider','acf-leaflet-field'); ?></p>
                </td>
                <td>
                    <?php
                    do_action('acf/create_field', array(
                        'type'      => 'radio',
                        'name'      => 'fields['.$key.'][map_provider]',
                        'value'     => $field['map_provider'],
                        'layout'    => 'horizontal',
                        'choices'   => array(
                            'openstreetmap' => acf_field_leaflet_field::$map_providers['openstreetmap']['nicename'],
                            'cloudmade'     => acf_field_leaflet_field::$map_providers['cloudmade']['nicename']
                        )
                    ));
                    ?>
                </td>
            </tr>

            <tr class="leaflet_field_api_key_field field_option field_option_<?php echo $this->name; ?>">
                <td class="label">
                    <label><?php _e('Cloudmade API-key','acf-leaflet-field'); ?></label>
                    <p class="description"><?php _e('Register for an API-key at ','acf-leaflet-field'); ?><a href="http://account.cloudmade.com/register" target="_blank">CloudMade</a>.</p>
                </td>
                <td>
                    <?php
                    do_action('acf/create_field', array(
                        'type'  => 'text',
                        'name'  => 'fields['.$key.'][api_key]',
                        'value' => $field['api_key']
                    ));
                    ?>
                </td>
            </tr>
            
            <tr class="field_option field_option_<?php echo $this->name; ?>">
                <td class="label">
                    <label><?php _e('Zoom level','acf-leaflet-field'); ?></label>
                    <p class="description"><?php _e('','acf-leaflet-field'); ?></p>
                </td>
                <td>
                    <?php
                    do_action('acf/create_field', array(
                        'type'  => 'number',
                        'name'  => 'fields['.$key.'][zoom_level]',
                        'value' => $field['zoom_level']
                    ));
                    ?>
                </td>
            </tr>

            <tr class="field_option field_option_<?php echo $this->name; ?>">
                <td class="label">
                    <label><?php _e('Latitude','acf-leaflet-field'); ?></label>
                    <p class="description"><?php _e('','acf-leaflet-field'); ?></p>
                </td>
                <td>
                    <?php
                    do_action('acf/create_field', array(
                        'type'  => 'number',
                        'name'  => 'fields['.$key.'][lat]',
                        'value' => $field['lat']
                    ));
                    ?>
                </td>
            </tr>
            
            <tr class="field_option field_option_<?php echo $this->name; ?>">
                <td class="label">
                    <label><?php _e('Longitude','acf-leaflet-field'); ?></label>
                    <p class="description"><?php _e('','acf-leaflet-field'); ?></p>
                </td>
                <td>
                    <?php
                    do_action('acf/create_field', array(
                        'type'      => 'number',
                        'name'      => 'fields['.$key.'][lng]',
                        'value'     => $field['lng']
                    ));
                    ?>
                </td>
            </tr>

            <tr class="field_option field_option_<?php echo $this->name; ?>">
                <td class="label">
                    <label><?php _e('Height','acf-leaflet-field'); ?></label>
                    <p class="description"><?php _e('The map needs a specified height to be rendered correctly.','acf-leaflet-field'); ?></p>
                </td>
                <td>
                    <?php
                    do_action('acf/create_field', array(
                        'type'      => 'number',
                        'name'      => 'fields['.$key.'][height]',
                        'value'     => $field['height']
                    ));
                    ?>
                </td>
            </tr>
		<?php
		
	}

    /*
    *  ACF { Conditional Logic
    *
    *  @description: hide / show fields based on a "trigger" field
    *  @created: 17/07/12
    */

    function conditional_options()
    {
        ?>
        <style type="text/css">
            .leaflet_field_api_key_field {
                display: none;
            }
        </style>
        <script type="text/javascript">
        (function($){
            /*
            *  Map provider change
            */
            
            $(document).on('change', '.leaflet_field_map_provider_field input' , function(){
                // vars
                var value = $(this).val();
                
                <?php
                    // iterate map providers and check if they require an api-key
                    $conditions = '';
                    foreach( acf_field_leaflet_field::$map_providers as $key => $map_provider )
                    {
                        $conditions .= 'if( value == "' . $key . '" ) { $(this).parents(".leaflet_field_map_provider_field").siblings(".leaflet_field_api_key_field").';

                        if( $map_provider['requires_key'] )
                        {
                            $conditions .= 'show';
                        }
                        else {
                            $conditions .= 'hide';   
                        }

                        $conditions .= '(); }';
                    }

                    echo $conditions;
                ?>
            });

        })(jQuery);
        </script>
        <?php
    }
	
	/*
	*  create_field()
	*
	*  Create the HTML interface for your field
	*
	*  @param	$field - an array holding all the field's data
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/
	
	function create_field( $field )
	{
		// defaults
        $field = array_merge($this->defaults, $field);

        // Build an unique id based on ACF's one.
        $pattern = array( '/\[/', '/\]/' );
        $replace = array( '_', '' );
        $uid = preg_replace($pattern, $replace, $field['name']);

        $field['id'] = $uid;

        // resolve tile-layer and attribution
        $tile_layer = str_replace( '{api_key}', $field['api_key'], acf_field_leaflet_field::$map_providers[$field['map_provider']]['url'] );
        $attribution = acf_field_leaflet_field::$map_providers[$field['map_provider']]['attribution'];

        // include the javascript
        include_once("js/input.js.php");

        // render the field container, 
        ?>
            <div id="leaflet_field-wrapper_<?php echo $uid; ?>">
                <input type="hidden" value='<?php echo $field['value']; ?>' id="field_<?php echo $uid; ?>" name="<?php echo $field['name']; ?>" data-zoom-level="<?php echo $field['zoom_level']; ?>" data-lat="<?php echo $field['lat']; ?>" data-lng="<?php echo $field['lng']; ?>" />
                <div class="leaflet-map" data-uid="<?php echo $uid; ?>" data-tile-layer="<?php echo $tile_layer; ?>" data-attribution='<?php echo $attribution; ?>'>
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
	
	
	/*
	*  input_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
	*  Use this action to add css + javascript to assist your create_field() action.
	*
	*  $info	http://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/

	function input_admin_enqueue_scripts()
	{
		// styles
        wp_enqueue_style( 'leaflet', plugins_url( '/js/leaflet/leaflet.css', __FILE__ ), array(), '0.5.1', 'all' );
        wp_enqueue_style( 'leaflet-ie', plugins_url( '/js/leaflet/leaflet.ie.css', __FILE__ ), array( 'leaflet' ), '0.5.1' );
        $GLOBALS['wp_styles']->add_data( 'leaflet-ie', 'conditional', 'lte IE 8' );
        wp_enqueue_style( 'icomoon', plugins_url( '/css/icomoon/style.css', __FILE__ ), array(), '1.0.0', 'all' );
        wp_enqueue_style( 'leaflet-field', plugins_url( '/css/input.css', __FILE__ ), array( 'leaflet', 'icomoon' ), '1', 'all' );

        // scripts
        wp_enqueue_script( 'jquery' );
        wp_register_script( 'leaflet', plugins_url( '/js/leaflet/leaflet.js', __FILE__ ), array(), '0.5.1', true );
        wp_enqueue_script( 'leaflet' );
	}
	
	
	/*
	*  input_admin_head()
	*
	*  This action is called in the admin_head action on the edit screen where your field is created.
	*  Use this action to add css and javascript to assist your create_field() action.
	*
	*  @info	http://codex.wordpress.org/Plugin_API/Action_Reference/admin_head
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/

	function input_admin_head()
	{
		// Note: This function can be removed if not used
	}
	
	
	/*
	*  field_group_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is edited.
	*  Use this action to add css + javascript to assist your create_field_options() action.
	*
	*  $info	http://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/

	function field_group_admin_enqueue_scripts()
	{
		// Note: This function can be removed if not used
	}

	
	/*
	*  field_group_admin_head()
	*
	*  This action is called in the admin_head action on the edit screen where your field is edited.
	*  Use this action to add css and javascript to assist your create_field_options() action.
	*
	*  @info	http://codex.wordpress.org/Plugin_API/Action_Reference/admin_head
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/

	function field_group_admin_head()
	{
		// Note: This function can be removed if not used
	}


	/*
	*  load_value()
	*
	*  This filter is appied to the $value after it is loaded from the db
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value - the value found in the database
	*  @param	$post_id - the $post_id from which the value was loaded from
	*  @param	$field - the field array holding all the field options
	*
	*  @return	$value - the value to be saved in te database
	*/
	
	function load_value( $value, $post_id, $field )
	{
		// Note: This function can be removed if not used
		return $value;
	}
	
	
	/*
	*  update_value()
	*
	*  This filter is appied to the $value before it is updated in the db
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value - the value which will be saved in the database
	*  @param	$post_id - the $post_id of which the value will be saved
	*  @param	$field - the field array holding all the field options
	*
	*  @return	$value - the modified value
	*/
	
	function update_value( $value, $post_id, $field )
	{
		// Note: This function can be removed if not used
		return $value;
	}
	
	
	/*
	*  format_value()
	*
	*  This filter is appied to the $value after it is loaded from the db and before it is passed to the create_field action
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value	- the value which was loaded from the database
	*  @param	$post_id - the $post_id from which the value was loaded
	*  @param	$field	- the field array holding all the field options
	*
	*  @return	$value	- the modified value
	*/
	
	function format_value( $value, $post_id, $field )
	{
		// defaults?
		/*
		$field = array_merge($this->defaults, $field);
		*/
		
		// perhaps use $field['preview_size'] to alter the $value?
		
		
		// Note: This function can be removed if not used
		return $value;
	}
	
	
	/*
	*  format_value_for_api()
	*
	*  This filter is appied to the $value after it is loaded from the db and before it is passed back to the api functions such as the_field
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value	- the value which was loaded from the database
	*  @param	$post_id - the $post_id from which the value was loaded
	*  @param	$field	- the field array holding all the field options
	*
	*  @return	$value	- the modified value
	*/
	
	function format_value_for_api( $value, $post_id, $field )
	{
		// defaults?
		$field = array_merge( $this->defaults, $field );
		
        // format value
		$value = json_decode( $value );
		
		// Note: This function can be removed if not used
		return $value;
	}
	
	
	/*
	*  load_field()
	*
	*  This filter is appied to the $field after it is loaded from the database
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field - the field array holding all the field options
	*
	*  @return	$field - the field array holding all the field options
	*/
	
	function load_field( $field )
	{
		// Note: This function can be removed if not used
		return $field;
	}
	
	
	/*
	*  update_field()
	*
	*  This filter is appied to the $field before it is saved to the database
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field - the field array holding all the field options
	*  @param	$post_id - the field group ID (post_type = acf)
	*
	*  @return	$field - the modified field
	*/

	function update_field( $field, $post_id )
	{
		// Note: This function can be removed if not used
		return $field;
	}

	
}


// create field
new acf_field_leaflet_field();

?>