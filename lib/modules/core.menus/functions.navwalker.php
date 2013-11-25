<?php

// Register Custom Navigation Walker
require_once( 'wp_bootstrap_navwalker.php' );

// Remove the default Roots Navwalker
remove_filter('wp_nav_menu_args', 'roots_nav_menu_args');

// Add the custom navwalker
function shoestrap_alt_nav_menu_args($args = '') {
  $shoestrap_alt_nav_menu_args['container'] = false;

  if ( !$args['items_wrap'] ) :
  	$shoestrap_alt_nav_menu_args['items_wrap'] = '<ul class="%2$s">%3$s</ul>';
  endif;

  if ( current_theme_supports( 'bootstrap-top-navbar' ) && !$args['depth'] ) :
  	$shoestrap_alt_nav_menu_args['depth'] = 2;
  endif;

  if ( !$args['walker'] ) :
  	$shoestrap_alt_nav_menu_args['walker'] = new wp_bootstrap_navwalker();
  endif;

  if ( !$args['fallback_cb'] ) :
  	$shoestrap_alt_nav_menu_args['fallback_cb'] = 'wp_bootstrap_navwalker::fallback';
  endif;

  return array_merge( $args, $shoestrap_alt_nav_menu_args );
}
add_filter( 'wp_nav_menu_args', 'shoestrap_alt_nav_menu_args' );