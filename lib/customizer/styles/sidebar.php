<?php

function shoestrap_sidebar_width( $target, $echo = false ) {
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
  
  if ( $echo ) {
    echo $class;
  } else {
    return $class;
  }
}

function shoestrap_secondary_sidebar_position() {
  
  $primary   = get_theme_mod( 'shoestrap_aside_layout' );
  $secondary = get_theme_mod( 'shoestrap_secondary_layout' );
  
  // Primary - Main - Secondary
  if ( $primary == 'left' && $secondary == 'right' ) {
    
  }
  
  // Primary - Secondary - Main
  if ( $primary == 'left' && $secondary == 'center' ) {
    
  }
  
  // Main - Primary - Secondary
  if ( $primary == 'right' && $secondary == 'right' ) {
    
  }
  
  // Main - Secondary - Primary
  if ( $primary == 'right' && $secondary == 'center' ) {
    
  }
  
  // Secondary - Main - Primary
  if ( $primary == 'right' && $secondary == 'left' ) {
    
  }
  
  // Secondary - Primary - Main
  if ( $primary == 'left' && $secondary == 'left' ) {
    
  }
}
