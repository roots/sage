<?php

/*
 * Calculates the classes of the main area, main sidebar and secondary sidebar
 */
function shoestrap_sidebar_class_calc( $target, $offset = '', $echo = false ) {
  $first  = get_theme_mod( 'shoestrap_aside_width' );
  $second = get_theme_mod( 'shoestrap_secondary_width' );
  $layout = get_theme_mod( 'shoestrap_layout' );
  $fluid  = get_theme_mod( 'shoestrap_fluid' );
  
  // If secondary sidebar is empty, ignore it.
  if ( !is_active_sidebar( 'sidebar-secondary' ) ) {
    $main      = 'col col-lg-' . ( 12 - $first );
    $primary   = 'col col-lg-' . $first;
  // If secondary sidebar is not empty, do not ignore it.
  } else {
    $main      = 'col col-lg-' . ( 12 - $first - $second );
    $primary   = 'col col-lg-' . $first;
    $secondary = 'col col-lg-' . $second;
  }

  if ( ( $layout == 'pms' ) || ( $layout == 'mps' ) || ( $layout == 'smp' ) || ( $layout == 'spm' ) ) {
    $main = 'col col-lg-' . ( 12 - $first );
  }

  $main_primary = 'col col-lg-' . ( 12 - $second );
  
  // If the layout is "Main only", the main area should have a class of col col-lg-12
  if ( $layout == 'm' ) {
    $main = 'col col-lg-12';
  }
  
  // If the layout contains only the main area and primary sidebar, ignore the secondary sidebar width
  if ( in_array( $layout, array( 'mp', 'pm' ) ) ) {
    $main = 'col col-lg-' . ( 12 - $first );
  }
  
  // If the layout contains only the main area and secondary sidebar, ignore the primary sidebar width
  if ( in_array( $layout, array( 'ms', 'sm' ) ) ) {
    $main = 'col col-lg-' . ( 12 - $second );
  }
  
  // Overrides main region class when selected template is page-full.php
  if ( is_page_template('page-full.php') ) {
    $main = 'col col-lg-12';
  }

  // Overrides main and primary region classes when selected template is page-primary-sidebar.php
  if ( is_page_template('page-primary-sidebar.php') ) {
    $main      = 'col col-lg-' . ( 12 - $first );
    $primary   = 'col col-lg-' . $first;
  }  

  if ( $target == 'primary' ) {
    // return the primary class
    $class = $primary;
  } elseif ( $target == 'secondary' ) {
    // return the secondary class
    $class = $secondary;
  } elseif ( $target == 'main-primary' ) {
    $class = $main_primary;
  } else {
    // return the main class
    $class = $main;
  }
  
  // if we've selected an offset, add it here.
  if ( $offset ) {
    $class = $class . ' offset' . $offset;
  }
  
  // Echo or return the result.
  if ( $echo ) {
    echo $class;
  } else {
    return $class;
  }
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
