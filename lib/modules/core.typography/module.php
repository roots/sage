<?php

/*
 * The typography core options for the Shoestrap theme
 */
if ( !function_exists( 'shoestrap_module_typography_options' ) ) {
  function shoestrap_module_typography_options() {

    /*-----------------------------------------------------------------------------------*/
    /* The Options Array */
    /*-----------------------------------------------------------------------------------*/

    // Set the Options Array
    global $of_options, $smof_details;

    // Typography
    $of_options[] = array(
      "name"      => __("Typography Options", "shoestrap"),
      "type"      => "heading"
    );

    $of_options[] = array(
      "name"      => __("Base Font", "shoestrap"),
      "desc"      => __("The main font for your site.", "shoestrap"),
      "id"        => "font_base",
      'less'      => true,
      "std"       => array(
        'face'    => 'Arial, Helvetica, sans-serif',
        'size'    => '14px',
        'google'  => 'false',
        'weight'  => 'inherit',
        'color'   => '#333333',
      ),
      "preview"   => array(
        "text"    => __( "This is my preview text!", "shoestrap" ), //this is the text from preview box
        "size"    => "30px" //this is the text size from preview box
      ),
      "type"      => "select_google_font_hybrid",
    );

    $of_options[] = array(
      "name"      => __("Header Overrides", "shoestrap"),
      "desc"      => __("By enabling this you can specify custom values for each <h*> tag. Default: Off", "shoestrap"),
      "id"        => "font_heading_custom",
      "std"       => 0,
      "less"      => true,
      "type"      => "switch",
      "customizer"=> array(),
      "fold"      => "advanced_toggle"
    );

    $of_options[] = array(
      "name"      => __("H1 Font", "shoestrap"),
      "desc"      => __("The main font for your site.", "shoestrap"),
      "id"        => "font_h1",
      'less'      => true,
      "std"       => array(
        'face'    => 'Arial, Helvetica, sans-serif',
        'size'    => '38px',
        'color'   => '#333333',
        'google'  => 'false'
      ),
      "preview"   => array(
        "text"    => __( "This is my preview text!", "shoestrap" ), //this is the text from preview box
        "size"    => "30px" //this is the text size from preview box
      ),
      "type"      => "select_google_font_hybrid",
      "fold"      => "font_heading_custom",
    );

    $of_options[] = array(
      "name"      => __("H2 Font", "shoestrap"),
      "desc"      => __("The main font for your site.", "shoestrap"),
      "id"        => "font_h2",
      'less'      => true,
      "std"       => array(
        'face'    => 'Arial, Helvetica, sans-serif',
        'size'    => '32px',
        'color'   => '#333333',
        'google'  => 'false'
      ),
      "preview"   => array(
        "text"    => __( "This is my preview text!", "shoestrap" ), //this is the text from preview box
        "size"    => "30px" //this is the text size from preview box
      ),
      "type"      => "select_google_font_hybrid",
      "fold"      => "font_heading_custom",
    );

    $of_options[] = array(
      "name"      => __("H3 Font", "shoestrap"),
      "desc"      => __("The main font for your site.", "shoestrap"),
      "id"        => "font_h3",
      'less'      => true,
      "std"       => array(
        'face'    => 'Arial, Helvetica, sans-serif',
        'size'    => '24px',
        'color'   => '#333333',
        'google'  => 'false'
      ),
      "preview"   => array(
        "text"    => __( "This is my preview text!", "shoestrap" ), //this is the text from preview box
        "size"    => "30px" //this is the text size from preview box
      ),
      "type"      => "select_google_font_hybrid",
      "fold"      => "font_heading_custom",
    );

    $of_options[] = array(
      "name"      => __("H4 Font", "shoestrap"),
      "desc"      => __("The main font for your site.", "shoestrap"),
      "id"        => "font_h4",
      'less'      => true,
      "std"       => array(
        'face'    => 'Arial, Helvetica, sans-serif',
        'size'    => '18px',
        'color'   => '#333333',
        'google'  => 'false'
      ),
      "preview"   => array(
        "text"    => __( "This is my preview text!", "shoestrap" ), //this is the text from preview box
        "size"    => "30px" //this is the text size from preview box
      ),
      "type"      => "select_google_font_hybrid",
      "fold"      => "font_heading_custom",
    );

    $of_options[] = array(
      "name"      => __("H5 Font", "shoestrap"),
      "desc"      => __("The main font for your site.", "shoestrap"),
      "id"        => "font_h5",
      'less'      => true,
      "std"       => array(
        'face'    => 'Arial, Helvetica, sans-serif',
        'size'    => '16px',
        'color'   => '#333333',
        'google'  => 'false'
      ),
      "preview"   => array(
        "text"    => __( "This is my preview text!", "shoestrap" ), //this is the text from preview box
        "size"    => "30px" //this is the text size from preview box
      ),
      "type"      => "select_google_font_hybrid",
      "fold"      => "font_heading_custom",
    );

    $of_options[] = array(
      "name"      => __("H6 Font", "shoestrap"),
      "desc"      => __("The main font for your site.", "shoestrap"),
      "id"        => "font_h6",
      'less'      => true,
      "std"       => array(
        'face'    => 'Arial, Helvetica, sans-serif',
        'size'    => '12px',
        'color'   => '#333333',
        'google'  => 'false'
      ),
      "preview"   => array(
        "text"    => __( "This is my preview text!", "shoestrap" ), //this is the text from preview box
        "size"    => "30px" //this is the text size from preview box
      ),
      "type"      => "select_google_font_hybrid",
      "fold"      => "font_heading_custom",
    );


    do_action( 'shoestrap_module_typography_options_modifier' );

    $smof_details = array();
    foreach( $of_options as $option ) {
      if (isset($option['id']))
        $smof_details[$option['id']] = $option;
    }
  }
}
add_action( 'init', 'shoestrap_module_typography_options', 80 );

include_once( dirname(__FILE__).'/functions.typography.php' );