<?php

function shoestrap_register_controls($wp_customize){
  
/*
 * HEADER AND BRANDING
 */
  $wp_customize->add_control(new WP_Customize_Image_Control(
    $wp_customize,
    'shoestrap_logo_Image',
    array(
      'label'     => __('Logo Image', 'bootstrap_commerce'),
      'section'   => 'shoestrap_header',
      'settings'  => 'shoestrap_logo',
      'priority'  => 1
    )
  ));

  $wp_customize->add_control( new WP_Customize_Color_Control(
    $wp_customize,
    'shoestrap_header_backgroundcolor',
    array(
      'label'     => 'Header Region Background Color',
      'section'   => 'shoestrap_header',
      'settings'  => 'shoestrap_header_backgroundcolor',
      'priority'  => 2
    )
  ));

  $wp_customize->add_control( new WP_Customize_Color_Control(
    $wp_customize,
    'shoestrap_header_textcolor',
    array(
      'label'     => 'Header Text Color',
      'section'   => 'shoestrap_header',
      'settings'  => 'shoestrap_header_textcolor',
      'priority'  => 3
    )
  ));

  // Navbar theme variation (light/dark)
  $wp_customize->add_control( new WP_Customize_Color_Control(
    $wp_customize,
    'shoestrap_navbar_color',
    array(
      'label'     => 'Navbar Color',
      'section'   => 'shoestrap_header',
      'settings'  => 'shoestrap_navbar_color',
      'priority'  => 4
    )
  ));


/*
 * LAYOUT SECTION
 */
  // Sidebar: left/Right/Hidden
  $wp_customize->add_control( 'shoestrap_aside_layout', array(
    'label'       => __( 'Sidebar', 'bootstrap_commerce' ),
    'section'     => 'shoestrap_layout',
    'settings'    => 'shoestrap_aside_layout',
    'type'        => 'select',
    'priority'    => 1,
    'choices'     => array(
      'right'     => __('Right', 'bootstrap_commerce'),
      'left'      => __('Left', 'bootstrap_commerce'),
      'hide'      => __('Hide', 'bootstrap_commerce'),
    ),
  ));
  
/*
 * TYPOGRAPHY SECTION
 */
  $wp_customize->add_control( 'shoestrap_google_webfonts', array(
    'label'       => __( 'Google Webfont Name', 'bootstrap_commerce' ),
    'section'     => 'shoestrap_typography',
    'settings'    => 'shoestrap_google_webfonts',
    'type'        => 'text',
    'priority'    => 2,
  ));

  
/*
 * GENERAL COLORS AND BACKGROUND SECTION
 */
  //Text variation (light/dark)
  $wp_customize->add_control( 'shoestrap_text_variation', array(
    'label'       => __( 'Text Variation', 'bootstrap_commerce' ),
    'section'     => 'colors',
    'settings'    => 'shoestrap_text_variation',
    'type'        => 'select',
    'priority'    => 1,
    'choices'     => array(
      'dark'      => __('Dark', 'bootstrap_commerce'),
      'light'     => __('Light', 'bootstrap_commerce'),
    ),
  ));

  $wp_customize->add_control( new WP_Customize_Color_Control(
    $wp_customize,
    'shoestrap_link_color',
    array(
      'label'     => 'Links Color',
      'section'   => 'colors',
      'settings'  => 'shoestrap_link_color',
      'priority'  => 2
    )
  ));

  $wp_customize->add_control( new WP_Customize_Color_Control(
    $wp_customize,
    'shoestrap_buttons_color',
    array(
      'label'     => 'Buttons Color',
      'section'   => 'colors',
      'settings'  => 'shoestrap_buttons_color',
      'priority'  => 3
    )
  ));

/*
 * HERO SECTION
 */
  // Hero region title
  $wp_customize->add_control( 'shoestrap_hero_title', array(
    'label'       => __( 'Title', 'bootstrap_commerce' ),
    'section'     => 'shoestrap_hero',
    'settings'    => 'shoestrap_hero_title',
    'type'        => 'text',
    'priority'    => 1
  ));
  
  // Hero Region content
  $wp_customize->add_control( 'shoestrap_hero_content', array(
    'label'       => __( 'Content', 'bootstrap_commerce' ),
    'section'     => 'shoestrap_hero',
    'settings'    => 'shoestrap_hero_content',
    'type'        => 'text',
    'priority'    => 2
  ));
  
  // Hero Region Call to action button link
  $wp_customize->add_control( 'shoestrap_hero_cta_text', array(
    'label'       => __( 'Call To Action Button Text', 'bootstrap_commerce' ),
    'section'     => 'shoestrap_hero',
    'settings'    => 'shoestrap_hero_cta_text',
    'type'        => 'text',
    'priority'    => 3
  ));
  
  // Hero Region Call to action button link
  $wp_customize->add_control( 'shoestrap_hero_cta_link', array(
    'label'       => __( 'Call To Action Button Link', 'bootstrap_commerce' ),
    'section'     => 'shoestrap_hero',
    'settings'    => 'shoestrap_hero_cta_link',
    'type'        => 'text',
    'priority'    => 4
  ));
  
  // Call to action button color
  $wp_customize->add_control( 'shoestrap_hero_cta_color', array(
    'label'       => __( 'Call To Action Button Color', 'bootstrap_commerce' ),
    'section'     => 'shoestrap_hero',
    'settings'    => 'shoestrap_hero_cta_color',
    'type'        => 'select',
    'priority'    => 5,
    'choices'     => array(
      'default'   => __('White', 'bootstrap_commerce'),
      'primary'   => __('Blue', 'bootstrap_commerce'),
      'info'      => __('Light Blue', 'bootstrap_commerce'),
      'success'   => __('Green', 'bootstrap_commerce'),
      'warning'   => __('Orange', 'bootstrap_commerce'),
      'danger'    => __('Red', 'bootstrap_commerce'),
      'inverse'   => __('Black', 'bootstrap_commerce'),
    ),
  ));

  // Hero region background image
  $wp_customize->add_control(new WP_Customize_Image_Control(
    $wp_customize,
    'hero_background',
    array(
      'label'     => __('Background', 'bootstrap_commerce'),
      'section'   => 'shoestrap_hero',
      'settings'  => 'shoestrap_hero_background',
      'priority'  => 6
    )
  ));
  
  // Hero region background color
  $wp_customize->add_control( new WP_Customize_Color_Control(
    $wp_customize,
    'shoestrap_hero_background_color',
    array(
      'label'     => 'Hero Region Background Color',
      'section'   => 'shoestrap_hero',
      'settings'  => 'shoestrap_hero_background_color',
      'priority'  => 7
    )
  ));
  
 // region textcolor
  $wp_customize->add_control( new WP_Customize_Color_Control(
    $wp_customize,
    'shoestrap_hero_textcolor',
    array(
      'label'     => 'Hero Region Text Color',
      'section'   => 'shoestrap_hero',
      'settings'  => 'shoestrap_hero_textcolor',
      'priority'  => 8
    )
  ));

  // Call to action button color
  $wp_customize->add_control( 'shoestrap_hero_visibility', array(
    'label'       => __( 'Hero Region Visibility', 'bootstrap_commerce' ),
    'section'     => 'shoestrap_hero',
    'settings'    => 'shoestrap_hero_visibility',
    'type'        => 'select',
    'priority'    => 9,
    'choices'     => array(
      'front'     => __('Frontpage', 'bootstrap_commerce'),
      'site'      => __('Site-Wide', 'bootstrap_commerce'),
    ),
  ));

  
/*
 * FOOTER SECTION
 */
  $wp_customize->add_control( new WP_Customize_Color_Control(
    $wp_customize,
    'shoestrap_footer_background_color',
    array(
      'label'     => 'Footer Background',
      'section'   => 'shoestrap_footer',
      'settings'  => 'shoestrap_footer_background_color',
      'priority'  => 1
    )
  ));


/*
 * SOCIAL LINKS SECTION
 */

  $wp_customize->add_control( 'shoestrap_facebook_link', array(
    'label'       => __( 'Facebook Page Link', 'bootstrap_commerce' ),
    'section'     => 'shoestrap_social',
    'settings'    => 'shoestrap_facebook_link',
    'type'        => 'text',
    'priority'    => 1,
  ));

  $wp_customize->add_control( 'shoestrap_twitter_link', array(
    'label'       => __( 'Twitter Profile Link', 'bootstrap_commerce' ),
    'section'     => 'shoestrap_social',
    'settings'    => 'shoestrap_twitter_link',
    'type'        => 'text',
    'priority'    => 2,
  ));

  $wp_customize->add_control( 'shoestrap_google_plus_link', array(
    'label'       => __( 'Google+ Profile Link', 'bootstrap_commerce' ),
    'section'     => 'shoestrap_social',
    'settings'    => 'shoestrap_google_plus_link',
    'type'        => 'text',
    'priority'    => 3,
  ));

  $wp_customize->add_control( 'shoestrap_pinterest_link', array(
    'label'       => __( 'Pinterest Profile Link', 'bootstrap_commerce' ),
    'section'     => 'shoestrap_social',
    'settings'    => 'shoestrap_pinterest_link',
    'type'        => 'text',
    'priority'    => 4,
  ));


  if ( $wp_customize->is_preview() && ! is_admin() )
    add_action( 'wp_footer', 'shoestrap_preview', 21);
}
add_action( 'customize_register', 'shoestrap_register_controls' );
