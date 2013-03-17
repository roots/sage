<?php

/*
 * Applies the background to the footer.
 */
function shoestrap_footer_css() {
  $footer_color = get_theme_mod( 'shoestrap_footer_background_color' );
  
  // Make sure colors are properly formatted
  $footer_color = '#' . str_replace( '#', '', $footer_color );
  ?>
  
  <style>
    #footer-wrapper{ background: <?php echo $footer_color; ?> }
    <?php
    if ( shoestrap_get_brightness( $footer_color ) >= 160 ) { ?>
      #footer-wrapper{ color: <?php echo shoestrap_adjust_brightness( $footer_color, -150 ); ?>; }
      #footer-wrapper a{ color: <?php echo shoestrap_adjust_brightness( $footer_color, -180 ); ?>;}
    <?php } else { ?>
      #footer-wrapper{ color: <?php echo shoestrap_adjust_brightness( $footer_color, 150 ); ?>;}
      #footer-wrapper a{color: <?php echo shoestrap_adjust_brightness( $footer_color, 180 ); ?>;}
    <?php } ?>
  </style>
  <?php
}
add_action( 'wp_head', 'shoestrap_footer_css', 199 );