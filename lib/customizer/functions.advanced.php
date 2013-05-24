<?php

/*
 * ADVANCED
 *
 * The advanced section allow users to enter their own css and/or scripts
 * and place them either in the head or the footer of the page.
 * These are textarea controls that we created in the beginning of this file.
 *
 * CAUTION:
 * Using this can be potentially dangerous for your site.
 * Any content you enter here will be echoed with minimal checks
 * so you should be careful of your code.
 *
 * To add css rules you must write <style>....your styles here...</style>
 * To add a script you should write <script>....your styles here...</script>
 *
 */


/*
 * If the user has entered any scripts in the 'head' control
 * of the advanced section of the customizer, then his content will be
 * echoed in the <head> of our page.
 *
 * CAUTION:
 * Anything users enter in the advanced section will not be filtered.
 */
function shoestrap_custom_header_scripts() {
  $header_scripts = shoestrap_getVariable( 'advanced_head' );
  echo $header_scripts;
}
add_action( 'wp_head', 'shoestrap_custom_header_scripts', 200 );

/*
 * If the user has entered any scripts in the 'head' control
 * of the advanced section of the customizer, then his content will be
 * echoed in the footer of our page.
 *
 * CAUTION:
 * Anything users enter in the advanced section will not be filtered.
 */
function shoestrap_custom_footer_scripts() {
  $footer_scripts = shoestrap_getVariable( 'advanced_footer' );
  echo $footer_scripts;
}
add_action( 'shoestrap_after_footer', 'shoestrap_custom_footer_scripts', 200 );
