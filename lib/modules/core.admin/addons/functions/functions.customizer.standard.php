<?php
/*
 *    Adds the SMOF customize support to the customizer!
 */
function smof_customize_register( $wp_customize ) {
  global $smof_data, $of_options, $smof_details;
  $section = array();
  $section_set = true;
  $order = array(
    'heading' => -500,
    'option'  => -500,
  );
  $defaults = array(
    'default-color'          => '',
    'default-image'          => '',
    'wp-head-callback'       => '',
    'admin-head-callback'    => '',
    'admin-preview-callback' => ''
  );

  
  foreach( $of_options as $option ) {


    $customSetting = array(
      'type'          => 'theme_mod',
      'capabilities'  => 'manage_theme_options',
      'default'       =>  $option['std']
    );

    //Change the item priority if not set
    if ( $option['type'] != 'heading' && !isset( $option['priority'] ) ) {
      $option['priority'] = $order['option'];
      $order['option']++;
    }   

    // Add the section
    if ( $section_set == false && is_array( $section ) ) {
      $wp_customize->add_section($section['id'], array(
        'title'       => $section['name'],
        'priority'    => $section['priority'],
        'description' => $section['desc']
      ));
      $section_set = true;
    }

    if ( !array_key_exists("customizer", $smof_details[$option['id']]) ) {
      //echo $option['id'];
      //continue;      
    }

    switch( $option['type'] ) {
      case 'heading':
        // We don't want to put up the section unless it's used by something visible in the customizer
        $section        = $option;
        $section['id']  = strtolower( str_replace( " ", "", $option['name'] ) );
        $section_set    = false;
        $order['option']=-500;
        if (!empty( $option['priority'] ) ) {
          $section['priority'] = $option['priority'];
        } else {
          $section['priority'] = $order['heading'];
          $order['heading']++;          
        }
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

  }
}
add_action( 'customize_register', 'smof_customize_register' );