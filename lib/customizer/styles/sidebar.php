<?php

function shoestrap_sidebar_class_calc( $target, $offset = '', $echo = false ) {
  $first  = get_theme_mod( 'shoestrap_aside_width' );
  $second = get_theme_mod( 'shoestrap_secondary_width' );
  $layout = get_theme_mod( 'shoestrap_layout' );
  
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
  
  if ( $layout == 'm' ) {
    $main = 'span12';
  }
  if ( in_array( $layout, array( 'mp', 'pm' ) ) ) {
    $main = 'span' . ( 12 - $first );
  }
  if ( in_array( $layout, array( 'ms', 'sm' ) ) ) {
    $main = 'span' . ( 12 - $second );
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

function shoestrap_sidebars_positioning_css() {
  
  $shoestrap_layout = get_theme_mod( 'shoestrap_layout' );
  
  if ( $shoestrap_layout == 'pm' ) {
    $css = '#main{float: right;}';
  } elseif ( $shoestrap_layout == 'sm' ) {
    $css = '#main{float: right;}';
  } elseif ( $shoestrap_layout == 'mps' ) {
    $css = '#secondary{float: right;}';
  } elseif ( $shoestrap_layout == 'msp' ) {
    $css = '#sidebar{float: right;}';
  } elseif ( $shoestrap_layout == 'pms' ) {
    $css = '#main, #secondary{float: right;} .m_p_wrap{float: left;}';
  } elseif ( $shoestrap_layout == 'psm' ) {
    $css = '#main{float: right;}';
  } elseif ( $shoestrap_layout == 'smp' ) {
    $css = '.m_p_wrap{float: right;}';
  } elseif ( $shoestrap_layout == 'spm' ) {
    $css = '.m_p_wrap, #main{float: right;}';
  } else {
  } ?>
  <style> <?php echo $css; ?> </style>
  <?php
}
add_action( 'wp_head', 'shoestrap_sidebars_positioning_css' );
