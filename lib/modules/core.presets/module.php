<?php

/*
 * The presets core options for the Shoestrap theme
 */
if ( !function_exists( 'shoestrap_module_presets_options' ) ) :
function shoestrap_module_presets_options() {

  //Preset Styles Reader
  $preset_styles_path = get_template_directory() . '/lib/admin/presets';

  $preset_styles_url  = get_template_directory_uri() . '/lib/admin/presets/';
  $preset_styles      = array();

  if ( is_dir( $preset_styles_path ) ) :
    if ( $preset_styles_dir = opendir( $preset_styles_path ) ) :
      while ( ( $preset_styles_file = readdir( $preset_styles_dir ) ) !== false ) :
        if ( stristr( $preset_styles_file, ".txt" ) !== false ) :
          $array    = array();
          $pre      = $preset_styles_url . $preset_styles_file;
          $explode  = explode( "/", $pre );
          $style    = end( $explode );
          $key      = explode( '.', $style );
          $preset_styles[$key[0]]['style'] = $style;
        endif;

        if ( stristr( $preset_styles_file, ".png" ) !== false || stristr( $preset_styles_file, '.jpg' ) !== false) :
          $preview = $preset_styles_url . $preset_styles_file;
          $preview = explode( '/', $preview );
          $preview = end( $preview );

          $key = explode( '.', $preview );
          $preset_styles[$key[0]]['preview'] = $preview;
        endif;
      endwhile;
    endif;
  endif;

  /*-----------------------------------------------------------------------------------*/
  /* The Options Array */
  /*-----------------------------------------------------------------------------------*/

  // Set the Options Array
  global $of_options, $redux;

  // Presets Styles
  $of_options[] = array(
    'name'      => __( 'Preset Styles', 'shoestrap' ),
    'type'      => 'heading'
  );

  $of_options[] = array(
    'name'      => __( 'Choose a Preset', 'shoestrap' ),
    'desc'      => __( 'Select a site preset. You can load it in and replace your current styles.', 'shoestrap' ),
    'id'        => 'design_preset',
    'std'       => '',
    'type'      => 'presets',
    'options'   => $preset_styles,
  );

  do_action( 'shoestrap_module_presets_options_modifier' );

  $redux = array();

  foreach( $of_options as $option ) :
    if ( isset( $option['id'] ) ) :
      $redux[$option['id']] = $option;
    endif;
  endforeach;
}
endif;