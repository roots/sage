<?php

// Store the old SMOF values
function shoestrap_preSave() {
  set_theme_mod( 'shoestrap_customizer_preSave', get_theme_mods() );
}
add_action('customize_save', 'shoestrap_preSave');

// Compare less values to see if we need to rebuild the CSS
function shoestrap_generateCSS() {
  global $smof_details;
  $old = get_theme_mod( 'shoestrap_customizer_preSave' );
  remove_theme_mod( 'shoestrap_customizer_preSave' ); // Cleanup
  $new = get_theme_mods();

  foreach ( $smof_details as $key=>$option ) {
    if ( $option['less'] == true ) {
      if ( $old[$option['id']] != $new[$option['id']] ) {
        shoestrap_makecss();
        break;
      }
    }
  }
}
add_action('customize_save_after', 'shoestrap_generateCSS');

$smof_details = array();

function smof_customize_init( $wp_customize ) {
  // Get Javascript
  of_load_only();
  // Have to change the javascript for the customizer
  wp_dequeue_script( 'smof', ADMIN_DIR .'assets/js/smof.js' );
  wp_enqueue_style( 'wp-pointer' );
  wp_enqueue_script( 'wp-pointer' );
  // Remove when code is in place!
  wp_enqueue_script('smofcustomizerjs', ADMIN_DIR .'assets/js/customizer.js');
  // Get styles
  of_style_only();
  wp_enqueue_style('smofcustomizer', ADMIN_DIR .'assets/css/customizer.css');
}
add_action( 'customize_controls_init', 'smof_customize_init' );

function smof_preview_init( $wp_customize ) {
  global $smof_data, $smof_details;
  wp_dequeue_style( 'shoestrap_css' );
  wp_deregister_style( 'shoestrap_css' );

  file_put_contents( str_replace( ".css", ".less", shoestrap_css() ), shoestrap_complete_less( true ) );
  print '<link rel="stylesheet/less" type="text/less" href="'.str_replace( ".css", ".less", shoestrap_css( 'url' ) ).'">';
  print '<script type="text/javascript">
    less = {
      env: "development", // or "production"
      async: false,       // load imports async
      fileAsync: false,   // load imports async when in a page under a file protocol
      poll: 1000,         // when in watch mode, time in ms between polls
      functions: {},      // user functions, keyed by name
      dumpLineNumbers: "comments", // or "mediaQuery" or "all"
      relativeUrls: false,// whether to adjust urls to be relative if false, urls are already relative to the entry less file
      rootpath: "http://localhost/wordpress3/wp-content/themes/shoestrap/less/"// a path to add on to the start of every url resource
    };
  </script>';
  wp_enqueue_script( 'less-js', ADMIN_DIR .'/assets/js/less-1.3.3.min.js' );
  wp_enqueue_script( 'preview-js', ADMIN_DIR .'assets/js/preview.js' );
  wp_localize_script( 'preview-js', 'smofPost', array(
    'data'      => $smof_data,
    'variables' => $smof_details
  ));
}
add_action( 'customize_preview_init', 'smof_preview_init' );

function enqueue_less_styles( $tag, $handle ) {
  global $wp_styles;
  $match_pattern = '/\.less$/U';
  if ( preg_match( $match_pattern, $wp_styles->registered[$handle]->src ) ) {
    $handle = $wp_styles->registered[$handle]->handle;
    $media = $wp_styles->registered[$handle]->args;
    $href = $wp_styles->registered[$handle]->src . '?ver=' . $wp_styles->registered[$handle]->ver;
    $rel = isset($wp_styles->registered[$handle]->extra['alt']) && $wp_styles->registered[$handle]->extra['alt'] ? 'alternate stylesheet' : 'stylesheet';
    $title = isset($wp_styles->registered[$handle]->extra['title']) ? "title='" . esc_attr( $wp_styles->registered[$handle]->extra['title'] ) . "'" : '';
    $tag = "<link rel='stylesheet/less' id='$handle' $title href='$href' type='text/less' media='$media' />";
  }
  return $tag;
}

function postMessageHandlersJS() {
  global $smof_data, $smof_details;
  $script = "";
  foreach ( $smof_details as $option ) {
    if ( $option['less'] == true ) {
      $script .="
      wp.customize( option , function( value ) {
        value.bind( function( to ) {
          console.log('Setting customize bind: '+option);
          var variable = '@'+option;
          console.log(option);
        });
      });";
    }
  }
}

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
