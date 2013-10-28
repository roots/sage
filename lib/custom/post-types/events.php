<?php
/*
Plugin Name: Atkore Post Types: Events
Plugin URI: http://atkore.com
Description: Adds events as a post type.
Version: 1.0
Author: Maintain Web
Author URI: http://maintainweb.co/
License: GPL
Copyright: Maintain Web
*/

if ( ! function_exists('atkore_post_type_events') ) {

// Register Custom Post Types
function atkore_post_type_events() {
    $admin_img_path = '//atkore.com/assets/img/atkore-admin-icon.png';
  	$labels = array(
  		'name'                => _x( 'Events', 'Post Type General Name', 'atkore' ),
  		'singular_name'       => _x( 'Event', 'Post Type Singular Name', 'atkore' ),
  		'menu_name'           => __( 'Events', 'atkore' ),
  		'parent_item_colon'   => __( 'Parent Event:', 'atkore' ),
  		'all_items'           => __( 'All Events', 'atkore' ),
  		'view_item'           => __( 'View Event', 'atkore' ),
  		'add_new_item'        => __( 'Add New Event', 'atkore' ),
  		'add_new'             => __( 'New Event', 'atkore' ),
  		'edit_item'           => __( 'Edit Event', 'atkore' ),
  		'update_item'         => __( 'Update Event', 'atkore' ),
  		'search_items'        => __( 'Search Events', 'atkore' ),
  		'not_found'           => __( 'Nothing found', 'atkore' ),
  		'not_found_in_trash'  => __( 'Nothing found in Trash', 'atkore' ),
  	);

  	$rewrite = array(
  	'slug'                  => 'events',
  	);

  	$args = array(
  		'label'               => __( 'Events', 'atkore' ),
  		'description'         => __( 'Events', 'atkore' ),
  		'labels'              => $labels,
  		'supports'            => array( 'title', 'editor', 'thumbnail', 'custom-fields', ),
  		//'taxonomies'          => array( '',),
  		'rewrite'             => $rewrite,
  		'hierarchical'        => false,
  		'public'              => true,
  		'show_ui'             => true,
  		'show_in_menu'        => true,
  		'show_in_nav_menus'   => true,
  		'show_in_admin_bar'   => false,
  		'menu_position'       => 5,
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