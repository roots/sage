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

function atkore_post_type_carousels() {
    $admin_img_path = '//atkore.com/assets/img/atkore-admin-icon.png';
  	$labels = array(
  		'name'                => _x( 'Carousels', 'Post Type General Name', 'atkore' ),
  		'singular_name'       => _x( 'Carousel', 'Post Type Singular Name', 'atkore' ),
  		'menu_name'           => __( 'Carousels', 'atkore' ),
  		'parent_item_colon'   => __( 'Parent Carousel:', 'atkore' ),
  		'all_items'           => __( 'All Carousels', 'atkore' ),
  		'view_item'           => __( 'View Carousel', 'atkore' ),
  		'add_new_item'        => __( 'Add New Carousel', 'atkore' ),
  		'add_new'             => __( 'New Carousel', 'atkore' ),
  		'edit_item'           => __( 'Edit Carousel', 'atkore' ),
  		'update_item'         => __( 'Update Carousel', 'atkore' ),
  		'search_items'        => __( 'Search Carousels', 'atkore' ),
  		'not_found'           => __( 'No Carousels found', 'atkore' ),
  		'not_found_in_trash'  => __( 'No Carousels found in Trash', 'atkore' ),
  	);

  	$args = array(
  		'label'               => __( 'carousel', 'atkore' ),
  		'description'         => __( 'carousel information pages', 'atkore' ),
  		'labels'              => $labels,
  		'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'custom-fields', 'page-attributes', ),
  		'hierarchical'        => true,
  		'public'              => true,
  		'show_ui'             => true,
  		'show_in_menu'        => true,
  		'show_in_nav_menus'   => true,
  		'show_in_admin_bar'   => true,
  		'menu_position'       => 5,
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