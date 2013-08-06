<?php

/*
 * The Header template
 */
function shoestrap_branding() {
  if ( shoestrap_getVariable( 'header_toggle' ) == 1 ) :
    if ( shoestrap_getVariable( 'site_style' ) == 'boxed' )
      echo '<div class="container">';

    echo '<div class="header-wrapper">';

    if ( shoestrap_getVariable( 'site_style' ) == 'wide' )
      echo '<div class="container">';

    if ( shoestrap_getVariable( 'header_branding' ) == 1 ) {
      echo '<a class="brand-logo" href="' . home_url() . '/">';
      echo '<h1>';
      if ( function_exists( 'shoestrap_logo' ) )
        shoestrap_logo();
      echo '</h1>';
      echo '</a>';
    }
    if ( shoestrap_getVariable( 'header_branding' ) == 1 )
      echo '<div class="pull-right">';
    else
      echo '<div>';

    dynamic_sidebar('header-area');
    echo '</div></div>';
    if ( shoestrap_getVariable( 'site_style' ) != 'fluid' )
      echo '</div>';

  endif;
}
add_action( 'shoestrap_below_top_navbar', 'shoestrap_branding', 5 );

function shoestrap_header_css() {
  $bg = shoestrap_getVariable( 'header_bg');
  $cl = shoestrap_getVariable( 'header_color' );
  $opacity = (intval(shoestrap_getVariable( 'header_bg_opacity' )))/100;

  $rgb = shoestrap_get_rgb($bg, true);

  if ( shoestrap_getVariable( 'header_toggle' ) == 1 ) {

      $style = '.header-wrapper{';
        if ($opacity != 1 && $opacity != "") {
          $style .= 'background: rgb('.$rgb.');';
          $style .= 'background: rgba('.$rgb.', '.$opacity.');';
        } else {
          $style .= 'background: '.$bg.';';
        }
      $style .= '}';

  	wp_add_inline_style( 'shoestrap_css', $style );

  }
}
add_action( 'wp_enqueue_scripts', 'shoestrap_header_css', 101 );
