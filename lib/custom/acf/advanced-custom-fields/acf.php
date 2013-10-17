<?php
/*
Plugin Name: Advanced Custom Fields
Plugin URI: http://www.advancedcustomfields.com/
Description: Fully customise WordPress edit screens with powerful fields. Boasting a professional interface and a powerfull API, it’s a must have for any web developer working with WordPress. Field types include: Wysiwyg, text, textarea, image, file, select, checkbox, page link, post object, date picker, color picker, repeater, flexible content, gallery and more!
Version: 4.2.2
Author: Elliot Condon
Author URI: http://www.elliotcondon.com/
License: GPL
Copyright: Elliot Condon
*/


// lite mode
if( !defined('ACF_LITE') )
{
	define( 'ACF_LITE', false );
}


// API
include_once('core/api.php');


// controllers
include_once('core/controllers/field_groups.php');
include_once('core/controllers/field_group.php');
include_once('core/controllers/input.php');
include_once('core/controllers/location.php');

if( is_admin() )
{
	if( !ACF_LITE )
	{
		include_once('core/controllers/export.php');
		include_once('core/controllers/addons.php');
		include_once('core/controllers/third_party.php');
		include_once('core/controllers/upgrade.php');
	}
	
	include_once('core/controllers/revisions.php');
	include_once('core/controllers/everything_fields.php');	
}


