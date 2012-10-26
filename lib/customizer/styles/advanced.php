<?php

function shoestrap_custom_header_scripts() {
  $header_scripts = get_theme_mod( 'shoestrap_advanced_head' );
  echo $header_scripts;
}
add_action( 'wp_head', 'shoestrap_custom_header_scripts', 200 );

function shoestrap_custom_footer_scripts() {
  $footer_scripts = get_theme_mod( 'shoestrap_advanced_footer' );
  echo $footer_scripts;
}
add_action( 'shoestrap_after_footer', 'shoestrap_custom_footer_scripts', 200 );

