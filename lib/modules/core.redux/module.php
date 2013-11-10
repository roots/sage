<?php

if ( !class_exists( 'ReduxFramework' ) && file_exists( dirname( __FILE__ ) . '/ReduxFramework/ReduxCore/framework.php' ) ) :
  include_once( dirname( __FILE__ ) . '/ReduxFramework/ReduxCore/framework.php' );
endif;

/*
 * Require the framework class before doing anything else, so we can use the defined urls and dirs
 * Also if running on windows you may have url problems, which can be fixed by defining the framework url first
 */

if ( class_exists( 'ReduxFramework' ) ) :
  
  define('REDUX_OPT_NAME', 'shoestrap');

  $args = $tabs = array();

  // ** PLEASE PLEASE for production use your own key! **

  //Setup custom links in the footer for share icons
   $args['share_icons']['twitter'] = array(
    'link'  => 'https://github.com/shoestrap/shoestrap',
    'title' => 'Fork Me on GitHub',
    'img'   => ReduxFramework::$_url . '/assets/img/social/GitHub.png'
   );

  // Choose a custom option name for your theme options, the default is the theme name in lowercase with spaces replaced by underscores
  $args['opt_name']               = REDUX_OPT_NAME;
  $args['customizer']             = false;
  $args['google_api_key']         = 'AIzaSyCDiOc36EIOmwdwspLG3LYwCg9avqC5YLs';
  $args['global_variable']        = 'redux';
  $args['default_show']           = true;
  $args['default_mark']           = '*';
  $args['page_slug']              = REDUX_OPT_NAME;
  $theme                          = wp_get_theme();
  $args['display_name']           = $theme->get( 'Name' );
  $args['menu_title']             = $theme->get( 'Name' );
  $args['display_version']        = $theme->get( 'Version' );    
  $args['page_position']          = 99;
//  $args['database'] 			  = 'theme_mods_expanded';
  $args['import_icon_class']      = 'icon-large';
  $args['system_info_icon_class'] = 'icon-large';
  $args['dev_mode_icon_class']    = 'icon-large';

  $args['help_tabs'][] = array(
    'id'      => 'redux-options-1',
    'title'   => __( 'Theme Information 1', 'shoestrap' ),
    'content' => __('<p>This is the tab content, HTML is allowed.</p>', 'shoestrap' )
  );
  $args['help_tabs'][] = array(
    'id'      => 'redux-options-2',
    'title'   => __( 'Theme Information 2', 'shoestrap' ),
    'content' => __( '<p>This is the tab content, HTML is allowed. Tab2</p>', 'shoestrap' )
  );

  

  $args['edd'] = array(
    'mode'            => 'template', // template|plugin
    'path'            => '', // Path to the plugin/template main file
    'remote_api_url'  => 'http://shoestrap.org',    // our store URL that is running EDD
    'version'         => $theme->get( 'Version' ), // current version number
    'item_name'       => $theme->get( 'Name' ), // name of this theme
    'author'          => $theme->get( 'Author' ), // author of this theme
    'field_id'        => "shoestrap_license_key", // ID of the field used by EDD
    );  

  //Set the Help Sidebar for the options page - no sidebar by default                   
  $args['help_sidebar'] = __( '<p>This is the sidebar content, HTML is allowed.</p>', 'shoestrap' );

  $sections = array();
  $sections = apply_filters( 'shoestrap_add_sections', $sections );

  $ReduxFramework = new ReduxFramework( $sections, $args, $tabs );

  if ( !empty( $redux['dev_mode'] ) && $redux['dev_mode'] == 1 ) :
    $ReduxFramework->args['dev_mode']     = true;
    $ReduxFramework->args['system_info']  = true;
  endif;
endif;

// Saving functions on import, etc
// If a compiler field was altered or import or reset defaults
add_action( 'redux-compiler-'.REDUX_OPT_NAME , 'shoestrap_makecss' );


/**
 * Adds tracking parameters for Redux settings. Outside of the main class as the class could also be in use in other plugins.
 *
 * @param array $options
 * @return array
 */
function shoestrap_tracking_additions( $options ) {
  $opt = array();
  $options['shoestrap'] = array('title'=>'Shoestrap');

  return $options;
}
add_filter( 'redux/tracking/developer', 'shoestrap_tracking_additions' );
