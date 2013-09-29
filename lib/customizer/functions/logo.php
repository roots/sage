<?php

/*
 * The site logo.
 * If no custom logo is uploaded, use the sitename
 */
function shoestrap_logo() {
  if ( get_theme_mod( 'shoestrap_logo' ) ) {
    $image = '<img id="site-logo" src="%s" alt="%s" style="max-width:100%%; height:auto;">';
    printf(
      $image,
      get_theme_mod( 'shoestrap_logo' ),
      get_bloginfo( 'name' )
    );
  } else {
    echo '<span class="sitename">';
    bloginfo( 'name' );
    echo '</span>';
  }
}

/*
 * Extra function for the navbar logo.
 * Same as the shoestrap_logo function,
 * with just a minor css tweak.
 */
function shoestrap_navbar_brand() {
  if ( get_theme_mod( 'shoestrap_navbar_logo' ) != 0 ) {
    if ( get_theme_mod( 'shoestrap_logo' ) ) {
      $image = '<img id="site-logo" src="%s" alt="%s" style="max-height:20px; width:auto;">';
      printf(
        $image,
        get_theme_mod( 'shoestrap_logo' ),
        get_bloginfo( 'name' )
      );
    } else {
      echo '<span class="sitename">';
      bloginfo( 'name' );
      echo '</span>';
    }
  } else {
    bloginfo( 'name' );
  }
}
