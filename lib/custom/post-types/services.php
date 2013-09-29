<?php
/*
Plugin Name: Atkore Post Types: Services
Plugin URI: http://atkore.com
Description: Adds services as a custom post type
Version: 1.0
Author: Maintain Web
Author URI: http://maintainqweb.co/
License: GPL
Copyright: Maintain Web
*/

if ( ! function_exists('atkore_post_type_services') ) {

// Register Custom Post Types
function atkore_post_type_services() {
    $admin_img_path = 'http://atkore.com/assets/img/atkore-admin-icon.png';
  	// Services Post Type
  	$labels = array(
  		'name'                => _x( 'Services', 'Post Type General Name', 'roots' ),
  		'singular_name'       => _x( 'Service', 'Post Type Singular Name', 'roots' ),
  		'menu_name'           => __( 'Services', 'roots' ),
  		'parent_item_colon'   => __( 'Parent Service:', 'roots' ),
  		'all_items'           => __( 'All Services', 'roots' ),
  		'view_item'           => __( 'View Service', 'roots' ),
  		'add_new_item'        => __( 'Add New Service', 'roots' ),
  		'add_new'             => __( 'New Service', 'roots' ),
  		'edit_item'           => __( 'Edit Service', 'roots' ),
  		'update_item'         => __( 'Update Service', 'roots' ),
  		'search_items'        => __( 'Search Services', 'roots' ),
  		'not_found'           => __( 'Nothing found', 'roots' ),
  		'not_found_in_trash'  => __( 'Nothing found in Trash', 'roots' ),
  	);

  	$rewrite = array(
  	'slug'                  => 'services',
  	);

  	$args = array(
  		'label'               => __( 'Services', 'roots' ),
  		'description'         => __( 'Services information pages', 'roots' ),
  		'labels'              => $labels,
  		'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'custom-fields', 'page-attributes',),
  		'taxonomies'          => array( 'category','post_tag'),
  		'rewrite'             => $rewrite,
  		'hierarchical'        => true,
  		'public'              => true,
  		'show_ui'             => true,
  		'show_in_menu'        => true,
  		'show_in_nav_menus'   => true,
  		'show_in_admin_bar'   => true,
  		'menu_position'       => 40,
  		'menu_icon'           => $admin_img_path,
  		'can_export'          => true,
  		'has_archive'         => true,
  		'exclude_from_search' => false,
  		'publicly_queryable'  => true,
  		'capability_type'     => 'page',
  	);

  	register_post_type( 'service', $args );


}

// Hook into the 'init' action
add_action( 'init', 'atkore_post_type_services', 0 );

}