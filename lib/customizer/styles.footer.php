<?php

/*
 * Applies the background to the footer.
 */
function shoestrap_footer_css() {
  $bg = get_theme_mod( 'footer_bg' );
  $cl = get_theme_mod( 'footer_color' );
  
  // Make sure colors are properly formatted
  $bg = '#' . str_replace( '#', '', $bg );
  $cl = '#' . str_replace( '#', '', $cl );
  
  $styles = '<style>';
  // If no color is selected, then do not apply anything
  if ( strlen( $bg ) < 6 ) {
    $styles .= '#footer-wrapper{ background: none; background: transparent; }';
  } else {
    $styles .= '#footer-wrapper{ background: ' . $bg . ';}';
    if ( strlen( $cl ) < 6 ) {
      if ( shoestrap_get_brightness( $bg ) >= 160 ) {
          $styles .= '#footer-wrapper{ color: ' . shoestrap_adjust_brightness( $bg, -150 ) . ';}';
          $styles .= '#footer-wrapper a{ color: ' . shoestrap_adjust_brightness( $bg, -180 ) . ';}';
      } else {
        $styles .= '#footer-wrapper{ color: ' . shoestrap_adjust_brightness( $bg, 150 ) . ';}';
        $styles .= '#footer-wrapper a{color: ' . shoestrap_adjust_brightness( $bg, 180 ) . ';}';
      }
    } else {
      $styles .= '#footer-wrapper{ color: ' . $cl . ';}';
      $styles .= '#footer-wrapper a{ color: ' . $cl . ';}';
    }
  }
  $styles .= '</style>';
  
  return $styles;
}