class Acf
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
		// helpers
		add_filter('acf/helpers/get_path', array($this, 'helpers_get_path'), 1, 1);
		add_filter('acf/helpers/get_dir', array($this, 'helpers_get_dir'), 1, 1);
		
		
		// vars
		$this->settings = array(
			'path' => apply_filters('acf/helpers/get_path', __FILE__),
			'dir' => apply_filters('acf/helpers/get_dir', __FILE__),
			'hook' => basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ),
			'version' => '4.2.2',
			'upgrade_version' => '3.4.1',
		);
		
		
		// set text domain
		load_textdomain('acf', $this->settings['path'] . 'lang/acf-' . get_locale() . '.mo');
		
		
		// actions
		add_action('init', array($this, 'init'), 1);
		add_action('acf/save_post', array($this, 'save_post'), 10);
		
		add_action('acf/pre_save_post', array($this, 'save_post_lock'), 0);
		add_action('acf/pre_save_post', array($this, 'save_post_unlock'), 999);
		add_action('acf/save_post', array($this, 'save_post_lock'), 0);
		add_action('acf/save_post', array($this, 'save_post_unlock'), 999);
		
		
		// filters
		add_filter('acf/get_info', array($this, 'get_info'), 1, 1);
		add_filter('acf/parse_types', array($this, 'parse_types'), 1, 1);
		add_filter('acf/get_post_types', array($this, 'get_post_types'), 1, 3);
		add_filter('acf/get_taxonomies_for_select', array($this, 'get_taxonomies_for_select'), 1, 2);
		add_filter('acf/get_image_sizes', array($this, 'get_image_sizes'), 1, 1);
		add_action('acf/create_fields', array($this, 'create_fields'), 1, 2);
		add_action('acf/get_post_id', array($this, 'get_post_id'), 1, 1);
		
		
		// admin only
		if( is_admin() && !ACF_LITE )
		{
			add_action('admin_menu', array($this,'admin_menu'));
			add_action('admin_head', array($this,'admin_head'));
			add_filter('post_updated_messages', array($this, 'post_updated_messages'));
		}
		
		
		return true;
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
        $dir = str_replace($wp_plugin_dir, plugins_url(), $dir, $count);
        
        
        if( $count < 1 )
        {
	        // if file is in wp-content folder
	        $wp_content_dir = str_replace('\\' ,'/', WP_CONTENT_DIR); 
	        $dir = str_replace($wp_content_dir, content_url(), $dir, $count);
        }
        
        
        if( $count < 1 )
        {
	        // if file is in ??? folder
	        $wp_dir = str_replace('\\' ,'/', ABSPATH); 
	        $dir = str_replace($wp_dir, site_url('/'), $dir);
        }
        

        return $dir;
    }
	
	
	/*
	*  acf/get_post_id
	*
	*  A helper function to filter the post_id variable.
	*
	*  @type	filter
	*  @date	27/05/13
	*
	*  @param	{mixed}	$post_id
	*  @return	{mixed}	$post_id
	*/
	
	function get_post_id( $post_id )
	{
		// set post_id to global
		if( !$post_id )
		{
			global $post;
			
			if( $post )
			{
				$post_id = intval( $post->ID );
			}
		}
		
		
		// allow for option == options
		if( $post_id == "option" )
		{
			$post_id = "options";
		}
		
		
		/*
		*  Override for preview
		*  
		*  If the $_GET['preview_id'] is set, then the user wants to see the preview data.
		*  There is also the case of previewing a page with post_id = 1, but using get_field
		*  to load data from another post_id.
		*  In this case, we need to make sure that the autosave revision is actually related
		*  to the $post_id variable. If they match, then the autosave data will be used, otherwise, 
		*  the user wants to load data from a completely different post_id
		*/
		
		if( isset($_GET['preview_id']) )
		{
			$autosave = wp_get_post_autosave( $_GET['preview_id'] );
			if( $autosave->post_parent == $post_id )
			{
				$post_id = intval( $autosave->ID );
			}
		}
		
		
		// return
		return $post_id;
	}
	
	
	/*
	*  get_info
	*
	*  @description: helper to get variable from settings array
	*  @since: 3.6
	*  @created: 24/01/13
	*/
	
	function get_info( $info )
	{
		// vars
		$return = false;
		
		
		// specific
		if( isset($this->settings[ $info ]) )
		{
			$return = $this->settings[ $info ];
		}
		
		
		// all
		if( $info == 'all' )
		{
			$return = $this->settings;
		}
		
		
		// return
		return $return;
	}
	
	
	/*
	*  parse_types
	*
	*  @description: helper function to set the 'types' of variables
	*  @since: 2.0.4
	*  @created: 9/12/12
	*/
	
	function parse_types( $value )
	{
		// vars
		$restricted = array(
			'label',
			'name',
			'value',
			'instructions'
		);
		
		
		// is value another array?
		if( is_array($value) )
		{
			foreach( $value as $k => $v )
			{
				// bail early for restricted pieces
				if( in_array($k, $restricted, true) )
				{
					continue;
				}
				
				
				// filter piece
				$value[ $k ] = apply_filters( 'acf/parse_types', $v );
			}	
		}
		else
		{
			// string
			if( is_string($value) )
			{
				$value = trim( $value );
			}
			
			
			// numbers
			if( is_numeric($value) )
			{
				// float / int
				if( strpos($value,'.') !== false )
				{
					// leave decimal places alone
					//$value = floatval( $value );
				}
				else
				{
					$value = intval( $value );
				}
			}
		}
		
		
		// return
		return $value;
	}
	
	
	/*
	*  Init
	*
	*  @description: 
	*  @since 1.0.0
	*  @created: 23/06/12
	*/
	
	function init()
	{
		
		// Create ACF post type
		$labels = array(
		    'name' => __( 'Field&nbsp;Groups', 'acf' ),
			'singular_name' => __( 'Advanced Custom Fields', 'acf' ),
		    'add_new' => __( 'Add New' , 'acf' ),
		    'add_new_item' => __( 'Add New Field Group' , 'acf' ),
		    'edit_item' =>  __( 'Edit Field Group' , 'acf' ),
		    'new_item' => __( 'New Field Group' , 'acf' ),
		    'view_item' => __('View Field Group', 'acf'),
		    'search_items' => __('Search Field Groups', 'acf'),
		    'not_found' =>  __('No Field Groups found', 'acf'),
		    'not_found_in_trash' => __('No Field Groups found in Trash', 'acf'), 
		);
		
		register_post_type('acf', array(
			'labels' => $labels,
			'public' => false,
			'show_ui' => true,
			'_builtin' =>  false,
			'capability_type' => 'page',
			'hierarchical' => true,
			'rewrite' => false,
			'query_var' => "acf",
			'supports' => array(
				'title',
			),
			'show_in_menu'	=> false,
		));
		
		
		// register acf scripts
		$scripts = array();
		$scripts[] = array(
			'handle' => 'acf-field-group',
			'src' => $this->settings['dir'] . 'js/field-group.min.js',
			'deps' => array('jquery')
		);
		$scripts[] = array(
			'handle' => 'acf-input',
			'src' => $this->settings['dir'] . 'js/input.min.js',
			'deps' => array('jquery')
		);
		$scripts[] = array(
			'handle' => 'acf-datepicker',
			'src' => $this->settings['dir'] . 'core/fields/date_picker/jquery.ui.datepicker.js',
			'deps' => array('jquery', 'acf-input')
		);
		
		
		foreach( $scripts as $script )
		{
			wp_register_script( $script['handle'], $script['src'], $script['deps'], $this->settings['version'] );
		}
		
		
		// register acf styles
		$styles = array(
			'acf' => $this->settings['dir'] . 'css/acf.css',
			'acf-field-group' => $this->settings['dir'] . 'css/field-group.css',
			'acf-global' => $this->settings['dir'] . 'css/global.css',
			'acf-input' => $this->settings['dir'] . 'css/input.css',
			'acf-datepicker' => $this->settings['dir'] . 'core/fields/date_picker/style.date_picker.css',
		);
		
		foreach( $styles as $k => $v )
		{
			wp_register_style( $k, $v, false, $this->settings['version'] ); 
		}
		
		
		// register fields
		include_once('core/fields/_functions.php');
		include_once('core/fields/_base.php');
		
		include_once('core/fields/text.php');
		include_once('core/fields/textarea.php');
		include_once('core/fields/number.php');
		include_once('core/fields/email.php');
		include_once('core/fields/password.php');
		
		include_once('core/fields/wysiwyg.php');
		include_once('core/fields/image.php');
		include_once('core/fields/file.php');
		
		include_once('core/fields/select.php');
		include_once('core/fields/checkbox.php');
		include_once('core/fields/radio.php');
		include_once('core/fields/true_false.php');
		
		include_once('core/fields/page_link.php');
		include_once('core/fields/post_object.php');
		include_once('core/fields/relationship.php');
		include_once('core/fields/taxonomy.php');
		include_once('core/fields/user.php');
		
		include_once('core/fields/date_picker/date_picker.php');
		include_once('core/fields/color_picker.php');
		
		include_once('core/fields/message.php');
		include_once('core/fields/tab.php');
		
		
		// register 3rd party fields
		do_action('acf/register_fields');
		
		
	}
	
	
	/*
	*  admin_menu
	*
	*  @description: 
	*  @since 1.0.0
	*  @created: 23/06/12
	*/
	
	function admin_menu()
	{
		add_utility_page(__("Custom Fields",'acf'), __("Custom Fields",'acf'), 'manage_options', 'edit.php?post_type=acf');
	}
	
	
	/*
	*  post_updated_messages
	*
	*  @description: messages for saving a field group
	*  @since 1.0.0
	*  @created: 23/06/12
	*/

	function post_updated_messages( $messages )
	{
		global $post, $post_ID;
	
		$messages['acf'] = array(
			0 => '', // Unused. Messages start at index 1.
			1 => __('Field group updated.', 'acf'),
			2 => __('Custom field updated.', 'acf'),
			3 => __('Custom field deleted.', 'acf'),
			4 => __('Field group updated.', 'acf'),
			/* translators: %s: date and time of the revision */
			5 => isset($_GET['revision']) ? sprintf( __('Field group restored to revision from %s', 'acf'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6 => __('Field group published.', 'acf'),
			7 => __('Field group saved.', 'acf'),
			8 => __('Field group submitted.', 'acf'),
			9 => __('Field group scheduled for.', 'acf'),
			10 => __('Field group draft updated.', 'acf'),
		);
	
		return $messages;
	}	
	
	
	/*--------------------------------------------------------------------------------------
	*
	*	admin_head
	*
	*	@author Elliot Condon
	*	@since 1.0.0
	* 
	*-------------------------------------------------------------------------------------*/
	
	function admin_head()
	{
		?>
<style type="text/css"> 
	#adminmenu #toplevel_page_edit-post_type-acf a[href="edit.php?post_type=acf&page=acf-upgrade"]{ display:none; }
	#adminmenu #toplevel_page_edit-post_type-acf .wp-menu-image { background-position: 1px -33px; }
	#adminmenu #toplevel_page_edit-post_type-acf:hover .wp-menu-image,
	#adminmenu #toplevel_page_edit-post_type-acf.wp-menu-open .wp-menu-image { background-position: 1px -1px; }
</style>
		<?php
	}
	
	
	/*
	*  get_taxonomies_for_select
	*
	*  @description: 
	*  @since: 3.6
	*  @created: 27/01/13
	*/
	
	function get_taxonomies_for_select( $choices, $simple_value = false )
	{	
		// vars
		$post_types = get_post_types();
		
		
		if($post_types)
		{
			foreach($post_types as $post_type)
			{
				$post_type_object = get_post_type_object($post_type);
				$taxonomies = get_object_taxonomies($post_type);
				if($taxonomies)
				{
					foreach($taxonomies as $taxonomy)
					{
						if(!is_taxonomy_hierarchical($taxonomy)) continue;
						$terms = get_terms($taxonomy, array('hide_empty' => false));
						if($terms)
						{
							foreach($terms as $term)
							{
								$value = $taxonomy . ':' . $term->term_id;
								
								if( $simple_value )
								{
									$value = $term->term_id;
								}
								
								$choices[$post_type_object->label . ': ' . $taxonomy][$value] = $term->name; 
							}
						}
					}
				}
			}
		}
		
		return $choices;
	}
	
	
	/*
	*  get_post_types
	*
	*  @description: 
	*  @since: 3.5.5
	*  @created: 16/12/12
	*/
	
	function get_post_types( $post_types, $exclude = array(), $include = array() )
	{
		// get all custom post types
		$post_types = array_merge($post_types, get_post_types());
		
		
		// core include / exclude
		$acf_includes = array_merge( array(), $include );
		$acf_excludes = array_merge( array( 'acf', 'revision', 'nav_menu_item' ), $exclude );
	 
		
		// include
		foreach( $acf_includes as $p )
		{					
			if( post_type_exists($p) )
			{							
				$post_types[ $p ] = $p;
			}
		}
		
		
		// exclude
		foreach( $acf_excludes as $p )
		{
			unset( $post_types[ $p ] );
		}
		
		
		return $post_types;
		
	}
	
	
	/*
	*  get_image_sizes
	*
	*  @description: returns an array holding all the image sizes
	*  @since 3.2.8
	*  @created: 6/07/12
	*/
	
	function get_image_sizes( $sizes )
	{
		// find all sizes
		$all_sizes = get_intermediate_image_sizes();
		
		
		// define default sizes
		$sizes = array_merge($sizes, array(
			'thumbnail'	=>	__("Thumbnail",'acf'),
			'medium'	=>	__("Medium",'acf'),
			'large'		=>	__("Large",'acf'),
			'full'		=>	__("Full",'acf')
		));
		
		
		// add extra registered sizes
		foreach( $all_sizes as $size )
		{
			if( !isset($sizes[ $size ]) )
			{
				$sizes[ $size ] = ucwords( str_replace('-', ' ', $size) );
			}
		}
		
		
		// return array
		return $sizes;
	}
	
	
	/*
	*  render_fields_for_input
	*
	*  @description: 
	*  @since 3.1.6
	*  @created: 23/06/12
	*/
	
	function create_fields( $fields, $post_id )
	{
		if( is_array($fields) ){ foreach( $fields as $field ){
			
			// if they didn't select a type, skip this field
			if( !$field || !$field['type'] || $field['type'] == 'null' )
			{
				continue;
			}
			
			
			// set value
			if( !isset($field['value']) )
			{
				$field['value'] = apply_filters('acf/load_value', false, $post_id, $field);
				$field['value'] = apply_filters('acf/format_value', $field['value'], $post_id, $field);
			}
			
			
			// required
			$required_class = "";
			$required_label = "";
			
			if( $field['required'] )
			{
				$required_class = ' required';
				$required_label = ' <span class="required">*</span>';
			}
			
			
			echo '<div id="acf-' . $field['name'] . '" class="field field_type-' . $field['type'] . ' field_key-' . $field['key'] . $required_class . '" data-field_name="' . $field['name'] . '" data-field_key="' . $field['key'] . '" data-field_type="' . $field['type'] . '">';

				echo '<p class="label">';
					echo '<label for="' . $field['id'] . '">' . $field['label'] . $required_label . '</label>';
					echo $field['instructions'];
				echo '</p>';
				
				$field['name'] = 'fields[' . $field['key'] . ']';
				do_action('acf/create_field', $field);
			
			echo '</div>';
			
		}}
				
	}
	
	
	/*
	*  save_post_lock
	*
	*  This action sets a global variable which locks the ACF save functions to this ID.
	*  This prevents an inifinite loop if a user was to hook into the save and create a new post
	*
	*  @type	function
	*  @date	16/07/13
	*
	*  @param	{int}	$post_id
	*  @return	{int}	$post_id
	*/
	
	function save_post_lock( $post_id )
	{
		$GLOBALS['acf_save_lock'] = $post_id;
		
		return $post_id;
	}
	
	
	/*
	*  save_post_unlock
	*
	*  This action sets a global variable which unlocks the ACF save functions to this ID.
	*  This prevents an inifinite loop if a user was to hook into the save and create a new post
	*
	*  @type	function
	*  @date	16/07/13
	*
	*  @param	{int}	$post_id
	*  @return	{int}	$post_id
	*/
	
	function save_post_unlock( $post_id )
	{
		$GLOBALS['acf_save_lock'] = false;
		
		return $post_id;
	}
	
	
	/*
	*  save_post
	*
	*  @description: 
	*  @since: 3.6
	*  @created: 28/01/13
	*/
	
	function save_post( $post_id )
	{
		
		// load from post
		if( !isset($_POST['fields']) )
		{
			return $post_id;
		}
		

		// loop through and save
		if( !empty($_POST['fields']) )
		{
			// loop through and save $_POST data
			foreach( $_POST['fields'] as $k => $v )
			{
				// get field
				$f = apply_filters('acf/load_field', false, $k );
				
				// update field
				do_action('acf/update_value', $v, $post_id, $f );
				
			}
			// foreach($fields as $key => $value)
		}
		// if($fields)
		
		
		return $post_id;
	}

	
}

$acf = new Acf();

?>
