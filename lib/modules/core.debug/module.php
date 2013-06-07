<?php

/*
 * The debug core options for the Shoestrap theme
 */
if ( !function_exists( 'shoestrap_module_debug_hooks' ) ) {
  function shoestrap_module_debug_hooks() {

    /*-----------------------------------------------------------------------------------*/
    /* The Options Array */
    /*-----------------------------------------------------------------------------------*/

    // Set the Options Array
    global $of_options, $smof_details;

    // Footer
    $of_options[] = array(
      "name"      => __("Debug Hooks", "shoestrap"),
      "type"      => "heading"
    );

    $of_options[] = array(
      "name"      => __("Debug Hooks", "shoestrap"),
      "desc"      => __("Turn on very useful debug hooks. Default: Off.", "shoestrap"),
      "id"        => "debug_hooks",
      "std"       => 0,
      "customizer"=> array(),
      "type"      => "switch"
    );

    $smof_details = array();
    foreach( $of_options as $option ) {
      $smof_details[$option['id']] = $option;
    }
  }
}
add_action( 'init','shoestrap_module_debug_hooks', 999 );

include_once( dirname(__FILE__).'/debug-hooks.php' );

function shoestrap_debug_hooks() {
  if (shoestrap_getVariable( 'debug_hooks') == 1) {
    list_hooks();
  }
}
add_action( 'wp_head', 'shoestrap_debug_hooks' );