<?php

function shoestrap_branding_css() {
  $header_bg_color        = get_theme_mod( 'shoestrap_header_backgroundcolor' );
  $header_sitename_color  = get_theme_mod( 'shoestrap_header_textcolor' );
  
  // Make sure colors are properly formatted
  $header_bg_color        = '#' . str_replace( '#', '', $header_bg_color );
  $header_sitename_color  = '#' . str_replace( '#', '', $header_sitename_color );
  ?>

  <style>
    .logo-wrapper{background: <?php echo $header_bg_color; ?>;}
    .logo-wrapper .logo a{color: <?php echo $header_sitename_color; ?>;}
  </style>
  <?php
}
add_action( 'wp_head', 'shoestrap_branding_css', 199 );
