<?php

/*
 * The background core options for the Shoestrap theme
 */
if ( !function_exists( 'shoestrap_module_background_options' ) ) {
  function shoestrap_module_background_options() {

    //Background Patterns Reader
    $bg_pattern_images_path = get_template_directory() . '/lib/assets/img/patterns';
    $bg_pattern_images_url  = get_bloginfo( 'template_url' ) . '/lib/assets/img/patterns/';
    $bg_pattern_images      = array();

    if ( is_dir($bg_pattern_images_path) ) {
      if ( $bg_pattern_images_dir = opendir( $bg_pattern_images_path ) ) {
        while ( ( $bg_pattern_images_file = readdir( $bg_pattern_images_dir ) ) !== false ) {
          if( stristr( $bg_pattern_images_file, ".png" ) !== false || stristr( $bg_pattern_images_file, ".jpg" ) !== false)
            $bg_pattern_images[] = $bg_pattern_images_url . $bg_pattern_images_file;
        }
      }
    }

    /*-----------------------------------------------------------------------------------*/
    /* The Options Array */
    /*-----------------------------------------------------------------------------------*/

    // Set the Options Array
    global $of_options, $smof_details;

    // Background Options
    $of_options[] = array(
      "name"      => __("Background Options", "shoestrap"),
      "type"      => "heading"
    );

    $of_options[] = array(
      "name"      => __("Background Color", "shoestrap"),
      "desc"      => __("Select a background color for your site. Default: #ffffff.", "shoestrap"),
      "id"        => "color_body_bg",
      "std"       => "#ffffff",
      "less"      => true,
      "customizer"=> array(),
      "type"      => "color"
    );

    $of_options[] = array(
      "name"      => "",
      "desc"      => "",
      "id"        => "help4",
      "std"       => "<h3 style=\"margin: 0 0 10px;\">Background Images</h3>
                      <p>If you want a background image, you can select one here.
                      You can either upload a custom image, or use one of our pre-defined image patterns.
                      If you both upload a custom image and select a pattern, your custom image will override the selected pattern.
                      Please note that the image only applies to the area on the right and left of the main content area,
                      to ensure better content readability. You can also set the background position to be fixed or scroll!</p>",
      "icon"      => true,
      "type"      => "info"
    );

    $of_options[] = array(
      "name"      => __("Background position", "shoestrap"),
      "desc"      => __("Changes how the background image or pattern is displayed from scroll to fixed position. Default: Fixed.", "shoestrap"),
      "id"        => "background_fixed_toggle",
      "std"       => 1,
      "on"        => __("Fixed", "shoestrap"),
      "off"       => __("Scroll", "shoestrap"),
      "type"      => "switch"
    );

    $of_options[] = array(
      "name"      => __("Use a Background Image", "shoestrap"),
      "desc"      => __("Enable this option to upload a custom background image for your site. This will override any patterns you may have selected. Default: OFF.", "shoestrap"),
      "id"        => "background_image_toggle",
      "std"       => 0,
      "type"      => "switch"
    );

    $of_options[] = array(
      "name"      => __("Upload a Custom Background Image", "shoestrap"),
      "desc"      => __("Upload a Custom Background image using the media uploader, or define the URL directly.", "shoestrap"),
      "id"        => "background_image",
      "fold"      => "background_image_toggle",
      "std"       => "",
      "type"      => "media",
      "customizer"=> array(),
    );

    $of_options[] = array(
      "name"      => __("Background Image Positioning", "shoestrap"),
      "desc"      => __("Allows the user to modify how the background displays. By default it is full width and stretched to fill the page. Default: Full Width.", "shoestrap"),
      "id"        => "background_image_position_toggle",
      "std"       => 0,
      "fold"      => "background_image_toggle",
      "on"        => __("Custom", "shoestrap"),
      "off"       => __("Full Width", "shoestrap"),
      "type"      => "switch"
    );

    $of_options[] = array(
      "name"      => __("Background Repeat", "shoestrap"),
      "desc"      => __("Select how (or if) the selected background should be tiled. Default: Tile", "shoestrap"),
      "id"        => "background_repeat",
      "fold"      => "background_image_position_toggle",
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
      "id"        => "background_position_x",
      "fold"      => "background_image_position_toggle",
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
      "id"        => "background_pattern_toggle",
      "std"       => 0,
      "type"      => "switch"
    );

    $of_options[] = array(
      "name"      => __("Choose a Background Pattern", "shoestrap"),
      "desc"      => __("Select a background pattern.", "shoestrap"),
      "id"        => "background_pattern",
      "fold"      => "background_pattern_toggle",
      "std"       => "",
      "type"      => "tiles",
      "options"   => $bg_pattern_images,
    );

    do_action( 'shoestrap_module_background_options_modifier' );

    $smof_details = array();
    foreach( $of_options as $option ) {
      $smof_details[$option['id']] = $option;
    }
  }
}
add_action( 'init', 'shoestrap_module_background_options' );
