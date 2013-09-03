<?php

/*
 * The site logo.
 * If no custom logo is uploaded, use the sitename
 */
function shoestrap_logo() {
  $logo  = shoestrap_getVariable( 'logo' );

  if ( !empty( $logo['url'] ) )
    echo '<img id="site-logo" src="' . $logo['url'] . '" alt="' . get_bloginfo( 'name' ) . '">';
  else
    echo '<span class="sitename">' . bloginfo( 'name' ) . '</span>';

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
