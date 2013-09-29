<?php
// Add domain specific class to body
function atkore_class_names($classes) {
  $domain = $_SERVER[ 'SERVER_NAME' ];
	// add 'class-name' to the $classes array
	$classes[] = $domain;
	// return the $classes array
	return $classes;
}
add_filter('body_class','atkore_class_names');

// Remove brand class to prevent bootstrap collisions
function atkore_post_names($classes) {
	$classes = array_diff($classes, array('brand',));
	return $classes;
}
add_filter('post_class','atkore_post_names');

// Add brand specific CSS to post class
function add_brand_class( $classes )
{
    global $post;
    if ( isset( $post ) ) {
        $classes[] = $post->post_type . '-' . $post->post_name;
    }
    return $classes;
}
add_filter( 'post_class', 'add_brand_class' );