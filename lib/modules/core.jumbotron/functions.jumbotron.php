<?php

if ( !function_exists( 'jumbotron_content' ) ) :
/*
 * The content of the hero region
 * according to what we've entered in the customizer
 */
function jumbotron_content() {
  $hero         = false;
  $site_style   = shoestrap_getVariable( 'site_style' );
  $visibility   = shoestrap_getVariable( 'jumbotron_visibility' );
  $nocontainer  = shoestrap_getVariable( 'jumbotron_nocontainer' );

  if ( ( ( $visibility == 1 && is_front_page() ) || $visibility != 1 ) && is_active_sidebar( 'Jumbotron' ) ) :
    $hero = true;
  endif;

  echo '<div class="clearfix"></div>';

  if ( $hero == true ) :

    if ( $site_style == 'boxed' && $nocontainer != 1 ) :
      echo '<div class="' . shoestrap_container_class() . '">';
    endif;

    echo '<div class="jumbotron">';

    if ( $nocontainer != 1 && $site_style == 'wide' || $site_style == 'boxed' ) :
      echo '<div class="' . shoestrap_container_class() . '">';
    endif;

    dynamic_sidebar('Jumbotron');

    if ( $nocontainer != 1 && $site_style == 'wide' || $site_style == 'boxed' ) :
      echo '</div>';
    endif;

    if ( $site_style == 'boxed' && $nocontainer != 1 ) :
      echo '</div>';
    endif;

    echo '</div>';

  endif;
}
endif;
add_action( 'shoestrap_below_top_navbar', 'jumbotron_content', 10 );

if ( !function_exists( 'shoestrap_jumbotron_css' ) ) :
function shoestrap_jumbotron_css() {
  $center = shoestrap_getVariable( 'jumbotron_center' );
  $border = shoestrap_getVariable( 'jumbotron_border_bottom' );

  // $background is the saved custom image, or the default image.
  if ( shoestrap_getVariable( 'jumbotron_background_image_toggle' ) == 1 ) :

    if ( shoestrap_getVariable( 'jumbotron_background_custom_image' ) != "" ) :
    	$jVar = shoestrap_getVariable( 'jumbotron_background_custom_image' );
      $background = set_url_scheme( $jVar['url'] );
    elseif ( shoestrap_getVariable( 'jumbotron_background_image' ) != "" ) :
    	$jVar = shoestrap_getVariable( 'jumbotron_background_image' );
      $background = set_url_scheme( $jVar['url'] );
    endif;
    
  elseif ( shoestrap_getVariable( 'jumbotron_background_pattern_toggle' ) == 1 && shoestrap_getVariable( 'jumbotron_background_pattern' ) != "" ) :
    $background = shoestrap_getVariable( 'jumbotron_background_pattern' );
  endif;

  $color = '';
  if ( shoestrap_getVariable( 'jumbotron_background_color' ) != '' ) :
    $color = '#' . str_replace( '#', '', shoestrap_getVariable( 'jumbotron_background_color' ) );
  endif;

  if ( !isset( $background ) && !isset( $color ) ) :
    return;
  endif;

  $style = $color ? "background-color: $color;" : '';

  if ( shoestrap_getVariable( 'jumbotron_background_fixed_toggle' ) == 1 ) :
    $style .= 'background-attachment: fixed;';
  endif;

  $image = '';
  if ( isset($background) && $background ) :
    $image = "background-image: url( '$background' );";
  endif;

  $repeat   = '';
  $position = '';

  if ( shoestrap_getVariable( 'jumbotron_background_image_toggle' ) == 1 && ( shoestrap_getVariable( 'jumbotron_background_custom_image' ) != '' || shoestrap_getVariable( 'jumbotron_background_image' ) != '' ) ) :
    if ( shoestrap_getVariable( 'jumbotron_background_image_position_toggle' ) == 0 ) :
      $style .= "background-size: cover;";
      $style .= "-webkit-background-size: cover;";
      $style .= "-moz-background-size: cover;";
      $style .= "-o-background-size: cover;";
      $style .= "background-position: 50% 50%;";

      if ( shoestrap_getVariable( 'jumbotron_background_fixed_toggle' ) == 0 ) :
        $style .= "background-repeat: no-repeat;";
      endif;

    else : // Not fixed position, custom
      $repeat = shoestrap_getVariable( 'jumbotron_background_repeat' );

      if ( !in_array( $repeat, array( 'no-repeat', 'repeat-x', 'repeat-y', 'repeat' ) ) ) :
        $repeat = 'repeat';
      endif;

      if ( $repeat == 'no-repeat' ) :
        $style .= "background-size: auto;";
      endif;

      $repeat = " background-repeat: $repeat;";
      $position = shoestrap_getVariable( 'jumbotron_background_position_x', 'left' );
      
      if ( !in_array( $position, array( 'center', 'right', 'left' ) ) ) :
        $position = 'left';
      endif;

      $position = " background-position: top $position;";
    endif;
  endif;

  $style .= $image . $repeat . $position;

  if ( $center == 1 ) :
    $style .= 'text-align: center;';
  endif;

  if ( !empty($border) && $border['border-bottom'] > 0 && !empty($border['border-color']) ) :
    $style .= 'border-bottom:' . $border['border-bottom'] . ' ' . $border['border-style'] . ' ' . $border['border-color'] . ';';
  endif;

  $style .= 'margin-bottom: 0px;';

  $theCSS = '.jumbotron {' . trim( $style ) . '}';
  $theCSS .= $color ? ".jumbotron{background: $color;}" : '';
  
  wp_add_inline_style( 'shoestrap_css', $theCSS );
}
endif;
add_action( 'wp_enqueue_scripts', 'shoestrap_jumbotron_css', 101 );


