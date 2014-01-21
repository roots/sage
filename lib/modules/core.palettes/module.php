<?php

// shoestrap_redux_init();
/*
 * The page core options for the Shoestrap theme
 */
if ( !function_exists( 'shoestrap_module_coulourlovers_options' ) ) :
function shoestrap_module_coulourlovers_options( $sections ) {

  $variables_array = array(
    'color_brand_primary' => __( 'Branding Primary', 'shoestrap' ),
    'color_brand_success' => __( 'Branding Success', 'shoestrap' ),
    'color_brand_warning' => __( 'Branding Warning', 'shoestrap' ),
    'color_brand_danger'  => __( 'Branding Danger', 'shoestrap' ),
    'color_brand_info'    => __( 'Branding Info', 'shoestrap' ),
    'navbar_bg'           => __( 'Navbar Background', 'shoestrap' ),
    'html_color_bg'       => __( 'General Background', 'shoestrap' ),
    'color_body_bg'       => __( 'Body Background Color', 'shoestrap' ),
    'font_base'           => __( 'Text color', 'shoestrap' ),
    'footer_background'   => __( 'Footer Background', 'shoestrap' ),
    'footer_color'        => __( 'Footer text color', 'shoestrap' )
  );

  // Page Options
  $section = array(
    'title' => __( 'Palettes', 'shoestrap' ),
    'icon' => 'el-icon-file icon-large',
  );


  /**
  This will calculate the values of many Bootstrap variables based on a 5-color palette.
  - Background: The lightest and less intense color.
  - Text: The darkest and less intense color. If the difference in brightess between the text and background is not big enough, then pick another color.
  - Primary branding: The most intense color
  - Navbar background: the 3rd color in intensity
  - Navbar text: The color that has the most difference with the background in brightess.
  **/

  // XML copied from http://www.colourlovers.com/api/palettes/top?numResults=100
  $xml_url   = get_template_directory_uri() . '/lib/modules/core.palettes/top.xml';
  $feed_xml  = simplexml_load_file( $xml_url );
  $settings  = get_option( 'shoestrap' );
  $font_base = $settings[ 'font_base' ];

  // if ( isset( $settings[ 'palette_first_color' ] ) )
  //   $palette_first_color  = $settings[ 'palette_first_color' ];
  // if ( isset( $settings[ 'palette_second_color' ] ) )
  //   $palette_second_color = $settings[ 'palette_second_color' ];
  // if ( isset( $settings[ 'palette_third_color' ] ) )
  //   $palette_third_color  = $settings[ 'palette_third_color' ];
  // if ( isset( $settings[ 'palette_fourth_color' ] ) )
  //   $palette_fourth_color = $settings[ 'palette_fourth_color' ];
  // if ( isset( $settings[ 'palette_fifth_color' ] ) )
  //   $palette_fifth_color  = $settings[ 'palette_fifth_color' ];

  // if ( isset( $palette_first_color ) )
  //   foreach ( $palette_first_color as $key => $value ) {
  //     $variable_name = $value . '_override';
  //     $$variable_name = 1;
  //   }

  // if ( isset( $palette_second_color ) )
  //   foreach ( $palette_second_color as $key => $value ) {
  //     $variable_name = $value . '_override';
  //     $$variable_name = 2;
  //   }

  // if ( isset( $palette_third_color ) )
  //   foreach ( $palette_third_color as $key => $value ) {
  //     $variable_name = $value . '_override';
  //     $$variable_name = 3;
  //   }

  // if ( isset( $palette_fourth_color ) )
  //   foreach ( $palette_fourth_color as $key => $value ) {
  //     $variable_name = $value . '_override';
  //     $$variable_name = 4;
  //   }

  // if ( isset( $palette_fifth_color ) )
  //   foreach ( $palette_fifth_color as $key => $value ) {
  //     $variable_name = $value . '_override';
  //     $$variable_name = 5;
  //   }

  foreach($feed_xml->palette as $result) {
    $id       = $result->id;
    $content  = $result->content;
    $title    = $result->title;
    $badgeurl = $result->badgeUrl;
    $imageurl = $result->imageUrl;
    $colors   = array(
      0 => $result->colors->hex[0],
      1 => $result->colors->hex[1],
      2 => $result->colors->hex[2],
      3 => $result->colors->hex[3],
      4 => $result->colors->hex[4]
    );

    // if ( isset( $color_brand_primary_override ) ) {
    //   if ( $color_brand_primary_override == 1 )
    //     $color_brand_primary_override = $colors[0];
    //   if ( $color_brand_primary_override == 2 )
    //     $color_brand_primary_override = $colors[1];
    //   if ( $color_brand_primary_override == 3 )
    //     $color_brand_primary_override = $colors[2];
    //   if ( $color_brand_primary_override == 4 )
    //     $color_brand_primary_override = $colors[3];
    //   if ( $color_brand_primary_override == 5 )
    //     $color_brand_primary_override = $colors[4];
    // }

    // if ( isset( $color_brand_success_override ) ) {
    //   if ( $color_brand_success_override == 1 )
    //     $color_brand_success_override = $colors[0];
    //   if ( $color_brand_success_override == 2 )
    //     $color_brand_success_override = $colors[1];
    //   if ( $color_brand_success_override == 3 )
    //     $color_brand_success_override = $colors[2];
    //   if ( $color_brand_success_override == 4 )
    //     $color_brand_success_override = $colors[3];
    //   if ( $color_brand_success_override == 5 )
    //     $color_brand_success_override = $colors[4];
    // }

    // if ( isset( $color_brand_warning_override ) ) {
    //   if ( $color_brand_warning_override == 1 )
    //     $color_brand_warning_override = $colors[0];
    //   if ( $color_brand_warning_override == 2 )
    //     $color_brand_warning_override = $colors[1];
    //   if ( $color_brand_warning_override == 3 )
    //     $color_brand_warning_override = $colors[2];
    //   if ( $color_brand_warning_override == 4 )
    //     $color_brand_warning_override = $colors[3];
    //   if ( $color_brand_warning_override == 5 )
    //     $color_brand_warning_override = $colors[4];
    // }

    // if ( isset( $color_brand_danger_override ) ) {
    //   if ( $color_brand_danger_override == 1 )
    //     $color_brand_danger_override = $colors[0];
    //   if ( $color_brand_danger_override == 2 )
    //     $color_brand_danger_override = $colors[1];
    //   if ( $color_brand_danger_override == 3 )
    //     $color_brand_danger_override = $colors[2];
    //   if ( $color_brand_danger_override == 4 )
    //     $color_brand_danger_override = $colors[3];
    //   if ( $color_brand_danger_override == 5 )
    //     $color_brand_danger_override = $colors[4];
    // }

    // if ( isset( $color_brand_info_override ) ) {
    //   if ( $color_brand_info_override == 1 )
    //     $color_brand_info_override = $colors[0];
    //   if ( $color_brand_info_override == 2 )
    //     $color_brand_info_override = $colors[1];
    //   if ( $color_brand_info_override == 3 )
    //     $color_brand_info_override = $colors[2];
    //   if ( $color_brand_info_override == 4 )
    //     $color_brand_info_override = $colors[3];
    //   if ( $color_brand_info_override == 5 )
    //     $color_brand_info_override = $colors[4];
    // }

    // if ( isset( $navbar_bg_override ) ) {
    //   if ( $navbar_bg_override == 1 )
    //     $navbar_bg_override = $colors[0];
    //   if ( $navbar_bg_override == 2 )
    //     $navbar_bg_override = $colors[1];
    //   if ( $navbar_bg_override == 3 )
    //     $navbar_bg_override = $colors[2];
    //   if ( $navbar_bg_override == 4 )
    //     $navbar_bg_override = $colors[3];
    //   if ( $navbar_bg_override == 5 )
    //     $navbar_bg_override = $colors[4];
    // }

    // if ( isset( $html_color_bg_override ) ) {
    //   if ( $html_color_bg_override == 1 )
    //     $html_color_bg_override = $colors[0];
    //   if ( $html_color_bg_override == 2 )
    //     $html_color_bg_override = $colors[1];
    //   if ( $html_color_bg_override == 3 )
    //     $html_color_bg_override = $colors[2];
    //   if ( $html_color_bg_override == 4 )
    //     $html_color_bg_override = $colors[3];
    //   if ( $html_color_bg_override == 5 )
    //     $html_color_bg_override = $colors[4];
    // }

    // if ( isset( $color_body_bg_override ) ) {
    //   if ( $color_body_bg_override == 1 )
    //     $color_body_bg_override = $colors[0];
    //   if ( $color_body_bg_override == 2 )
    //     $color_body_bg_override = $colors[1];
    //   if ( $color_body_bg_override == 3 )
    //     $color_body_bg_override = $colors[2];
    //   if ( $color_body_bg_override == 4 )
    //     $color_body_bg_override = $colors[3];
    //   if ( $color_body_bg_override == 5 )
    //     $color_body_bg_override = $colors[4];
    // }

    // if ( isset( $font_base_override ) ) {
    //   if ( $font_base_override == 1 )
    //     $font_base_override = $colors[0];
    //   if ( $font_base_override == 2 )
    //     $font_base_override = $colors[1];
    //   if ( $font_base_override == 3 )
    //     $font_base_override = $colors[2];
    //   if ( $font_base_override == 4 )
    //     $font_base_override = $colors[3];
    //   if ( $font_base_override == 5 )
    //     $font_base_override = $colors[4];
    // }

    // if ( isset( $footer_background_override ) ) {
    //   if ( $footer_background_override == 1 )
    //     $footer_background_override = $colors[0];
    //   if ( $footer_background_override == 2 )
    //     $footer_background_override = $colors[1];
    //   if ( $footer_background_override == 3 )
    //     $footer_background_override = $colors[2];
    //   if ( $footer_background_override == 4 )
    //     $footer_background_override = $colors[3];
    //   if ( $footer_background_override == 5 )
    //     $footer_background_override = $colors[4];
    // }

    // if ( isset( $footer_color_override ) ) {
    //   if ( $footer_color_override == 1 )
    //     $footer_color_override = $colors[0];
    //   if ( $footer_color_override == 2 )
    //     $footer_color_override = $colors[1];
    //   if ( $footer_color_override == 3 )
    //     $footer_color_override = $colors[2];
    //   if ( $footer_color_override == 4 )
    //     $footer_color_override = $colors[3];
    //   if ( $footer_color_override == 5 )
    //     $footer_color_override = $colors[4];
    // }

    // Get the ligtness of all the colors in our palette and arrange them according to it.
    $colors_array_0b = $colors;
    $brightest_0_key = shoestrap_brightest_color( $colors_array_0b, 'key' );
    $brightest_0_val = shoestrap_brightest_color( $colors_array_0b, 'value' );

    $colors_array_1b = shoestrap_array_delete( $brightest_0_key, $colors_array_0b );
    $brightest_1_key = shoestrap_brightest_color( $colors_array_1b, 'key' );
    $brightest_1_val = shoestrap_brightest_color( $colors_array_1b, 'value' );

    $colors_array_2b = shoestrap_array_delete( $brightest_1_key, $colors_array_1b );
    $brightest_2_key = shoestrap_brightest_color( $colors_array_2b, 'key' );
    $brightest_2_val = shoestrap_brightest_color( $colors_array_2b, 'value' );

    $colors_array_3b = shoestrap_array_delete( $brightest_2_key, $colors_array_2b );
    $brightest_3_key = shoestrap_brightest_color( $colors_array_3b, 'key' );
    $brightest_3_val = shoestrap_brightest_color( $colors_array_3b, 'value' );

    $colors_array_4b = shoestrap_array_delete( $brightest_3_key, $colors_array_3b );
    $brightest_4_key = shoestrap_brightest_color( $colors_array_4b, 'key' );
    $brightest_4_val = shoestrap_brightest_color( $colors_array_4b, 'value' );


    // Get the saturation of all the colors in our palette and arrange them according to it.
    $colors_array_0s      = $colors;
    $most_saturated_0_key = shoestrap_most_saturated_color( $colors_array_0s, 'key' );
    $most_saturated_0_val = shoestrap_most_saturated_color( $colors_array_0s, 'value' );

    $colors_array_1s      = shoestrap_array_delete( $most_saturated_0_key, $colors_array_0s );
    $most_saturated_1_key = shoestrap_most_saturated_color( $colors_array_1s, 'key' );
    $most_saturated_1_val = shoestrap_most_saturated_color( $colors_array_1s, 'value' );

    $colors_array_2s      = shoestrap_array_delete( $most_saturated_1_key, $colors_array_1s );
    $most_saturated_2_key = shoestrap_most_saturated_color( $colors_array_2s, 'key' );
    $most_saturated_2_val = shoestrap_most_saturated_color( $colors_array_2s, 'value' );

    $colors_array_3s      = shoestrap_array_delete( $most_saturated_2_key, $colors_array_2s );
    $most_saturated_3_key = shoestrap_most_saturated_color( $colors_array_3s, 'key' );
    $most_saturated_3_val = shoestrap_most_saturated_color( $colors_array_3s, 'value' );

    $colors_array_4s      = shoestrap_array_delete( $most_saturated_3_key, $colors_array_3s );
    $most_saturated_3_key = shoestrap_most_saturated_color( $colors_array_4s, 'key' );
    $most_saturated_4_val = shoestrap_most_saturated_color( $colors_array_4s, 'value' );


    // Get the intensity of all the colors in our palette and arrange them according to it.
    $colors_array_0i    = $colors;
    $most_intense_0_key = shoestrap_most_intense_color( $colors_array_0i, 'key' );
    $most_intense_0_val = shoestrap_most_intense_color( $colors_array_0i, 'value' );

    $colors_array_1i    = shoestrap_array_delete( $most_intense_0_key, $colors_array_0i );
    $most_intense_1_key = shoestrap_most_intense_color( $colors_array_1i, 'key' );
    $most_intense_1_val = shoestrap_most_intense_color( $colors_array_1i, 'value' );

    $colors_array_2i    = shoestrap_array_delete( $most_intense_1_key, $colors_array_1i );
    $most_intense_2_key = shoestrap_most_intense_color( $colors_array_2i, 'key' );
    $most_intense_2_val = shoestrap_most_intense_color( $colors_array_2i, 'value' );

    $colors_array_3i    = shoestrap_array_delete( $most_intense_2_key, $colors_array_2i );
    $most_intense_3_key = shoestrap_most_intense_color( $colors_array_3i, 'key' );
    $most_intense_3_val = shoestrap_most_intense_color( $colors_array_3i, 'value' );

    $colors_array_4i    = shoestrap_array_delete( $most_intense_3_key, $colors_array_3i );
    $most_intense_3_key = shoestrap_most_intense_color( $colors_array_4i, 'key' );
    $most_intense_4_val = shoestrap_most_intense_color( $colors_array_4i, 'value' );


    // Get the lightness and "dullness" of all the colors in our palette and arrange them according to it.
    $colors_array_0d   = $colors;
    $bright_dull_0_key = shoestrap_brightest_dull_color( $colors_array_0d, 'key' );
    $bright_dull_0_val = shoestrap_brightest_dull_color( $colors_array_0d, 'value' );

    $colors_array_1d   = shoestrap_array_delete( $bright_dull_0_key, $colors_array_0d );
    $bright_dull_1_key = shoestrap_brightest_dull_color( $colors_array_1d, 'key' );
    $bright_dull_1_val = shoestrap_brightest_dull_color( $colors_array_1d, 'value' );

    $colors_array_2d   = shoestrap_array_delete( $bright_dull_1_key, $colors_array_1d );
    $bright_dull_2_key = shoestrap_brightest_dull_color( $colors_array_2d, 'key' );
    $bright_dull_2_val = shoestrap_brightest_dull_color( $colors_array_2d, 'value' );

    $colors_array_3d   = shoestrap_array_delete( $bright_dull_2_key, $colors_array_2d );
    $bright_dull_3_key = shoestrap_brightest_dull_color( $colors_array_3d, 'key' );
    $bright_dull_3_val = shoestrap_brightest_dull_color( $colors_array_3d, 'value' );

    $colors_array_4d   = shoestrap_array_delete( $bright_dull_3_key, $colors_array_3d );
    $bright_dull_3_key = shoestrap_brightest_dull_color( $colors_array_4d, 'key' );
    $bright_dull_4_val = shoestrap_brightest_dull_color( $colors_array_4d, 'value' );

    // The Background color.
    // If all colors are dark-ish, then assume a dark palette and make background the darkest instead of the lightest color.
    $background = $bright_dull_0_val;

    // Only display suitable templates
    $display = ( shoestrap_get_brightness( $bright_dull_0_val ) > 150 ) ? true : false;
    if ( $display )
      $display = ( shoestrap_get_brightness( $bright_dull_4_val ) < 100 ) ? true : false;

    // The text color
    if ( shoestrap_get_brightness( $background ) - shoestrap_get_brightness( $brightest_4_val ) > 100 ) {
      // If the brightest and darkest colors in the palette have a big difference, then use the darkest color as text.
      $text_color = $brightest_4_val;
    } else {
      // Are all colors dark-ish?
      // If they are, then use white text.
      if ( shoestrap_get_brightness( $background ) < 100 ) { 
        $text_color = '#ffffff';
      } else {
        if ( shoestrap_get_brightness( $background ) - shoestrap_get_brightness( '#222222' ) > 200 ) {
          $text_color = '#222222';
        } else {
          $text_color = '#000000';
        }
      }
    }
    // Bypass the font color
    $font_base['color'] = $text_color;

    $color_brand_primary = ( $text_color == $most_intense_1_val ) ? $most_intense_2_val : $most_intense_3_val;
    if ( ( shoestrap_get_brightness( $background ) - shoestrap_get_brightness( $color_brand_primary ) ) < 60 ) {
      if ( ( shoestrap_get_brightness( $background ) - shoestrap_get_brightness( $brightest_2_val ) ) > 60 )
        $color_brand_primary = $brightest_2_val;
      else
        $color_brand_primary = $brightest_3_val;
    }

    $navbar_bg = ( $bright_dull_3_val == $background ) ? $bright_dull_2_val : $bright_dull_3_val;

    $footer_color = ( $background == $bright_dull_0_val || $background == $bright_dull_1_val ) ? $bright_dull_4_val : $bright_dull_0_val;
    if ( $background == $bright_dull_0_val )
      $footer_background  = $bright_dull_1_val;
    elseif ( $background == $bright_dull_1_val )
      $footer_background  = $bright_dull_2_val;
    elseif ( $background == $bright_dull_2_val )
      $footer_background  = $bright_dull_3_val;
    elseif ( $background == $bright_dull_3_val )
      $footer_background  = $bright_dull_0_val;
    else
      $footer_background  = $bright_dull_0_val;

    $color_brand_primary = ( isset( $color_brand_primary_override ) ) ? $color_brand_primary_override : $color_brand_primary;
    $color_brand_success = ( isset( $color_brand_success_override ) ) ? $color_brand_success_override : $most_saturated_1_val;
    $color_brand_warning = ( isset( $color_brand_warning_override ) ) ? $color_brand_warning_override : $most_saturated_2_val;
    $color_brand_danger  = ( isset( $color_brand_danger_override ) )  ? $color_brand_danger_override  : $most_saturated_1_val;
    $color_brand_info    = ( isset( $color_brand_info_override ) )    ? $color_brand_info_override    : $most_saturated_2_val;
    $navbar_bg           = ( isset( $navbar_bg_override ) )           ? $navbar_bg_override           : $navbar_bg;
    $html_color_bg       = ( isset( $html_color_bg_override ) )       ? $html_color_bg_override       : $background;
    $color_body_bg       = ( isset( $color_body_bg_override ) )       ? $color_body_bg_override       : $background;
    $font_base['color']  = ( isset( $font_base_override ) )           ? $font_base_override           : $font_base['color'];
    $footer_background   = ( isset( $footer_background_override ) )   ? $footer_background_override   : $footer_background;
    $footer_color        = ( isset( $footer_color_override ) )        ? $footer_color_override        : $footer_color;


    if ( $display ) {
      $preset_options[] = array(
        'alt'     => $title,
        'img'     => $imageurl,
        'presets' => array(
          'color_brand_primary' => '#' . str_replace( '#', '', $color_brand_primary ),
          'color_brand_success' => '#' . str_replace( '#', '', $color_brand_success ),
          'color_brand_warning' => '#' . str_replace( '#', '', $color_brand_warning ),
          'color_brand_danger'  => '#' . str_replace( '#', '', $color_brand_danger ),
          'color_brand_info'    => '#' . str_replace( '#', '', $color_brand_info ),
          'navbar_bg'           => '#' . str_replace( '#', '', $navbar_bg ),
          'html_color_bg'       => '#' . str_replace( '#', '', $html_color_bg ),
          'color_body_bg'       => '#' . str_replace( '#', '', $color_body_bg ),
          'font_base'           => $font_base,
          'footer_background'   => '#' . str_replace( '#', '', $footer_background ),
          'footer_color'        => '#' . str_replace( '#', '', $footer_color ),
        )
      );
    }
  }

  $fields[] = array(
      'id'    => 'palettes_info_warning',
      'type'  => 'info',
      'style' => 'warning',
      'icon'  => 'el-icon-exclamation-sign',
      'desc'  => __( 'Warning: This feature is HIGHLY experimental, use at your own risk. Palettes taken from <a href="http://colourlovers.com">Colourlovers.</a>', 'shoestrap' )
  );

  // $fields[] = array(
  //   'id'       => 'palette_first_color',
  //   'type'     => 'select',
  //   'multi'    => true,
  //   'title'    => __( '1st Color', 'shoestrap' ), 
  //   'subtitle' => __(  'Assign color to these variables', 'shoestrap' ),
  //   'desc'     => '',
  //   'options'  => $variables_array
  // );

  // $fields[] = array(
  //   'id'       => 'palette_second_color',
  //   'type'     => 'select',
  //   'multi'    => true,
  //   'title'    => __( '2nd Color', 'shoestrap' ), 
  //   'subtitle' => __(  'Assign color to these variables', 'shoestrap' ),
  //   'desc'     => '',
  //   'options'  => $variables_array
  // );

  // $fields[] = array(
  //   'id'       => 'palette_third_color',
  //   'type'     => 'select',
  //   'multi'    => true,
  //   'title'    => __( '3rd Color', 'shoestrap' ), 
  //   'subtitle' => __(  'Assign color to these variables', 'shoestrap' ),
  //   'desc'     => '',
  //   'options'  => $variables_array
  // );

  // $fields[] = array(
  //   'id'       => 'palette_fourth_color',
  //   'type'     => 'select',
  //   'multi'    => true,
  //   'title'    => __( '4th Color', 'shoestrap' ), 
  //   'subtitle' => __(  'Assign color to these variables', 'shoestrap' ),
  //   'desc'     => '',
  //   'options'  => $variables_array
  // );

  // $fields[] = array(
  //   'id'       => 'palette_fifth_color',
  //   'type'     => 'select',
  //   'multi'    => true,
  //   'title'    => __( '5th Color', 'shoestrap' ), 
  //   'subtitle' => __(  'Assign color to these variables', 'shoestrap' ),
  //   'desc'     => '',
  //   'options'  => $variables_array
  // );

  $fields[] = array(
    'id'      =>'palette_presets',
    'type'    => 'image_select', 
    'presets' => true,
    'title'   => __('Palette', 'redux-framework'),
    'subtitle'=> __('Choose a palette. We will try to calculate the correct position of each color and assign them to their appropriate variables.', 'shoestrap'),
    'default' => 0,
    'desc'    => __('Choose a palette. We will try to calculate the correct position of each color and assign them to their appropriate variables.', 'shoestrap'),
    'options' => $preset_options
  );

  $section['fields'] = $fields;

  $section = apply_filters( 'shoestrap_module_coulourlovers_options_modifier', $section );
  
  $sections[] = $section;
  
  return $sections;

}
endif;

add_filter( 'redux/options/'.REDUX_OPT_NAME.'/sections', 'shoestrap_module_coulourlovers_options', 200 );
