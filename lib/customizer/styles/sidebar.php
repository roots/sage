<?php

function shoestrap_sidebar_width( $target, $offset = '', $echo = false ) {
  $first  = get_theme_mod( 'shoestrap_aside_width' );
  $second = get_theme_mod( 'shoestrap_secondary_width' );
  
  if ( !is_active_sidebar( 'sidebar-secondary' ) ) {
    if ( $first == '2' ) {
      $main    = 'span10';
      $primary = 'span2';
    } elseif ( $first == '3' ) {
      $main    = 'span9';
      $primary = 'span3';
    } elseif ( $first == '5' ) {
      $main    = 'span7';
      $primary = 'span5';
    } elseif ( $first == '6' ) {
      $main    = 'span6';
      $primary = 'span6';
    } else { // default value
      $main    = 'span8';
      $primary = 'span4';
    }
  } else {
    if ( $second == '2' ) {
      if ( $first == '2' ) {
        $main      = 'span8';
        $primary   = 'span2';
        $secondary = 'span2';
      } elseif ( $first == '3' ) {
        $main      = 'span7';
        $primary   = 'span3';
        $secondary = 'span2';
      } elseif ( $first == '5' ) {
        $main      = 'span5';
        $primary   = 'span5';
        $secondary = 'span2';
      } elseif ( $first == '6' ) {
        $main      = 'span4';
        $primary   = 'span6';
        $secondary = 'span2';
      } else {
        $main      = 'span6';
        $primary   = 'span4';
        $secondary = 'span2';
      }
    } elseif ( $second == '3' ) {
      if ( $first == '2' ) {
        $main      = 'span7';
        $primary   = 'span2';
        $secondary = 'span3';
      } elseif ( $first == '3' ) {
        $main      = 'span6';
        $primary   = 'span3';
        $secondary = 'span3';
      } elseif ( $first == '5' ) {
        $main      = 'span4';
        $primary   = 'span5';
        $secondary = 'span3';
      } elseif ( $first == '6' ) {
        $main      = 'span3';
        $primary   = 'span6';
        $secondary = 'span3';
      } else {
        $main      = 'span5';
        $primary   = 'span4';
        $secondary = 'span3';
      }
    } elseif ( $second == '4' ) {
      if ( $first == '2' ) {
        $main      = 'span6';
        $primary   = 'span2';
        $secondary = 'span4';
      } elseif ( $first == '3' ) {
        $main      = 'span5';
        $primary   = 'span3';
        $secondary = 'span4';
      } elseif ( $first == '5' ) {
        $main      = 'span3';
        $primary   = 'span5';
        $secondary = 'span4';
      } elseif ( $first == '6' ) {
        $main      = 'span2';
        $primary   = 'span6';
        $secondary = 'span4';
      } else {
        $main      = 'span4';
        $primary   = 'span4';
        $secondary = 'span4';
      }
    }
  }
  
  if ( $target == 'primary' ) {
    $class = $primary;
  } elseif ( $target == 'secondary' ) {
    $class = $secondary;
  } else {
    $class = $main;
  }
  
  if ( $offset ) {
    $class = $class . ' offset' . $offset;
  }
  
  if ( $echo ) {
    echo $class;
  } else {
    return $class;
  }
}

function shoestrap_sidebars_calculate( $target = 'main', $echo = false ) {
    
  $primary_width      = get_theme_mod( 'shoestrap_aside_width' );
  $secondary_width    = get_theme_mod( 'shoestrap_secondary_width' );
  $primary_location   = get_theme_mod( 'shoestrap_aside_layout' );
  $secondary_location = get_theme_mod( 'shoestrap_secondary_layout' );
  
  
  // Primary - Main - Secondary
  if ( $primary_location == 'left' && $secondary_location == 'right' ) {
    $main_class      = shoestrap_sidebar_width( 'main', $primary_width );
    $primary_class   = shoestrap_sidebar_width( 'primary' );
    $secondary_class = shoestrap_sidebar_width( 'secondary' );
  }
  
  // Primary - Secondary - Main
  if ( $primary_location == 'left' && $secondary_location == 'center' ) {
    $main_class      = shoestrap_sidebar_width( 'main' );
    $primary_class   = shoestrap_sidebar_width( 'primary' );
    $secondary_class = shoestrap_sidebar_width( 'secondary' );
  }
  
  // Main - Primary - Secondary
  if ( $primary_location == 'right' && $secondary_location == 'right' ) {
    $main_class      = shoestrap_sidebar_width( 'main' );
    $primary_class   = shoestrap_sidebar_width( 'primary' );
    $secondary_class = shoestrap_sidebar_width( 'secondary' );
  }
  
  // Main - Secondary - Primary
  if ( $primary_location == 'right' && $secondary_location == 'center' ) {
    $main_class      = shoestrap_sidebar_width( 'main' );
    $primary_class   = shoestrap_sidebar_width( 'primary' );
    $secondary_class = shoestrap_sidebar_width( 'secondary' );
  }
  
  // Secondary - Main - Primary
  if ( $primary_location == 'right' && $secondary_location == 'left' ) {
    $main_class      = shoestrap_sidebar_width( 'main', $secondary_width );
    $primary_class   = shoestrap_sidebar_width( 'primary' );
    $secondary_class = shoestrap_sidebar_width( 'secondary' );
  }
  
  // Secondary - Primary - Main
  if ( $primary_location == 'left' && $secondary_location == 'left' ) {
    $main_class      = shoestrap_sidebar_width( 'main', $secondary_width + $primary_width);
    $primary_class   = shoestrap_sidebar_width( 'primary', $secondary_width );
    $secondary_class = shoestrap_sidebar_width( 'secondary' );
  }
  if ( $target == 'primary' ) {
    $class = $primary_class;
  } elseif ( $target == 'secondary' ) {
    $class = $secondary_class;
  } else {
    $class = $main_class;
  }
  
  if ( $echo ) {
    echo $class;
  } else {
    return $class;
  }
}

function shoestrap_sidebars_positioning_css() {
  $primary_location   = get_theme_mod( 'shoestrap_aside_layout' );
  $secondary_location = get_theme_mod( 'shoestrap_secondary_layout' );
  
  // Primary - Main - Secondary
  if ( $primary_location == 'left' && $secondary_location == 'right' ) {
    $css = '#secondary{float: right;}';
    // $css .= '@media (min-width: 768px){#sidebar, #secondary{position: absolute;}}';
  }
  // Primary - Secondary - Main
  if ( $primary_location == 'left' && $secondary_location == 'center' ) {
    $css = '#main{float: right;}';
  }
  // Main - Primary - Secondary
  if ( $primary_location == 'right' && $secondary_location == 'right' ) {
    $css = '';
  }
  // Main - Secondary - Primary
  if ( $primary_location == 'right' && $secondary_location == 'center' ) {
    $css = '#sidebar{float: right;}';
  }
  // Secondary - Main - Primary
  if ( $primary_location == 'right' && $secondary_location == 'left' ) {
    $css = '#sidebar{float: right;}';
    // $css .= '@media (min-width: 768px){#main{position: absolute;}}';
  }
  // Secondary - Primary - Main
  if ( $primary_location == 'left' && $secondary_location == 'left' ) {
    $css = '#main{float: right;}';
    // $css .= '@media (min-width: 768px){#main, #sidebar{position: absolute;}}';
  } ?>
  <style> <?php echo $css; ?> </style>
  <?php
}
add_action( 'wp_head', 'shoestrap_sidebars_positioning_css' );
