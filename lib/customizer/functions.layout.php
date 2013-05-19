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

  if ( is_active_sidebar( 'sidebar-secondary' ) && is_active_sidebar( 'sidebar-primary' ) ) {
    if ( $layout >= 4 ) {
      $main       = $base . ( 12 - $first - $second );
      $primary    = $base . $first;
      $secondary  = $base . $second;
      $wrapper    = $base . ( 12 - $second );
    } elseif ( $layout >= 2 ) {
      $main       = $base . ( 12 - $first );
      $primary    = $base . $first;
      $secondary  = NULL;
    }
  } elseif ( !is_active_sidebar( 'sidebar-secondary' ) && is_active_sidebar( 'sidebar-primary' ) ) {
    if ( $layout >= 2 ) {
      $main       = $base . ( 12 - $first );
      $primary    = $base . $first;
      $secondary  = NULL;
    }
  } elseif ( is_active_sidebar( 'sidebar-secondary' ) && !is_active_sidebar( 'sidebar-primary' ) ) {
    if ( $layout >= 4 ) {
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
}

/*
 * If any css should be applied to fix the layout, enter it here.
 */
function shoestrap_sidebars_positioning_css() {
  
  $layout = get_theme_mod( 'shoestrap_layout' );
  $fluid  = get_theme_mod( 'shoestrap_fluid' );

  $css = '';

  // When the primary sidebar is first, set its margin-left to 0 since it has to go to the *left*
  if ( $layout == 'pm' || $layout == 'pms' || $layout == 'psm' ) {
    $css .= '#wrap #content #sidebar { margin-left: 0; }';
  }
  // When the secondary sidebar is first, set its margin-left to 0 since it has to go to the *left*
  if ( $layout == 'sm' || $layout == 'smp' || $layout == 'spm' || $layout == 'pms' ) {
    $css .= '#content #secondary { margin-left: 0; }';
  }

  // Float the main region to the right when needed
  if ( $layout == 'pm' || $layout == 'sm' || $layout == 'pms' || $layout == 'psm' || $layout == 'spm' ) {
    $css .= '#main { float: right; }';
  }

  // Float the main sidebar to the right when needed
  if ( $layout == 'msp' ) {
    $css .= '#sidebar { float: right; }';
  }

  // Float the main + primary wrapper div to the right when needed
  if ( $layout == 'smp' || $layout == 'spm' ) {
    $css .= '#wrap .m_p_wrap { float: right; }';
  }

  echo '<style>';
  echo $css;
  echo '</style>';
}
add_action( 'wp_head', 'shoestrap_sidebars_positioning_css' );
