<?php

/*
 * The jumbotron core options for the Shoestrap theme
 */
if ( !function_exists( 'shoestrap_module_jumbotron_options' ) ) {
  function shoestrap_module_jumbotron_options() {

    /*-----------------------------------------------------------------------------------*/
    /* The Options Array */
    /*-----------------------------------------------------------------------------------*/

    // Set the Options Array
    global $of_options, $smof_details;

    // Jumbotron (Hero)
    $of_options[] = array(
      "name"      => __("Jumbotron Options", "shoestrap"),
      "type"      => "heading"
    );

    $url = admin_url( 'widgets.php' );
    $of_options[] = array(
      "name"      => "",
      "desc"      => "",
      "id"        => "help8",
      "std"       => "<h3 style=\"margin: 0 0 10px;\">Jumbotron</h3>
                      <p>A \"Jumbotron\", also known as \"Hero\" area,
                      is an area in your site where you can display in a prominent position things that matter to you.
                      This can be a slideshow, some text or whatever else you wish.
                      This area is implemented as a widget area, so in order for something to be displayed
                      you will have to add a widget from <a href=\"$url\">here</a>.</p>",
      "icon"      => true,
      "type"      => "info"
    );

    $of_options[] = array(
      "name"      => __("Jumbotron Background Color", "shoestrap"),
      "desc"      => __("Select the background color for your Jumbotron area. Please note that this area will only be visible if you assign a widget to the \"Jumbotron\" Widget Area. Default: #EEEEEE.", "shoestrap"),
      "id"        => "jumbotron_bg",
      "std"       => "#EEEEEE",
      "less"      => true,
      "customizer"=> array(),
      "type"      => "color"
    );


    $of_options[] = array(
      "name"      => __("Background position", "shoestrap"),
      "desc"      => __("Changes how the background image or pattern is displayed from scroll to fixed position. Default: Fixed.", "shoestrap"),
      "id"        => "jumbotron_background_fixed_toggle",
      "std"       => 1,
      "on"        => __("Fixed", "shoestrap"),
      "off"       => __("Scroll", "shoestrap"),
      "type"      => "switch"
    );

    $of_options[] = array(
      "name"      => __("Use a Background Image", "shoestrap"),
      "desc"      => __("Enable this option to upload a custom background image for your site. This will override any patterns you may have selected. Default: OFF.", "shoestrap"),
      "id"        => "jumbotron_background_image_toggle",
      "std"       => 0,
      "type"      => "switch"
    );

    $of_options[] = array(
      "name"      => __("Upload a Custom Background Image", "shoestrap"),
      "desc"      => __("Upload a Custom Background image using the media uploader, or define the URL directly.", "shoestrap"),
      "id"        => "jumbotron_background_image",
      "fold"      => "jumbotron_background_image_toggle",
      "std"       => "",
      "type"      => "media",
      "customizer"=> array(),
    );

    $of_options[] = array(
      "name"      => __("Background Image Positioning", "shoestrap"),
      "desc"      => __("Allows the user to modify how the background displays. By default it is full width and stretched to fill the page. Default: Full Width.", "shoestrap"),
      "id"        => "jumbotron_background_image_position_toggle",
      "std"       => 0,
      "fold"      => "jumbotron_background_image_toggle",
      "on"        => __("Custom", "shoestrap"),
      "off"       => __("Full Width", "shoestrap"),
      "type"      => "switch"
    );

    $of_options[] = array(
      "name"      => __("Background Repeat", "shoestrap"),
      "desc"      => __("Select how (or if) the selected background should be tiled. Default: Tile", "shoestrap"),
      "id"        => "jumbotron_background_repeat",
      "fold"      => "jumbotron_background_image_position_toggle",
      "std"       => "repeat",
      "type"      => "radio",
      "options"   => array(
        'no-repeat'  => __( 'No Repeat', 'shoestrap' ),
        'repeat'     => __( 'Tile', 'shoestrap' ),
        'repeat-x'   => __( 'Tile Horizontally', 'shoestrap' ),
        'repeat-y'   => __( 'Tile Vertically', 'shoestrap' ),
      ),
    );

    $of_options[] = array(
      "name"      => __("Background Alignment", "shoestrap"),
      "desc"      => __("Select how the selected background should be horizontally aligned. Default: Left", "shoestrap"),
      "id"        => "jumbotron_background_position_x",
      "fold"      => "jumbotron_background_image_position_toggle",
      "std"       => "repeat",
      "type"      => "radio",
      "options"   => array(
        'left'    => __( 'Left', 'shoestrap' ),
        'right'   => __( 'Right', 'shoestrap' ),
        'center'  => __( 'Center', 'shoestrap' ),
      ),
    );

    $of_options[] = array(
      "name"      => __("Use a Background Pattern", "shoestrap"),
      "desc"      => __("Select one of the already existing Background Patterns. Default: OFF.", "shoestrap"),
      "id"        => "jumbotron_background_pattern_toggle",
      "std"       => 0,
      "type"      => "switch"
    );

    $of_options[] = array(
      "name"      => __("Choose a Background Pattern", "shoestrap"),
      "desc"      => __("Select a background pattern.", "shoestrap"),
      "id"        => "jumbotron_background_pattern",
      "fold"      => "jumbotron_background_pattern_toggle",
      "std"       => "",
      "type"      => "tiles",
      "options"   => $bg_pattern_images,
    );


    $of_options[] = array(
      "name"      => __("Jumbotron Text Color", "shoestrap"),
      "desc"      => __("Select the text color for your Jumbotron area. Please note that this area will only be visible if you assign a widget to the \"Jumbotron\" Widget Area. Default: #333333.", "shoestrap"),
      "id"        => "jumbotron_color",
      "std"       => "#333333",
      "less"      => true,
      "customizer"=> array(),
      "type"      => "color"
    );

    $of_options[] = array(
      "name"      => __("Display Jumbotron only on the Frontpage.", "shoestrap"),
      "desc"      => __("When Turned OFF, the Jumbotron area is displayed in all your pages. If you wish to completely disable the Jumbotron, then please remove the widgets assigned to its area and it will no longer be displayed. Default: ON", "shoestrap"),
      "id"        => "jumbotron_visibility",
      "customizer"=> array(),
      "std"       => 1,
      "type"      => "switch"
    );

    $of_options[] = array(
      "name"      => __("Full-Width", "shoestrap"),
      "desc"      => __("When Turned ON, the Jumbotron is no longer restricted by the width of your page, taking over the full width of your screen. This option is useful when you have assigned a slider widget on the Jumbotron area and you want its width to be the maximum width of the screen. Default: OFF.", "shoestrap"),
      "id"        => "jumbotron_nocontainer",
      "customizer"=> array(),
      "std"       => 1,
      "type"      => "switch"
    );

    $of_options[] = array(
      "name"      => __("Use fittext script for the title.", "shoestrap"),
      "desc"      => __("Use the fittext script to enlarge or scale-down the font-size of the widget title to fit the Jumbotron area. Default: OFF", "shoestrap"),
      "id"        => "jumbotron_title_fit",
      "customizer"=> array(),
      "std"       => 0,
      "type"      => "switch"
    );

    $of_options[] = array(
      "name"      => __("Center-align the content.", "shoestrap"),
      "desc"      => __("Turn this on to center-align the contents of the Jumbotron area. Default: OFF", "shoestrap"),
      "id"        => "jumbotron_center",
      "customizer"=> array(),
      "std"       => 0,
      "type"      => "switch"
    );

    $of_options[] = array(
      "name"      => "Jumbotron Bottom Border",
      "desc"      => __("Select the border options for your Jumbotron", "shoestrap"),
      "id"        => "jumbotron_border_bottom",
      "type"      => "border",
      "std"       => array(
        'width'   => '0',
        'style'   => 'solid',
        'color'   => "#428bca",
      )
    );

    do_action( 'shoestrap_module_jumbotron_options_modifier' );

    $smof_details = array();
    foreach( $of_options as $option ) {
      $smof_details[$option['id']] = $option;
    }
  }
}

add_action( 'init','shoestrap_module_jumbotron_options', 70 );

include_once( dirname(__FILE__).'/functions.jumbotron.php' );
