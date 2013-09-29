<?php
/*
Plugin Name: Atkore Post Types: Carousels
Plugin URI: http://atkore.com
Description: Adds applications as a custom post type
Version: 1.0
Author: Maintain Web
Author URI: http://maintainweb.co/
License: GPL
Copyright: Maintain Web
*/

if ( ! function_exists('atkore_post_type_carousels') ) {

// Register Custom Post Types
function atkore_post_type_carousels() {
    $admin_img_path = 'http://atkore.com/assets/img/atkore-admin-icon.png';
  	// Carousel	Post Type
  	$labels = array(
  		'name'                => _x( 'Carousels', 'Post Type General Name', 'roots' ),
  		'singular_name'       => _x( 'Carousel', 'Post Type Singular Name', 'roots' ),
  		'menu_name'           => __( 'Carousels', 'roots' ),
  		'parent_item_colon'   => __( 'Parent Carousel:', 'roots' ),
  		'all_items'           => __( 'All Carousels', 'roots' ),
  		'view_item'           => __( 'View Carousel', 'roots' ),
  		'add_new_item'        => __( 'Add New Carousel', 'roots' ),
  		'add_new'             => __( 'New Carousel', 'roots' ),
  		'edit_item'           => __( 'Edit Carousel', 'roots' ),
  		'update_item'         => __( 'Update Carousel', 'roots' ),
  		'search_items'        => __( 'Search Carousels', 'roots' ),
  		'not_found'           => __( 'No Carousels found', 'roots' ),
  		'not_found_in_trash'  => __( 'No Carousels found in Trash', 'roots' ),
  	);

  	$args = array(
  		'label'               => __( 'carousel', 'roots' ),
  		'description'         => __( 'carousel information pages', 'roots' ),
  		'labels'              => $labels,
  		'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'custom-fields', 'page-attributes', ),
  		'hierarchical'        => true,
  		'public'              => true,
  		'show_ui'             => true,
  		'show_in_menu'        => true,
  		'show_in_nav_menus'   => true,
  		'show_in_admin_bar'   => true,
  		'menu_position'       => 46,
  		'menu_icon'           => $admin_img_path,
  		'can_export'          => true,
  		'has_archive'         => false,
  		'exclude_from_search' => true,
  		'publicly_queryable'  => false,
  		'capability_type'     => 'post',
  	);

  	register_post_type( 'carousel', $args );
}
// Hook into the 'init' action
add_action( 'init', 'atkore_post_type_carousels', 0 );

}