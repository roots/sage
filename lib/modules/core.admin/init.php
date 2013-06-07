<?php
/*
Title   : SMOF
Description : Slightly Modified Options Framework
Version   : 1.5
Author    : Syamil MJ
Author URI  : http://aquagraphite.com
License   : GPLv3 - http://www.gnu.org/copyleft/gpl.html

Credits   : Thematic Options Panel - http://wptheming.com/2010/11/thematic-options-panel-v2/
        Woo Themes - http://woothemes.com/
        Option Tree - http://wordpress.org/extend/plugins/option-tree/

Contributors: Syamil MJ - http://aquagraphite.com
        Andrei Surdu - http://smartik.ws/
        Jonah Dahlquist - http://nucleussystems.com/
        partnuz - https://github.com/partnuz
        Alex Poslavsky - https://github.com/plovs
*/

/**
 * Definitions
 *
 * @since 1.4.0
 */
$theme_version = '';
      
if( function_exists( 'wp_get_theme' ) ) {
  if( is_child_theme() ) {
    $temp_obj = wp_get_theme();
    $theme_obj = wp_get_theme( $temp_obj->get('Template') );
  } else {
    $theme_obj = wp_get_theme();    
  }

  $theme_version = $theme_obj->get('Version');
  $theme_name = $theme_obj->get('Name');
  $theme_uri = $theme_obj->get('ThemeURI');
  $author_uri = $theme_obj->get('AuthorURI');
} else {
  $theme_data = get_theme_data( get_template_directory().'/style.css' );
  $theme_version = $theme_data['Version'];
  $theme_name = $theme_data['Name'];
  $theme_uri = $theme_data['ThemeURI'];
  $author_uri = $theme_data['AuthorURI'];
}


define( 'SMOF_VERSION', '1.5' );


if( !defined('SMOF_DIR') )
  define( 'SMOF_DIR','/lib/modules/core.admin/');

if( !defined('ADMIN_PATH') )
  define( 'ADMIN_PATH', get_template_directory() . SMOF_DIR . "Options-Framework/admin/" );
if( !defined('ADMIN_DIR') )
  define( 'ADMIN_DIR', get_template_directory_uri() . SMOF_DIR . "Options-Framework/admin/");



define( 'ADMIN_IMAGES', ADMIN_DIR . 'assets/images/' );

define( 'LAYOUT_PATH', ADMIN_PATH . 'layouts/' );
define( 'THEMENAME', $theme_name );
/* Theme version, uri, and the author uri are not completely necessary, but may be helpful in adding functionality */
define( 'THEMEVERSION', $theme_version );
define( 'THEMEURI', $theme_uri );
define( 'THEMEAUTHORURI', $author_uri );

define( 'BACKUPS','backups' );

/**
 * Required action filters
 *
 * @uses add_action()
 *
 * @since 1.0.0
 */
//if (is_admin() && isset($_GET['activated'] ) && $pagenow == "themes.php" ) add_action('admin_head','of_option_setup');
add_action('admin_head', 'optionsframework_admin_message');
add_action('admin_init','optionsframework_admin_init');
add_action('admin_menu', 'optionsframework_add_admin');

/**
 * Required Files
 *
 * @since 1.0.0
 */ 

require_once ( ADMIN_PATH . 'functions/functions.load.php' );
require_once ( ADMIN_PATH . 'classes/class.options_machine.php' );

/**
 * AJAX Saving Options
 *
 * @since 1.0.0
 */
add_action('wp_ajax_of_ajax_post_action', 'of_ajax_callback');



include_once( dirname(__FILE__) . '/addons/functions/functions.admin.php'); 
include_once( dirname(__FILE__) . '/addons/functions/functions.options.php'); 
include_once( dirname(__FILE__) . '/addons/functions/functions.interface.php'); 
include_once( dirname(__FILE__) . '/addons/functions/functions.customizer.php'); 
include_once( dirname(__FILE__) . '/addons/functions/functions.customizer.standard.php'); 


/*

CUSTOMIZER TODO

Customizer Basic
  Fix Checkboxes!!!

Customizer Pro
  Hide Areas (Fold)
  Font Selector (Google Font & Regular)
  Borders

Options Panel
  Advanced Typography Type
  

*/

?>