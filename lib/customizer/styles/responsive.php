<?php

function shoestrap_responsive_class_before() {
  $shoestrap_responsive = get_theme_mod( 'shoestrap_responsive' );
  if ( $shoestrap_responsive == '0' ) {
    echo '<div id="fixedwidth">';
  } else {
    echo '<div id="responsive"';
  }
}
add_action( 'shoestrap_pre_wrap', 'shoestrap_responsive_class_before' );

function shoestrap_responsive_class_after() {
  echo '</div>';
}
add_action( 'shoestrap_pre_wrap', 'shoestrap_responsive_class_after' );
