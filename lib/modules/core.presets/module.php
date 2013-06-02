<?php

/*
 * The presets core options for the Shoestrap theme
 */
if ( !function_exists( 'shoestrap_module_presets_options' ) ) {
  function shoestrap_module_presets_options() {

    //Preset Styles Reader
    $preset_styles_path = get_template_directory() . '/lib/admin/presets';

    $preset_styles_url  = get_bloginfo( 'template_url' ) . '/lib/admin/presets/';
    $preset_styles      = array();

    if ( is_dir( $preset_styles_path ) ) {
      if ( $preset_styles_dir = opendir( $preset_styles_path ) ) {
        while ( ( $preset_styles_file = readdir( $preset_styles_dir ) ) !== false ) {
          if ( stristr( $preset_styles_file, ".txt" ) !== false ) {
            $array    = array();
            $pre      = $preset_styles_url . $preset_styles_file;
            $explode  = explode( "/", $pre );
            $style    = end( $explode );
            $key      = explode( '.', $style );
            $preset_styles[$key[0]]['style'] = $style;
          }
          if( stristr( $preset_styles_file, ".png" ) !== false || stristr( $preset_styles_file, ".jpg" ) !== false) {
            $preview = $preset_styles_url . $preset_styles_file;
            $preview = explode( "/", $preview );
            $preview = end( $preview );

            $key = explode( '.', $preview );
            $preset_styles[$key[0]]['preview'] = $preview;
          }
        }
      }
    }

    /*-----------------------------------------------------------------------------------*/
    /* The Options Array */
    /*-----------------------------------------------------------------------------------*/

    // Set the Options Array
    global $of_options, $smof_details;

    // Presets Styles
    $of_options[] = array(
      "name"      => __("Preset Styles", "shoestrap"),
      "type"      => "heading"
    );

    $of_options[] = array(
      "name"      => __("Choose a Preset", "shoestrap"),
      "desc"      => __("Select a site preset. You can load it in and replace your current styles.", "shoestrap"),
      "id"        => "design_preset",
      "std"       => "",
      "type"      => "presets",
      "options"   => $preset_styles,
    );

    // Backup Options
    $of_options[] = array(
      "name"      => __("Backup Options", "shoestrap"),
      "type"      => "heading"
    );

    $of_options[] = array(
      "name"      => __("Backup and Restore Options", "shoestrap"),
      "id"        => "of_backup",
      "std"       => "",
      "type"      => "backup",
      "desc"      => __('You can use the two buttons below to backup your current options, and then restore it back at a later time. This is useful if you want to experiment on the options but would like to keep the old settings in case you need it back.', "shoestrap"),
    );

    $of_options[] = array(
      "name"      => __("Transfer Theme Options Data", "shoestrap"),
      "id"        => "of_transfer",
      "std"       => "",
      "type"      => "transfer",
      "desc"      => __('You can tranfer the saved options data between different installs by copying the text inside the text box. To import data from another install, replace the data in the text box with the one from another install and click "Import Options".', "shoestrap"),
    );

    do_action( 'shoestrap_module_presets_options_modifier' );

    $smof_details = array();
    foreach( $of_options as $option ) {
      $smof_details[$option['id']] = $option;
    }
  }
}
add_action( 'init','shoestrap_module_presets_options' );
