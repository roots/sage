<?php
/*
Plugin Name: Advanced Custom Fields: Options Page
Plugin URI: http://www.advancedcustomfields.com/
Description: This premium Add-on creates a static menu item for the Advanced Custom Fields plugin
Version: 1.2.0
Author: Elliot Condon
Author URI: http://www.elliotcondon.com/
License: GPL
Copyright: Elliot Condon
*/

class acf_options_page_plugin
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
			'title' 		=> __('Options','acf'),
			'menu'			=> __('Options','acf'),
			'slug' 			=> 'acf-options',
			'capability'	=> 'edit_posts',
			'pages' 		=> array(),
		);
		
		
		// set text domain
		//load_plugin_textdomain('acf', false, basename(dirname(__FILE__)) . '/lang' );
		load_textdomain('acf', dirname(__FILE__) . '/lang/acf-options-page-' . get_locale() . '.mo');
		
		
		// actions
		add_action('init', array($this,'init'), 1 );
		add_action('admin_menu', array($this,'admin_menu'), 11, 0);
		
		
		// filters
		add_filter('acf/location/rule_types', array($this,'acf_location_rules_types'));
		add_filter('acf/location/rule_values/options_page', array($this,'acf_location_rules_values_options_page'));
	}
	
	
	/*
	*  init
	*
	*  Allow the user to customize the settings array and also format all added sub pages
	*
	*  @type	action
	*  @date	12/02/13
	*
	*  @param	N/A
	*  @return	N/A
	*/
	
	function init()
	{
		// filters
		$this->settings = apply_filters('acf/options_page/settings', $this->settings);
		
		
		// extra settings (not editable by the user)
		$this->settings['show_parent'] = true;
		
				
		// format the sub pages
		if( !empty($this->settings['pages']) )
		{
			// defaults
			$defaults = array(
				'title'			=>	'',
				'menu'			=>	'',
				'slug'			=>	'',
				'parent'		=>	'',
				'capability'	=>	''
			);
			
			
			// vars
			$slug_changed = false;
			
			foreach( $this->settings['pages'] as $k => $page )
			{
			
				// standardize
				if( is_string($page) )
				{
					$title = $page;
					$page = $defaults;
					$page['title'] = $title;
				}
				elseif( is_array($page) )
				{
					$page = array_merge($defaults, $page);
				}
				else
				{
					// error
					unset( $this->settings['pages'][ $k ] );
					continue;
				}
				
				
				// menu
				if( ! $page['menu'] )
				{
					$page['menu'] = $page['title'];
				}
				
				
				// slug
				if( ! $page['slug'] )
				{
					$page['slug'] = 'acf-options-' . sanitize_title( $page['title'] );
				}
				

				// parent
				if( ! $page['parent'] )
				{
					$page['parent'] = $this->settings['slug'];
				}
				
				
				// update parent?
				if( $page['parent'] == $this->settings['slug'] )
				{
					// this sub page DOES sit under the default options page menu item.
					// we will use this sub page slug as the parent slug (make the parent redirect to this sub page).
					if( ! $slug_changed )
					{
						$slug_changed = true;
						
						$this->settings['slug'] = $page['slug'];
						
						// solidate the parent menu
						$this->settings['menu'] = $this->settings['title'];
						
						// update the parent title
						$this->settings['title'] = $page['title'];
						
						$page['parent'] = $page['slug'];
					}
				}
				else
				{
					// this sub page DOES NOT sit under the default options page menu item
				}
				
				
				// parent
				if( ! $page['capability'] )
				{
					$page['capability'] = $this->settings['capability'];
				}
				
				
				// update
				$this->settings['pages'][ $k ] = $page;
				
			}
			
			
			// update parent slug
			if( !$slug_changed )
			{
				// no sub pages sit under the default options page menu item
				$this->settings['show_parent'] = false;
			}
			
			
		}

	}
		
	
	/*
	*  acf_location_rules_types
	*
	*  this function will add "Options Page" to the ACF location rules
	*
	*  @type	function
	*  @date	2/02/13
	*
	*  @param	{array}	$choices
	*  @return	{array}	$choices
	*/
	
	function acf_location_rules_types( $choices )
	{
	    $choices[ __("Options Page",'acf') ]['options_page'] = __("Options Page",'acf');
	 
	    return $choices;
	}
	
	
	/*
	*  acf_location_rules_values_options_page
	*
	*  this function will populate the available pages in the ACF location rules
	*
	*  @type	function
	*  @date	2/02/13
	*
	*  @param	{array}	$choices
	*  @return	{array}	$choices
	*/
	
	function acf_location_rules_values_options_page( $choices )
	{
		// default
		$choices = array();
		
		
		// populate choices
		if( !empty( $this->settings['pages'] ) )
		{
			foreach( $this->settings['pages'] as $page )
			{
				$choices[ $page['slug'] ] = $page['title'];
			}
		}
		else
		{
			$choices[ $this->settings['slug'] ] = $this->settings['title'];
		}
		
		
	    return $choices;
	}

	
	/*
	*  admin_menu
	*
	*  @description: 
	*  @since: 2.0.4
	*  @created: 5/12/12
	*/
	
	function admin_menu() 
	{
		// parent
		if( $this->settings['show_parent'] )
		{
			// menu
			if( ! $this->settings['menu'] )
			{
				$this->settings['menu'] = $this->settings['title'];
			}
			
			
			$parent_page = add_menu_page( $this->settings['title'], $this->settings['menu'], $this->settings['capability'], $this->settings['slug'], array($this, 'html'));
			
			// actions
			add_action('load-' . $parent_page, array($this,'admin_load'));
		}
		
		
		// sub pages
		if( !empty( $this->settings['pages'] ) )
		{
			foreach( $this->settings['pages'] as $page )
			{
				$child_page = add_submenu_page( $page['parent'], $page['menu'], $page['title'], $page['capability'], $page['slug'], array($this, 'html'));
			
				
				// actions
				add_action('load-' . $child_page, array($this,'admin_load'));
			}
		}

	}
	
	
	/*
	*  load
	*
	*  @description: 
	*  @since: 3.6
	*  @created: 2/02/13
	*/
	
	function admin_load()
	{
		add_action('admin_enqueue_scripts', array($this,'admin_enqueue_scripts'));
		add_action('admin_head', array($this,'admin_head'));
		add_action('admin_footer', array($this,'admin_footer'));
	}
	
	
	/*
	*  admin_enqueue_scripts
	*
	*  @description: run after post query but before any admin script / head actions. A good place to register all actions.
	*  @since: 3.6
	*  @created: 26/01/13
	*/
	
	function admin_enqueue_scripts()
	{
		// actions
		do_action('acf/input/admin_enqueue_scripts');
	}
	
	
	/*
	*  admin_head
	*
	*  @description: 
	*  @since: 2.0.4
	*  @created: 5/12/12
	*/
	
	function admin_head()
	{	
	
		// verify nonce
		if( isset($_POST['acf_nonce']) && wp_verify_nonce($_POST['acf_nonce'], 'input') )
		{
			do_action('acf/save_post', 'options');
			
			$this->data['admin_message'] = __("Options Updated",'acf');
		}
		
		
		// get field groups
		$filter = array();
		$metabox_ids = array();
		$metabox_ids = apply_filters( 'acf/location/match_field_groups', $metabox_ids, $filter );

		
		if( empty($metabox_ids) )
		{
			$this->data['no_fields'] = true;
			return false;	
		}
		
		
		// Style
		echo '<style type="text/css">#side-sortables.empty-container { border: 0 none; }</style>';
		
		
		// add user js + css
		do_action('acf/input/admin_head');
		
		
		// get field groups
		$acfs = apply_filters('acf/get_field_groups', array());
		
		
		if( $acfs )
		{
			foreach( $acfs as $acf )
			{
				// load options
				$acf['options'] = apply_filters('acf/field_group/get_options', array(), $acf['id']);
				
				
				// vars
				$show = in_array( $acf['id'], $metabox_ids ) ? 1 : 0;
				
				if( !$show )
				{
					continue;
				}
				
				
				// add meta box
				add_meta_box(
					'acf_' . $acf['id'], 
					$acf['title'], 
					array($this, 'meta_box_input'), 
					'acf_options_page',
					$acf['options']['position'], 
					'high',
					array( 'field_group' => $acf, 'show' => $show, 'post_id' => 'options' )
				);
				
			}
			// foreach($acfs as $acf)
		}
		// if($acfs)
		
	}
	
	
	/*
	*  meta_box_input
	*
	*  @description: 
	*  @since 1.0.0
	*  @created: 23/06/12
	*/
	
	function meta_box_input( $post, $args )
	{
		// vars
		$options = $args['args'];
		
		
		echo '<div class="options" data-layout="' . $options['field_group']['options']['layout'] . '" data-show="' . $options['show'] . '" style="display:none"></div>';
		
		$fields = apply_filters('acf/field_group/get_fields', array(), $options['field_group']['id']);
					
		do_action('acf/create_fields', $fields, $options['post_id']);
		
	}
	
	
	/*
	*  admin_footer
	*
	*  @description: 
	*  @since: 2.0.4
	*  @created: 5/12/12
	*/
	
	function admin_footer()
	{
		// add togle open / close postbox
		?>
		<script type="text/javascript">
		(function($){
			
			$('.postbox .handlediv').live('click', function(){
				
				var postbox = $(this).closest('.postbox');
				
				if( postbox.hasClass('closed') )
				{
					postbox.removeClass('closed');
				}
				else
				{
					postbox.addClass('closed');
				}
				
			});
			
		})(jQuery);
		</script>
		<?php
	}
	
	
	/*
	*  html
	*
	*  @description: 
	*  @since: 2.0.4
	*  @created: 5/12/12
	*/
	
	function html()
	{
		?>
		<div class="wrap no_move">
		
			<div class="icon32" id="icon-options-general"><br></div>
			<h2><?php echo get_admin_page_title(); ?></h2>
			
			<?php if(isset($this->data['admin_message'])): ?>
			<div id="message" class="updated"><p><?php echo $this->data['admin_message']; ?></p></div>
			<?php endif; ?>
			
			<?php if(isset($this->data['no_fields'])): ?>
			<div id="message" class="updated"><p><?php _e("No Custom Field Group found for the options page",'acf'); ?>. <a href="<?php echo admin_url(); ?>post-new.php?post_type=acf"><?php _e("Create a Custom Field Group",'acf'); ?></a></p></div>
			<?php else: ?>
			
			<form id="post" method="post" name="post">
			<div class="metabox-holder has-right-sidebar" id="poststuff">
				
				<!-- Sidebar -->
				<div class="inner-sidebar" id="side-info-column">
					
					<!-- Update -->
					<div class="postbox">
						<h3 class="hndle"><span><?php _e("Publish",'acf'); ?></span></h3>
						<div class="inside">
							<input type="hidden" name="HTTP_REFERER" value="<?php echo $_SERVER['HTTP_REFERER'] ?>" />
							<input type="hidden" name="acf_nonce" value="<?php echo wp_create_nonce( 'input' ); ?>" />
							<input type="submit" class="acf-button" value="<?php _e("Save Options",'acf'); ?>" />
						</div>
					</div>
					
					<?php $meta_boxes = do_meta_boxes('acf_options_page', 'side', null); ?>
					
				</div>
					
				<!-- Main -->
				<div id="post-body">
				<div id="post-body-content">
					<?php $meta_boxes = do_meta_boxes('acf_options_page', 'normal', null); ?>
					<script type="text/javascript">
					(function($){
					
						$('#poststuff .postbox[id*="acf_"]').addClass('acf_postbox');

					})(jQuery);
					</script>
				</div>
				</div>
			
			</div>
			</form>
			
			<?php endif; ?>
		
		</div>
		
		<?php
				
	}
	
	
}

