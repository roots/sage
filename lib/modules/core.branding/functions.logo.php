<?php

/*
 * The site logo.
 * If no custom logo is uploaded, use the sitename
 */
function shoestrap_logo() {
  if ( $data = shoestrap_getVariable( 'logo' ) ) {
  	$i = shoestrap_image($data);
  	
  	if (empty($i['url'])) {
  		return;
  	}
  	
    $image = '<img id="site-logo" src="%s" alt="%s">';
    printf(
      $image,
      $i['url'],
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
