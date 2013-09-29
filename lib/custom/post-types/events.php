<?php
/*
Plugin Name: Atkore Post Types: Events
Plugin URI: http://atkore.com
Description: Adds events as a post type.
Version: 1.0
Author: Maintain Web
Author URI: http://maintainqweb.co/
License: GPL
Copyright: Maintain Web
*/

if ( ! function_exists('atkore_post_type_events') ) {

// Register Custom Post Types
function atkore_post_type_events() {
    $admin_img_path = 'http://atkore.com/assets/img/atkore-admin-icon.png';
  	$labels = array(
  		'name'                => _x( 'Events', 'Post Type General Name', 'roots' ),
  		'singular_name'       => _x( 'Event', 'Post Type Singular Name', 'roots' ),
  		'menu_name'           => __( 'Events', 'roots' ),
  		'parent_item_colon'   => __( 'Parent Event:', 'roots' ),
  		'all_items'           => __( 'All Events', 'roots' ),
  		'view_item'           => __( 'View Event', 'roots' ),
  		'add_new_item'        => __( 'Add New Event', 'roots' ),
  		'add_new'             => __( 'New Event', 'roots' ),
  		'edit_item'           => __( 'Edit Event', 'roots' ),
  		'update_item'         => __( 'Update Event', 'roots' ),
  		'search_items'        => __( 'Search Events', 'roots' ),
  		'not_found'           => __( 'Nothing found', 'roots' ),
  		'not_found_in_trash'  => __( 'Nothing found in Trash', 'roots' ),
  	);

  	$rewrite = array(
  	'slug'                  => 'events',
  	);

  	$args = array(
  		'label'               => __( 'Events', 'roots' ),
  		'description'         => __( 'Events', 'roots' ),
  		'labels'              => $labels,
  		'supports'            => array( 'title', 'editor', 'thumbnail', 'custom-fields', ),
  		'rewrite'             => $rewrite,
  		'hierarchical'        => false,
  		'public'              => true,
  		'show_ui'             => true,
  		'show_in_menu'        => true,
  		'show_in_nav_menus'   => true,
  		'show_in_admin_bar'   => false,
  		'menu_position'       => 55,
  		'menu_icon'           => $admin_img_path,
  		'can_export'          => true,
  		'has_archive'         => false,
  		'exclude_from_search' => true,
  		'publicly_queryable'  => false,
  		'capability_type'     => 'post',
  	);

  	register_post_type( 'event', $args );

}

// Hook into the 'init' action
add_action( 'init', 'atkore_post_type_events', 0 );

}