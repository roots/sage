<?php

/*
 * The blog's base CSS file
 */
if ( !function_exists( 'shoestrap_base_css' ) ) {
  function shoestrap_base_css() {
    wp_register_style( 'shoestrap-base-css', get_template_directory_uri() . '/assets/css/base.css' );
    wp_enqueue_style( 'shoestrap-base-css' );


	

	

  }
}
add_action( 'wp_enqueue_scripts','shoestrap_base_css', 95 );
