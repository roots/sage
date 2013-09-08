<?php

include_once( dirname( __FILE__ ) . '/Redux-Framework/framework.php' );

function ReduxFramework( $sections = array(), $args = array(), $tabs = array() ) {
  global $ReduxFramework, $options;
  $ReduxFramework = new ReduxFramework( $sections, $args, $tabs );
} // function

/*
 * Require the framework class before doing anything else, so we can use the defined urls and dirs
 * Also if running on windows you may have url problems, which can be fixed by defining the framework url first
 */
function shoestrap_reduxframework_init() {
  if ( class_exists( 'ReduxFramework' ) ) :
    global $ReduxFramework, $options;

    if ( !empty( $ReduxFramework ) ) :
      return;
    endif;

    $args = array();

    // Set it to dev mode to view the class settings/info in the form - default is false
    // $args['dev_mode'] = true;

    // Enable customizer support for all of the fields unless denoted as customizer=>false in the field declaration
    $args['customizer'] = true;

    //google api key MUST BE DEFINED IF YOU WANT TO USE GOOGLE WEBFONTS
    $args['google_api_key'] = 'AIzaSyAX_2L_UzCDPEnAHTG7zhESRVpMPS4ssII';
    // ** PLEASE PLEASE for production use your own key! **

    // Add HTML before the form
    // $args['intro_text'] = __('<p>This is the HTML which can be displayed before the form, it isn\'t required, but more info is always better. Anything goes in terms of markup here, any HTML.</p>', 'shoestrap');

    //Setup custom links in the footer for share icons
    // $args['share_icons']['twitter'] = array(
    //  'link'  => 'https://github.com/shoestrap/shoestrap',
    //  'title' => 'Fork Me on GitHub',
    //  'img'   => REDUX_URL.'img/glyphicons/glyphicons_341_github.png'
    // );

    // Choose a custom option name for your theme options, the default is the theme name in lowercase with spaces replaced by underscores
    $args['opt_name'] = 'shoestrap';

    // Custom menu icon
    // $args['menu_icon'] = '';

    // Custom menu title for options page - default is "Options"
    $args['menu_title'] = wp_get_theme();

    // Custom page location - default 100 - must be unique or will override other items
    $args['page_position'] = 27;

    // Custom page icon class (used to override the page icon next to heading)
    // $args['page_icon'] = 'icon-themes';

    // Set ANY custom page help tabs - displayed using the new help tab API, show in order of definition
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

    //Set the Help Sidebar for the options page - no sidebar by default                   
    $args['help_sidebar'] = __( '<p>This is the sidebar content, HTML is allowed.</p>', 'shoestrap' );

    $sections = array();
    $sections = apply_filters( 'shoestrap_add_sections', $sections );

    ReduxFramework( $sections, $args );

    if ($sof['dev_mode'] == 1) :
      $ReduxFramework->args['dev_mode'] = true;
    endif;
  endif;
}//function
add_action('init', 'shoestrap_reduxframework_init');

// Saving functions on import, etc
// If a compiler field was altered or import or reset defaults
add_action( 'redux_compiler', 'shoestrap_makecss' );