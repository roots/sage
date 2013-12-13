<?php

if ( !function_exists( 'shoestrap_getLayout' ) ) :
/*
 * Get the layout value, but only set it once!
 */
function shoestrap_getLayout() {
  global $shoestrap_layout;

  if ( !isset( $shoestrap_layout ) ) :
    do_action( 'shoestrap_layout_modifier' );
    // Looking for a per-page template ?
    if ( is_page_template() ) :
      if ( is_page_template( 'template-0.php' ) ) :
        $shoestrap_layout = 0;
      elseif ( is_page_template( 'template-1.php' ) ) :
        $shoestrap_layout = 1;
      elseif ( is_page_template( 'template-2.php' ) ) :
        $shoestrap_layout = 2;
      elseif ( is_page_template( 'template-3.php' ) ) :
        $shoestrap_layout = 3;
      elseif ( is_page_template( 'template-4.php' ) ) :
        $shoestrap_layout = 4;
      elseif ( is_page_template( 'template-5.php' ) ) :
        $shoestrap_layout = 5;
      else :
        $shoestrap_layout = intval( shoestrap_getVariable( 'layout' ) );
      endif;
    endif;
    // Use default setting if $shoestrap_layout not set yet
    if ( !isset( $shoestrap_layout ) ) :
      $post_types = get_post_types( array( 'public' => true ), 'names' );
      foreach ( $post_types as $post_type ) :
        if ( is_singular( $post_type ) && shoestrap_getVariable( 'cpt_layout_toggle' ) == 1 ) :
          $shoestrap_layout = intval( shoestrap_getVariable( $post_type . '_layout' ) );
        endif;
      endforeach; 
    endif;
    
    if ( !is_active_sidebar( 'sidebar-secondary' ) && is_active_sidebar( 'sidebar-primary' ) && $shoestrap_layout == 5 ) :
      $shoestrap_layout = 3;
    endif;

  endif;

  return $shoestrap_layout;
}
endif;


if ( !function_exists( 'shoestrap_setLayout' ) ) :
/*
 *Override the layout value globally
 */
function shoestrap_setLayout( $val ) {
  global $shoestrap_layout, $redux;
  $shoestrap_layout = intval( $val );
}
endif;

if ( !function_exists( 'shoestrap_section_class' ) ) :
/*
 * Calculates the classes of the main area, main sidebar and secondary sidebar
 */
function shoestrap_section_class( $target, $echo = false ) {
  global $redux;
  
  $layout = shoestrap_getLayout();
  $first  = intval( shoestrap_getVariable( 'layout_primary_width' ) );
  $second = intval( shoestrap_getVariable( 'layout_secondary_width' ) );
  $base   = 'col-sm-';
  
  // Set some defaults so that we can change them depending on the selected template
  $main       = $base . 12;
  $primary    = NULL;
  $secondary  = NULL;
  $wrapper    = NULL;

  if ( is_active_sidebar( 'sidebar-secondary' ) && is_active_sidebar( 'sidebar-primary' ) ) :

    if ( $layout == 5 ) :
      $main       = $base . ( 12 - floor( ( 12 * $first ) / ( 12 - $second ) ) );

      $primary    = $base . floor( ( 12 * $first ) / ( 12 - $second ) );
      $secondary  = $base . $second;
      $wrapper    = $base . ( 12 - $second );
    elseif ( $layout >= 3 ) :
      $main       = $base . ( 12 - $first - $second );
      $primary    = $base . $first;
      $secondary  = $base . $second;
    elseif ( $layout >= 1 ) :
      $main       = $base . ( 12 - $first );
      $primary    = $base . $first;
      $secondary  = $base . $second;
    endif;

  elseif ( !is_active_sidebar( 'sidebar-secondary' ) && is_active_sidebar( 'sidebar-primary' ) ) :

    if ( $layout >= 1 ) :
      $main       = $base . ( 12 - $first );
      $primary    = $base . $first;
    endif;

  elseif ( is_active_sidebar( 'sidebar-secondary' ) && !is_active_sidebar( 'sidebar-primary' ) ) :

    if ( $layout >= 3 ) :
      $main       = $base . ( 12 - $second );
      $secondary  = $base . $second;
    endif;
  endif;

  // Overrides the main region class when on the frontpage and sidebars are set to not being displayed there.
  if ( is_front_page() && shoestrap_getVariable( 'layout_sidebar_on_front' ) != 1 ) :
    $main      = $base . 12;
    $wrapper    = NULL;
  endif;

  if ( $target == 'primary' )
    $class = $primary;
  elseif ( $target == 'secondary' )
    $class = $secondary;
  elseif ( $target == 'wrapper' )
    $class = $wrapper;
  else
    $class = $main;

  if ( $target != 'wrap'  ) :
    // echo or return the result.
    if ( $echo ) :
      echo $class;
    else :
      return $class;
    endif;
  else :
    if ( $layout == 5 ) :
      return true;
    else :
      return false;
    endif;
  endif;
}
endif;

