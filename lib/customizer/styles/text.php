<?php

/*
 * CSS needed to apply the selecte styles to text elements.
 */
function shoestrap_text_css() {
  $background_color = get_theme_mod( 'shoestrap_background_color' );
  $link_color       = get_theme_mod( 'shoestrap_link_color' );
  
  // Make sure colors are properly formatted
  $background_color = '#' . str_replace( '#', '', $background_color );
  $link_color       = '#' . str_replace( '#', '', $link_color );
  ?>

  <style>
    /* General links styling */
    a, a.active, a:hover, a.hover, a.visited, a:visited, a.link, a:link{ color: <?php echo $link_color; ?> }
    /* Button styling overrides */
    a.btn{ color: #333; }
    a.btn-primary, a.btn-info, a.btn-success, a.btn-danger, a.btn-inverse, a.btn-warning{ color: #fff; }
    <?php
    if ( shoestrap_get_brightness( $background_color ) >= 100 ) { ?>
      #wrap { color: #333; }
    <?php } else { ?>
      #wrap { color: #f7f7f7; }
    <?php } ?>
  </style>

  <?php
}
add_action( 'wp_head', 'shoestrap_text_css', 199 );
