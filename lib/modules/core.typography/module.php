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
      "name"      => __("Font Size Base", "shoestrap"),
      "desc"      => __("The basic font size. Based on this, all the other text elements will also be calculated (for example titles etc).", "shoestrap"),
      "id"        => "typography_font_size_base",
      "std"       => 14,
      "min"       => 9,
      "step"      => 1,
      "max"       => 22,
      "less"      => true,
      "type"      => "sliderui"
    );

    $of_options[] = array(
      "name"      => __("Text Color", "shoestrap"),
      "desc"      => __("Pick a color for your site's main text. Default: #333333.", "shoestrap"),
      "id"        => "color_text",
      "std"       => "#333333",
      "less"      => true,
      "customizer"=> array(),
      "type"      => "color"
    );

    $of_options[] = array(
      "name"      => __("Links Color", "shoestrap"),
      "desc"      => __("Pick a color for your site's links. Default: #428bca.", "shoestrap"),
      "id"        => "color_links",
      "std"       => "#428bca",
      "less"      => true,
      "customizer"=> array(),
      "type"      => "color"
    );

    $of_options[] = array(
      "name"      => __("Font", "shoestrap"),
      "desc"      => __("The main font for your site.", "shoestrap"),
      "id"        => "typography_sans_serif",
      "std"       => "'Helvetica Neue', Helvetica, Arial, sans-serif",
      "type"      => "text",
    );

    $of_options[] = array(  "name"    => "Typography",
        "desc"    => "Typography option with each property can be called individually.",
        "id"    => "typography_sans_serif2",
        "std"     => array('face'=>'Helvetica','size' => '12px','style' => 'bold italic', 'color'=>'black'),
        "type"    => "typography"
    );


    $of_options[] = array(
      "name"      => __("Base Font Family", "shoestrap"),
      "desc"      => __("The main font for your site.", "shoestrap"),
      "id"        => "base_font_family",
      "std"       => "Open Sans",
      "preview"   => array(
              "text" => "This is my preview text!", //this is the text from preview box
              "size" => "30px" //this is the text size from preview box
      ),
      "type"    => "select_google_font",
      "options"   => array(
        "Open Sans" => "Open Sans",
        "Loved by the King" => "Loved By the King",
        "Tangerine" => "Tangerine",
        "Terminal Dosis" => "Terminal Dosis"
      )    
    );  

         $of_options[] = array(
      "name"      => __("Base Font Family", "shoestrap"),
      "desc"      => __("The main font for your site.", "shoestrap"),
      "id"        => "base_font_family_test",
      "std"       => "Open Sans",
      "preview"   => array(
              "text" => "This is my preview text!", //this is the text from preview box
              "size" => "30px" //this is the text size from preview box
      ),
      "type"    => "select_google_font_hybrid",
      "options"   => array(
        "Open Sans" => "Open Sans",
        "Loved by the King" => "Loved By the King",
        "Tangerine" => "Tangerine",
        "Terminal Dosis" => "Terminal Dosis"
      ), 
      "standard"   => array(
        "Open Sans" => "Open Sans",
        "Loved by the King" => "Loved By the King",
        "Tangerine" => "Tangerine",
        "Terminal Dosis" => "Terminal Dosis"
      )     
    ); 


    do_action( 'shoestrap_module_typography_options_modifier' );

    $smof_details = array();
    foreach( $of_options as $option ) {
      $smof_details[$option['id']] = $option;
    }
  }
}
add_action( 'init', 'shoestrap_module_typography_options', 80 );

include_once( dirname(__FILE__).'/functions.typography.php' );
add_action( 'optionsframework_machine_loop', 'shoestrap_add_typography_class_case' );