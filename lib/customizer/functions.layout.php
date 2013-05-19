<?php

/*
 * Calculates the classes of the main area, main sidebar and secondary sidebar
 */
function shoestrap_section_class( $target, $echo = false ) {
  $layout = get_theme_mod( 'layout' );
  $first  = get_theme_mod( 'layout_primary_width' );
  $second = get_theme_mod( 'layout_secondary_width' );
  
  $base   = 'col col-lg-';
  // Set some defaults so that we can change them depending on the selected template
  $main       = $base . 12;
  $primary    = NULL;
  $secondary  = NULL;
  $wrapper    = NULL;
  $wrap       = false;

  if ( is_active_sidebar( 'sidebar-secondary' ) && is_active_sidebar( 'sidebar-primary' ) ) {
    if ( $layout >= 3 ) {
      $main       = $base . ceil( ( 12 - $first ) * ( 12 - $second ) / 12 );
      $primary    = $base . ( 12 - ceil( ( 12 - $first ) * ( 12 - $second ) / 12 ) );
      $secondary  = $base . $second;
      $wrapper    = $base . ( 12 - $second );
      $wrap       = true;
    } elseif ( $layout >= 1 ) {
      $main       = $base . ( 12 - $first );
      $primary    = $base . $first;
      $secondary  = NULL;
    }
  } elseif ( !is_active_sidebar( 'sidebar-secondary' ) && is_active_sidebar( 'sidebar-primary' ) ) {
    if ( $layout >= 1 ) {
      $main       = $base . ( 12 - $first );
      $primary    = $base . $first;
      $secondary  = NULL;
    }
  } elseif ( is_active_sidebar( 'sidebar-secondary' ) && !is_active_sidebar( 'sidebar-primary' ) ) {
    if ( $layout >= 3 ) {
      $main       = $base . ( 12 - $second );
      $secondary  = $base . $second;
    }
  }

  // Overrides main region class when selected template is page-full.php
  if ( is_page_template('page-full.php') ) {
    $main         = $base . 12;
  }

  // Overrides main and primary region classes when selected template is page-primary-sidebar.php
  if ( is_page_template('page-primary-sidebar.php') ) {
    $main      = $base . ( 12 - $first );
    $primary   = $base . $first;
  }  

  if ( $target == 'primary' )
    $class = $primary;
  elseif ( $target == 'secondary' )
    $class = $secondary;
  elseif ( $target == 'wrapper' )
    $class = $wrapper;
  else
    $class = $main;

  // echo or return the result.
  if ( $echo )
    echo $class;
  else
    return $class;

  if ( $target == 'wrap' )
    return $wrap;
}

/*
 * If any css should be applied to fix the layout, enter it here.
 */
function shoestrap_sidebars_positioning_css() {
  $layout = get_theme_mod( 'shoestrap_layout' );
}
add_action( 'wp_head', 'shoestrap_sidebars_positioning_css' );
