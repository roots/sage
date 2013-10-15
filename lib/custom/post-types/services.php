<?php
/*
Plugin Name: Atkore Post Types: Services
Plugin URI: http://atkore.com
Description: Adds services as a custom post type
Version: 1.0
Author: Maintain Web
Author URI: http://maintainweb.co/
License: GPL
Copyright: Maintain Web
*/

if ( ! function_exists('atkore_post_type_services') ) {

// Register Custom Post Types
function atkore_post_type_services() {
    $admin_img_path = '//atkore.com/assets/img/atkore-admin-icon.png';
  	// Services Post Type
  	$labels = array(
  		'name'                => _x( 'Services', 'Post Type General Name', 'atkore' ),
  		'singular_name'       => _x( 'Service', 'Post Type Singular Name', 'atkore' ),
  		'menu_name'           => __( 'Services', 'atkore' ),
  		'parent_item_colon'   => __( 'Parent Service:', 'atkore' ),
  		'all_items'           => __( 'All Services', 'atkore' ),
  		'view_item'           => __( 'View Service', 'atkore' ),
  		'add_new_item'        => __( 'Add New Service', 'atkore' ),
  		'add_new'             => __( 'New Service', 'atkore' ),
  		'edit_item'           => __( 'Edit Service', 'atkore' ),
  		'update_item'         => __( 'Update Service', 'atkore' ),
  		'search_items'        => __( 'Search Services', 'atkore' ),
  		'not_found'           => __( 'Nothing found', 'atkore' ),
  		'not_found_in_trash'  => __( 'Nothing found in Trash', 'atkore' ),
  	);

  	$rewrite = array(
  	'slug'                  => 'services',
  	);

  	$args = array(
  		'label'               => __( 'Services', 'atkore' ),
  		'description'         => __( 'Services information pages', 'atkore' ),
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
  		'menu_position'       => 20,
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