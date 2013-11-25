<?php

if ( !function_exists( 'shoestrap_branding' ) ) :
/*
 * The Header template
 */
function shoestrap_branding() {
  if ( shoestrap_getVariable( 'header_toggle' ) == 1 ) :
    if ( shoestrap_getVariable( 'site_style' ) == 'boxed' ) :
      echo '<div class="container">';
    endif;

    echo '<div class="header-wrapper">';

    if ( shoestrap_getVariable( 'site_style' ) == 'wide' ) :
      echo '<div class="container">';
    endif;

    if ( shoestrap_getVariable( 'header_branding' ) == 1 ) :
      echo '<a class="brand-logo" href="' . home_url() . '/">';
      echo '<h1>';

      if ( function_exists( 'shoestrap_logo' ) ) :
        shoestrap_logo();
      endif;

      echo '</h1>';
      echo '</a>';
    endif;

    if ( shoestrap_getVariable( 'header_branding' ) == 1 ) :
      echo '<div class="pull-right">';
    else :
      echo '<div>';
    endif;

    dynamic_sidebar( 'header-area' );
    echo '</div></div>';

    if ( shoestrap_getVariable( 'site_style' ) != 'fluid' ) :
      echo '</div>';
    endif;

  endif;
}
endif;
add_action( 'shoestrap_below_top_navbar', 'shoestrap_branding', 5 );

if ( !function_exists( 'shoestrap_header_css' ) ) :
/*
 * Any necessary extra CSS is generated here
 */
function shoestrap_header_css() {
  $bg = shoestrap_getVariable( 'header_bg' );
  $cl = shoestrap_getVariable( 'header_color' );
  
  $header_margin_top    = shoestrap_getVariable( 'header_margin_top' );
  $header_margin_bottom = shoestrap_getVariable( 'header_margin_bottom' );
  
  $opacity  = (intval(shoestrap_getVariable( 'header_bg_opacity' )))/100;
  $rgb      = shoestrap_get_rgb($bg, true);

  if ( shoestrap_getVariable( 'header_toggle' ) == 1 ) :
    $style = '.header-wrapper{';
    $style .= 'color: '.$cl.';';

    if ( $opacity != 1 && $opacity != '' ) :
      $style .= 'background: rgb('.$rgb.');';
      $style .= 'background: rgba('.$rgb.', '.$opacity.');';
    else :
      $style .= 'background: '.$bg.';';
    endif;

    $style .= 'margin-top:'.$header_margin_top.'px;';
    $style .= 'margin-bottom:'.$header_margin_bottom.'px;';
    $style .= '}';

    wp_add_inline_style( 'shoestrap_css', $style );

  endif;
}
endif;
add_action( 'wp_enqueue_scripts', 'shoestrap_header_css', 101 );