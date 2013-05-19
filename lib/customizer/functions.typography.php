<?php

/*
 * Creates the section, settings and the controls for the customizer
 */
function shoestrap_typography_customizer( $wp_customize ) {

  // Dropdown (Select) Controls
  $select_controls = array();

  // Text Controls
  $text_controls = array();

  foreach ( $select_controls as $control ) {
    $wp_customize->add_control( $control['setting'], array(
      'label'       => $control['label'],
      'section'     => $control['section'],
      'settings'    => $control['setting'],
      'type'        => 'select',
      'priority'    => $control['priority'],
      'choices'     => $control['choises']
    ));
  }

  foreach ( $text_controls as $control) {
    $wp_customize->add_control( $control['setting'], array(
      'label'       => $control['label'],
      'section'     => $control['section'],
      'settings'    => $control['setting'],
      'type'        => 'text',
      'priority'    => $control['priority']
    ));
  }

  // Content of the Google Font
  $wp_customize->add_control( new Shoestrap_Google_WebFont_Control( $wp_customize, 'typography_google_webfont', array(
    'label'       => 'Google Webfont',
    'section'     => 'shoestrap_typography',
    'settings'    => 'typography_google_webfont',
    'priority'    => 3,
  )));

  //if ( $wp_customize->is_preview() && ! is_admin() )
    //add_action( 'wp_footer', 'shoestrap_customizer_typography_preview', 21 );
}
add_action( 'customize_register', 'shoestrap_typography_customizer' );



/**
 * Used by shoestrap_typography_customizer
 *
 * Adds extra javascript actions to the theme customizer editor
 */
function shoestrap_customizer_typography_controls()
{
  wp_register_script('theme_customizer', get_template_directory_uri() . '/lib/customizer/typography/scripts-customizer.js', false, null, true);
  wp_enqueue_script('theme_customizer');
}
add_action( 'customize_controls_init', 'shoestrap_customizer_typography_controls' );


/**
 * Used by shoestrap_typography_customizer
 *
 * Bind JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function shoestrap_customizer_typography_preview()
{
  wp_register_script('theme_customizer', get_template_directory_uri() . '/lib/customizer/typography/scripts-preview.js', false, null, true);
  wp_enqueue_script('theme_customizer');
}
add_action( 'customize_preview_init', 'shoestrap_customizer_typography_preview' );
