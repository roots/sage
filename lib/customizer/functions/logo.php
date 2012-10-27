<?php

/*
 * The site logo.
 * If no custom logo is uploaded, use the sitename
 */
function shoestrap_logo() {
  if ( get_theme_mod( 'shoestrap_logo' ) ) {
    if ( get_theme_mod( 'shoestrap_header_mode' ) == 'navbar' ) {
      $image = '<img id="site-logo" src="%s" alt="%s" style="height:20px; width:auto;">';
    } else {
      $image = '<img id="site-logo" src="%s" alt="%s" style="max-width:100%%; height:auto;">';
    }
    printf(
      $image,
      get_theme_mod('shoestrap_logo'),
      get_bloginfo('name')
    );
  } else {
    bloginfo('name');
  }
}

/*
 * Extra function for the navbar logo
 */
function navbar_brand() {
  if ( get_theme_mod( 'shoestrap_header_mode' ) == 'navbar' ) {
    shoestrap_logo();
  } else {
      bloginfo('name');
  }
}
