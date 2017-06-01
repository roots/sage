<?php

/**
 * Returns global variables for use in namespaced functions
 *
 * @return Instance of global variable
 */
function get_the_query() {
	global $wp_query;
	return $wp_query;
}

function get_the_DB() {
	global $wpdb;
	return $wpdb;
}

function get_wp() {
	global $wp;
	return $wp;
}

function get_shortcode_tags() {
	global $shortcode_tags;
	return $shortcode_tags;
}


function global_post_setup($new_post) {
	global $post;
	$post = $new_post;
	setup_postdata($post);
}
