<?php

/*
 * The site logo.
 * If no custom logo is uploaded, use the sitename
 */
function shoestrap_logo() {
  if ( shoestrap_getVariable( 'logo' ) ) {
    $image = '<img id="site-logo" src="%s" alt="%s">';
    printf(
      $image,
      shoestrap_getVariable( 'logo' ),
      get_bloginfo( 'name' )
    );
  } else {
    echo '<span class="sitename">';
    bloginfo( 'name' );
    echo '</span>';
  }
}

function shoestrap_branding_class( $echo = true ) {
  if ( shoestrap_getVariable( 'logo' ) )
    $class = 'logo';
  else
    $class = 'text';

  if ( $echo == false )
    return $class;
  else
    echo $class;
}