$GLOBALS['acf_options_page'] = new acf_options_page_plugin();


/*
*  Update
*
*  if update file exists, allow this add-on to connect and recieve updates.
*  all ACF premium Add-ons which are distributed within a plugin or theme, must have the update file removed.
*
*  @type	file
*  @date	13/07/13
*
*  @param	N/A
*  @return	N/A
*/

if( file_exists(  dirname( __FILE__ ) . '/acf-options-page-update.php' ) )
{
	include_once( dirname( __FILE__ ) . '/acf-options-page-update.php' );
}



/*
*  acf_set_options_page_title
*
*  this function is used to customize the options page admin menu title
*
*  @type	function
*  @date	13/07/13
*
*  @param	{string}	$title
*  @return	N/A
*/

function acf_set_options_page_title( $title = 'Options' )
{
	$GLOBALS['acf_options_page']->settings['title'] = $title;
}


/*
*  acf_set_options_page_menu
*
*  this function is used to customize the options page admin menu name
*
*  @type	function
*  @date	13/07/13
*
*  @param	{string}	$title
*  @return	N/A
*/

function acf_set_options_page_menu( $menu = 'Options' )
{
	$GLOBALS['acf_options_page']->settings['menu'] = $menu;
}


/*
*  acf_set_options_page_capability
*
*  this function is used to customize the options page capability. Defaults to 'edit_posts'
*  Read more: http://codex.wordpress.org/Roles_and_Capabilities
*
*  @type	function
*  @date	13/07/13
*
*  @param	{string}	$capability
*  @return	N/A
*/

function acf_set_options_page_capability( $capability = 'edit_posts' )
{
	$GLOBALS['acf_options_page']->settings['capability'] = $capability;
}


/*
*  acf_add_options_sub_page
*
*  this function is used to add a sub page to the options page menu
*
*  @type	function
*  @date	13/07/13
*
*  @param	{mixed}	$page	either a string for the sub page title, or an array with more information. 
*							The array can contain the following args:
*							+ {string} title - required
*							+ {string} menu - not required
*							+ {string} slug - not required
*							+ {string} parent - not required
*							+ {string} capability - not required
*  @return	N/A
*/

function acf_add_options_sub_page( $page = false )
{
	// validate
	if( !$page )
	{
		return false;
	}
	
	$GLOBALS['acf_options_page']->settings['pages'][] = $page;
}


/*
*  register_options_page()
*
*  This is an old function which is now referencing the new 'acf_add_options_sub_page' function
*
*  @type	function
*  @since	3.0.0
*  @date	29/01/13
*
*  @param	{string}	$title
*  @return	N/A
*/


if( !function_exists('register_options_page') )
{
	function register_options_page( $title = false )
	{
		acf_add_options_sub_page( $title );
	}
}


?>
