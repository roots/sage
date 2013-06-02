<?php

/*
 * The footer core options for the Shoestrap theme
 */
if ( !function_exists( 'shoestrap_module_footer_options' ) ) {
  function shoestrap_module_footer_options() {

    /*-----------------------------------------------------------------------------------*/
    /* The Options Array */
    /*-----------------------------------------------------------------------------------*/

    // Set the Options Array
    global $of_options, $smof_details;

    // Footer
    $of_options[] = array(
      "name"      => __("Footer Options", "shoestrap"),
      "type"      => "heading"
    );

    $of_options[] = array(
      "name"      => __("Footer Background Color", "shoestrap"),
      "desc"      => __("Select the background color for your footer. Default: #ffffff.", "shoestrap"),
      "id"        => "footer_background",
      "std"       => "#ffffff",
      "customizer"=> array(),
      "type"      => "color"
    );
    $of_options[] = array(
      "name"      => __("Footer Background Opacity", "shoestrap"),
      "desc"      => __("Select the opacity level for the footer bar. Default: 100%.", "shoestrap"),
      "id"        => "footer_opacity",
      "std"       => 100,
      "min"       => 30,
      "max"       => 100,
      "type"      => "sliderui"
    );

    $of_options[] = array(
      "name"      => __("Footer Text Color", "shoestrap"),
      "desc"      => __("Select the text color for your footer. Default: #333333.", "shoestrap"),
      "id"        => "footer_color",
      "std"       => "#333333",
      "customizer"=> array(),
      "type"      => "color"
    );

    $of_options[] = array(
      "name"      => __("Footer Text", "shoestrap"),
      "desc"      => __("The text that will be displayed in your footer. Default: your site's name.", "shoestrap"),
      "id"        => "footer_text",
      "std"       => get_bloginfo( 'name' ),
      "customizer"=> array(),
      "type"      => "textarea"
    );

    $of_options[] = array(
      "name"      => "Footer Top Border",
      "desc"      => "Select the border options for your Footer",
      "id"        => "footer_border_top",
      "type"      => "border",
      "std"       => array(
        'width'   => '2',
        'style'   => 'solid',
        'color'   => shoestrap_getVariable( 'color_brand_info' ),
      )
    );

    do_action( 'shoestrap_module_footer_options_modifier' );

    $smof_details = array();
    foreach( $of_options as $option ) {
      $smof_details[$option['id']] = $option;
    }
  }
}
add_action( 'init','shoestrap_module_footer_options' );
