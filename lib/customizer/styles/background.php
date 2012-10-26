<?php

function shoestrap_background_css() {
  $color = get_theme_mod( 'background_color' );
  
  // Make sure colors are properly formatted
  $color = '#' . str_replace( '#', '', $color );
  ?>
  
  <style>
    #wrap{ background: <?php echo $color; ?>; }
  </style>
  <?php
}
add_action( 'wp_head', 'shoestrap_background_css', 199 );