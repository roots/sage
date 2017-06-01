<?php

namespace App;

/**
* Cleanup Wordpress
*/
add_action('after_setup_theme', function () {

	/**
	* Cleanup head
	*/
	add_action('init', function () {
		// Remove category feeds
		// remove_action( 'wp_head', 'feed_links_extra', 3 );
		// Remove post and comment feeds
		// remove_action( 'wp_head', 'feed_links', 2 );
		// Remove EditURI link
		remove_action( 'wp_head', 'rsd_link' );
		// Remove Windows live writer
		remove_action( 'wp_head', 'wlwmanifest_link' );
		// Remove index link
		remove_action( 'wp_head', 'index_rel_link' );
		// Remove previous link
		remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
		// Remove start link
		remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
		// Remove links for adjacent posts
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
		// Remove WP version
		remove_action( 'wp_head', 'wp_generator' );
	});

	/**
	* Remove pesky injected css for recent comments widget
	*/
	add_filter('wp_head', function () {
		if ( has_filter('wp_head', 'wp_widget_recent_comments_style') ) {
			remove_filter('wp_head', 'wp_widget_recent_comments_style' );
		}
	});

	/**
	* Clean up comment styles in the head
	*/
	add_action('wp_head', function () {
		// Remove injected CSS from recent comments widget
		global $wp_widget_factory;
		if (isset($wp_widget_factory->widgets['WP_Widget_Recent_Comments'])) {
			remove_action('wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'));
		}
	});

	/**
	* Clean up gallery output in wp
	*/
	add_filter('gallery_style', function ($css) {
		// Remove injected CSS from gallery
		return preg_replace("!<style type='text/css'>(.*?)</style>!s", '', $css);
	});
});


/**
 * Stop WordPress from using the sticky class (which conflicts with Foundation), and style WordPress 
 * sticky posts using the .wp-sticky class instead
 */
add_filter('post_class', function ($classes) {
	if(in_array('sticky', $classes)) {
		$classes = array_diff($classes, array("sticky"));
		$classes[] = 'wp-sticky';
	}
	
	return $classes;
});

/**
 * Add custom sticky flag to the post meta data on post update
 */
add_action('post_updated', function($post_id) {
	$is_sticky = (isset($_POST['sticky']) && $_POST['sticky'] == 'sticky') || 
				 (isset($_GET['sticky']) && $_GET['sticky'] == 'sticky') ? 1 : 0;

	update_post_meta($post_id, 'custom_sticky', $is_sticky);
});

/**
 * Update Main Query on Blog page to order sticky posts
 */
add_action('pre_get_posts', function($query) {
	if($query->is_home() && $query->is_main_query()) {
		$query->set('ignore_sticky_posts', 1);
		$query->set('meta_key', 'custom_sticky');
		$query->set('orderby', array('meta_value_num' => 'DESC', 'date' => 'DESC'));
	}
});

/**
 * Remove custom Sticky meta data on theme deactivation
 */
add_action('switch_theme', function() { 
	delete_metadata ('post', null, 'custom_sticky', null, true);
});

/**
 * Add custom Sticky meta data on theme activation
 */
add_action('after_switch_theme', function() {
	$blog_posts = get_posts([
		'post_status' => 'any',
		'numberposts' => -1,
		'meta_query' => [
			[
				'key' => 'custom_sticky',
				'compare' => 'NOT EXISTS'
			],
		]
	]);

	if (!empty($blog_posts)) {
		foreach ($blog_posts as $blog_post) {
			add_post_meta($blog_post->ID, 'custom_sticky', is_sticky($blog_post->ID) ? 1 : 0);
		}
	}
});