if ( !function_exists( 'shoestrap_layout_body_class' ) ) :
/**
 * Add and remove body_class() classes to accomodate layouts
 */
function shoestrap_layout_body_class( $classes ) {
  $layout     = shoestrap_getLayout();
  $site_style = shoestrap_getVariable( 'site_style' );
  $margin     = shoestrap_getVariable( 'navbar_margin_top' );
  $style      = '';

  if ( $layout == 2 || $layout == 3 || $layout == 5 ) :
    $classes[] = 'main-float-right';
  endif;

  if ( $site_style == 'boxed' && $margin != 0 ) :
    $classes[] = 'boxed-style';
  endif;

  // Remove unnecessary classes
  $remove_classes = array();
  $classes = array_diff( $classes, $remove_classes );

  return $classes;
}
endif;
add_filter('body_class', 'shoestrap_layout_body_class');


if ( !function_exists( 'shoestrap_container_class' ) ) :
/*
 * Return the container class
 */
function shoestrap_container_class() {
  $site_style = shoestrap_getVariable( 'site_style' );

  if ( $site_style != 'fluid' ) :
    return 'container';
  else :
    return 'fluid';
  endif;
}
endif;

if ( !function_exists( 'shoestrap_content_width_px' ) ) :
/*
 * Calculate the width of the content area in pixels.
 */
function shoestrap_content_width_px( $echo = false ) {
  global $redux;

  $layout = shoestrap_getLayout();

  $container  = filter_var( shoestrap_getVariable( 'screen_large_desktop' ), FILTER_SANITIZE_NUMBER_INT );
  $gutter     = filter_var( shoestrap_getVariable( 'layout_gutter' ), FILTER_SANITIZE_NUMBER_INT );

  $main_span  = filter_var( shoestrap_section_class( 'main', false ), FILTER_SANITIZE_NUMBER_INT );
  $main_span  = str_replace( '-' , '', $main_span );

  // If the layout is #5, override the default function and calculate the span width of the main area again.
  if ( is_active_sidebar( 'sidebar-secondary' ) && is_active_sidebar( 'sidebar-primary' ) && $layout == 5 ) :
    $main_span = 12 - intval( shoestrap_getVariable( 'layout_primary_width' ) ) - intval( shoestrap_getVariable( 'layout_secondary_width' ) );
  endif;

  if ( is_front_page() && shoestrap_getVariable( 'layout_sidebar_on_front' ) != 1 ) :
    $main_span = 12;
  endif;

  $width = $container * ( $main_span / 12 ) - $gutter;

  // Width should be an integer since we're talking pixels, round up!.
  $width = round( $width );

  if ( $echo ) :
    echo $width;
  else :
    return $width;
  endif;
}
endif;

if ( !function_exists( 'shoestrap_content_width' ) ) :
/*
 * Set the content width
 */
function shoestrap_content_width() {
  global $content_width;
  $content_width = shoestrap_content_width_px();
}
endif;
add_action( 'template_redirect', 'shoestrap_content_width' );

if ( !function_exists( 'shoestrap_body_margin' ) ) :
/*
 * Body Margins
 */
function shoestrap_body_margin() {
  $body_margin_top = shoestrap_getVariable( 'body_margin_top' );
  $body_margin_bottom = shoestrap_getVariable( 'body_margin_bottom' );

  $style = 'body {';
    $style .= 'margin-top:'. $body_margin_top .'px;';
    $style .= 'margin-bottom:'. $body_margin_bottom .'px;';
  $style .= '}';

  wp_add_inline_style( 'shoestrap_css', $style );
}
endif;

if ( ( shoestrap_getVariable( 'body_margin_top' ) != 0 ) || ( shoestrap_getVariable( 'body_margin_bottom' ) != 0 ) ) :
  add_action( 'wp_enqueue_scripts', 'shoestrap_body_margin', 101 );
endif;