if ( !function_exists( 'jumbotron_fittext' ) ) :
/*
 * Enables the fittext.js for h1 headings
 */
function jumbotron_fittext() {
  $fittext_toggle   = shoestrap_getVariable( 'jumbotron_title_fit' );
  $jumbo_visibility = shoestrap_getVariable( 'jumbotron_visibility' );

  // Should only show on the front page if it's enabled, or site-wide when appropriate
  if ( $fittext_toggle == 1 && ( $jumbo_visibility == 0 && ( $jumbo_visibility == 1 && is_front_page() ) ) ) :
    echo '<script>jQuery(".jumbotron h1").fitText(1.3);</script>';
  endif;
}
endif;
add_action( 'wp_footer', 'jumbotron_fittext', 10 );


if ( !function_exists( 'jumbotron_fittext_enqueue_script' ) ) :
/*
 * Enqueues fittext.js when needed
 */
function jumbotron_fittext_enqueue_script() {
  $fittext_toggle   = shoestrap_getVariable( 'jumbotron_title_fit' );
  $jumbo_visibility = shoestrap_getVariable( 'jumbotron_visibility' );

  if ( $fittext_toggle == 1 && ( $jumbo_visibility == 0 && ( $jumbo_visibility == 1 && is_front_page() ) ) ) :
    wp_register_script('fittext', get_template_directory_uri() . '/assets/js/vendor/jquery.fittext.js', false, null, false);
    wp_enqueue_script('fittext');
  endif;
}
endif;
add_action('wp_enqueue_scripts', 'jumbotron_fittext_enqueue_script', 101);

if ( !function_exists( 'shoestrap_conditional_jumbo_section_removal' ) ) :
/*
 * Removes the Jumbotron section from the customizer
 * if there are no widgets in the Jumbotron widget area.
 */
function shoestrap_conditional_jumbo_section_removal( $wp_customize ) {
  if ( !is_active_sidebar( 'jumbotron' ) ) :
    $wp_customize->remove_section( 'jumbotron');
  endif;
}
endif;
add_action( 'customize_register', 'shoestrap_conditional_jumbo_section_removal' );