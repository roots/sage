<?php

function shoestrap_sidebar_width( $target, $echo = false ) {
  $sidebar_width = get_theme_mod( 'shoestrap_aside_width' );
  
  if ( $sidebar_width == '2' ) {
    $main    = 'span10';
    $sidebar = 'span2';
  } elseif ($sidebar_width == '3') {
    $main    = 'span9';
    $sidebar = 'span3';
  } elseif ($sidebar_width == '5') {
    $main    = 'span7';
    $sidebar = 'span5';
  } elseif ($sidebar_width == '6') {
    $main    = 'span6';
    $sidebar = 'span6';
  } else { // default value
    $main    = 'span8';
    $sidebar = 'span4';
  }
  
  if ( $target == 'main' ) {
    $class = $main;
  } else {
    $class = $sidebar;
  }
  
  if ( $echo ) {
    echo $class;
  } else {
    return $class;
  }
}
