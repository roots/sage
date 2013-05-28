<?php
/*
 *    Adds the SMOF customize support to the customizer!
 */
function smof_customize_register( $wp_customize ) {
  global $smof_data, $of_options, $smof_details;
  $section = array();
  $section_set = true;
  $order = array(
    'heading' => -100,
    'option'  => -100,
  );
  $defaults = array(
    'default-color'          => '',
    'default-image'          => '',
    'wp-head-callback'       => '',
    'admin-head-callback'    => '',
    'admin-preview-callback' => ''
  );
  add_theme_support( 'custom-background', $defaults );
  foreach( $of_options as $option ) {
    $smof_details[$option['id']] = $option;

    $customSetting = array(
      'type'          => 'theme_mod',
      'capabilities'  => 'manage_theme_options',
      'default'       =>  $option['std']
    );

    if ( $section_set == false && is_array( $section ) ) {
      if ( !isset($section['priority'] ) )
        $section['priority'] = $order['heading'];

      $wp_customize->add_section($section['id'], array(
        'title'       => $section['name'],
        'priority'    => $section['priority'],
        'description' => $section['desc']
      ));
      $section_set = true;
    }

    if ( $option['type'] != 'heading' && !isset( $option['priority'] ) )
      $option['priority'] = $order['option'];

    switch( $option['type'] ) {
      case 'heading':
        // We don't want to put up the section unless it's used by something visible in the customizer
        $section        = $option;
        $section['id']  = strtolower( str_replace( " ", "", $option['name'] ) );
        $section_set    = false;
        $order          = array(
          'option'      => -100,
        );
        $order['heading']++;
        break;

      case 'text':
        $wp_customize->add_setting( $option['id'], $customSetting);
        $wp_customize->add_control( $option['id'], array(
          'label'   => $option['name'],
          'section' => $section['id'],
          'settings'=> $option['id'],
          'priority'=> $option['priority'],
          'type'    => 'text',
        ) );
        break;

      case 'select':
        $wp_customize->add_setting( $option['id'], $customSetting);
        $wp_customize->add_control( $option['id'], array(
          'label'   => $option['name'],
          'section' => $section['id'],
          'settings'=> $option['id'],
          'priority'=> $option['priority'],
          'type'    => 'select',
          'choices' => $option['options']
        ) );
        break;

      case 'radio':
        $wp_customize->add_setting( $option['id'], $customSetting);
        $wp_customize->add_control( $option['id'], array(
          'label'   => $option['name'],
          'section' => $section['id'],
          'settings'=> $option['id'],
          'priority'=> $option['priority'],
          'type'    => 'radio',
          'choices' => $option['options']
        ) );
        break;

      case 'checkbox':
        $wp_customize->add_setting( $option['id'], $customSetting);
        $wp_customize->add_control( $option['id'], array(
          'label'   => $option['name'],
          'section' => $section['id'],
          'settings'=> $option['id'],
          'priority'=> $option['priority'],
          'type'    => 'checkbox',
        ) );
        break;

      case 'upload':
        $wp_customize->add_setting( $option['id'], $customSetting);
        $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, $option['id'], array(
          'label'   => $option['name'],
          'section' => $section['id'],
          'settings'=> $option['id'],
          'priority'=> $option['priority']
        ) ) );
        break;

      case 'media':
        $wp_customize->add_setting( $option['id'], $customSetting);
        $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, $option['id'], array(
          'label'   => $option['name'],
          'section' => $section['id'],
          'settings'=> $option['id'],
          'priority'=> $option['priority']
        ) ) );
        break;

      case 'color':
        $wp_customize->add_setting( $option['id'], $customSetting);
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $option['id'], array(
          'label'   => $option['name'],
          'section' => $section['id'],
          'settings'=> $option['id'],
          'priority'=> $option['priority']
        ) ) );
        break;

      case 'switch':
        $wp_customize->add_setting( $option['id'], $customSetting);
        $wp_customize->add_control( $option['id'], array(
          'label'   => $option['name'],
          'section' => $section['id'],
          'settings'=> $option['id'],
          'priority'=> $option['priority'],
          'type'    => 'checkbox',
        ) );
        break;

      default:
        break;
    }

    if ( $option['type'] != 'heading' ) {
      $order['option']++;
    }
  }
}
add_action( 'customize_register', 'smof_customize_register' );