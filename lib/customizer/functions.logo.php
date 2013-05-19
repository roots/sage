<?php

/*
 * The site logo.
 * If no custom logo is uploaded, use the sitename
 */
function shoestrap_logo() {
  if ( get_theme_mod( 'logo' ) ) {
    $image = '<img id="site-logo" src="%s" alt="%s">';
    printf(
      $image,
      get_theme_mod( 'logo' ),
      get_bloginfo( 'name' )
    );
  } else {
    echo '<span class="sitename">';
    bloginfo( 'name' );
    echo '</span>';
  }
}
