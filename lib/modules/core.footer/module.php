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
      "desc"      => __("Select the background color for your footer. Default: #282a2b.", "shoestrap"),
      "id"        => "footer_background",
      "std"       => "#282a2b",
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
      "desc"      => __("Select the text color for your footer. Default: #8C8989.", "shoestrap"),
      "id"        => "footer_color",
      "std"       => "#8C8989",
      "customizer"=> array(),
      "type"      => "color"
    );

    $of_options[] = array(
      "name"      => __("Footer Text", "shoestrap"),
      "desc"      => __("The text that will be displayed in your footer. You can use [year] and [sitename] and they will be replaced appropriately. Default: &copy; [year] [sitename]", "shoestrap"),
      "id"        => "footer_text",
      "std"       => '&copy; [year] [sitename]',
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
        'color'   => '#4B4C4D',
      )
    );

    $of_options[] = array(
      "name"      => __("Show social icons in footer", "shoestrap"),
      "desc"      => __("Show social icons in the footer. Default: On.", "shoestrap"),
      "id"        => "footer_social_toggle",
      "std"       => 0,
      "customizer"=> array(),
      "type"      => "switch"
    );

    $of_options[] = array(
      "name"      => __("Footer social links column width", "shoestrap"),
      "desc"      => __("You can customize the width of the footer social links area. The footer text width will be adjusted accordingly. Default: 5.", "shoestrap"),
      "id"        => "footer_social_width",
      "fold"      => 'footer_social_toggle',
      "std"       => 6,
      "min"       => 3,
      "step"      => 1,
      "max"       => 10,
      "customizer"=> array(),
      "type"      => "sliderui"
    );    

        
    $of_options[] = array(
      "name"      => __("Footer social icons open new window", "shoestrap"),
      "desc"      => __("Social icons in footer will open a new window. Default: On.", "shoestrap"),
      "id"        => "footer_social_new_window_toggle",
      "fold"      => 'footer_social_toggle',
      "std"       => 1,
      "customizer"=> array(),
      "type"      => "switch"
    );

    do_action( 'shoestrap_module_footer_options_modifier' );

    $smof_details = array();
    foreach( $of_options as $option ) {
      $smof_details[$option['id']] = $option;
    }
  }
}
add_action( 'init','shoestrap_module_footer_options', 90 );

include_once( dirname(__FILE__).'/functions.footer.php' );
