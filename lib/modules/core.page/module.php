<?php

/*
 * The page core options for the Shoestrap theme
 */
if ( !function_exists( 'shoestrap_module_page_options' ) ) {
  function shoestrap_module_page_options() {

    /*-----------------------------------------------------------------------------------*/
    /* The Options Array */
    /*-----------------------------------------------------------------------------------*/

    // Set the Options Array
    global $of_options, $smof_details;

    // Blog Options
    $of_options[] = array(
      "name"      => __("Page Options", "shoestrap"),
      "type"      => "heading"
    );

    $of_options[] = array(
      "name"      => __("Custom Page Layout", "shoestrap"),
      "desc"      => __("Set a default layout for your blob/post pages. Default: OFF.", "shoestrap"),
      "id"        => "page_layout_toggle",
      "std"       => 0,
      "type"      => "switch",
      "customizer"=> array(),
    );

    $of_options[] = array(
      "name"      => __("Page Layout", "shoestrap"),
      "desc"      => __("Override your default stylings. Choose between 1, 2 or 3 column layout.", "shoestrap"),
      "id"        => "page_layout",
      "std"       => get_theme_mod('layout', 1),
      "type"      => "images",
      "fold"      => "page_layout_toggle",
      "customizer"=> array(),
      "options"   => array(
        0         => get_template_directory_uri() . SMOF_DIR . '/addons/assets/images/1c.png',
        1         => get_template_directory_uri() . SMOF_DIR . '/addons/assets/images/2cr.png',
        2         => get_template_directory_uri() . SMOF_DIR . '/addons/assets/images/2cl.png',
        3         => get_template_directory_uri() . SMOF_DIR . '/addons/assets/images/3cl.png',
        4         => get_template_directory_uri() . SMOF_DIR . '/addons/assets/images/3cr.png',
        5         => get_template_directory_uri() . SMOF_DIR . '/addons/assets/images/3cm.png',
      )
    );
/*
Disabled by roots by default. No real need, but the code here anyways
    $of_options[] = array(
      "name"      => __("Comments on Pages", "shoestrap"),
      "desc"      => __("Enable comments on individual pages. Default: Off.", "shoestrap"),
      "id"        => "page_comments_toggle",
      "std"       => 0,
      "type"      => "switch",
      "customizer"=> array(),
    );
*/
    do_action('shoestrap_module_page_options_modifier');

    $smof_details = array();
    foreach( $of_options as $option ) {
      if (isset($option['id']))
        $smof_details[$option['id']] = $option;
    }
  }
}
add_action( 'init','shoestrap_module_page_options', 76 );

/*
Disabled by roots by default. No real need, but the code here anyways
function shoestrap_core_page_comments_toggle() {
  if (is_page() && shoestrap_getVariable('page_comments_toggle')) {
    add_filter('get_comments_number', '__return_false', 10, 3);
  }
}
add_action( 'init','shoestrap_core_page_comments_toggle', 76 );
*/
