<?php
/*
Plugin Name: Atkore Post Types: Brands
Plugin URI: http://atkore.com
Description: Adds applications as a custom post type
Version: 1.0
Author: Maintain Web
Author URI: http://maintainqweb.co/
License: GPL
Copyright: Maintain Web
*/

if ( ! function_exists('atkore_post_type_brands') ) {

// Register Custom Post Types
function atkore_post_type_brands() {
  $admin_img_path = 'http://atkore.com/assets/img/atkore-admin-icon.png';

  	// Brands Post Type
  	$labels = array(
  		'name'                => _x( 'Brands', 'Post Type General Name', 'roots' ),
  		'singular_name'       => _x( 'Brand', 'Post Type Singular Name', 'roots' ),
  		'menu_name'           => __( 'Brands', 'roots' ),
  		'parent_item_colon'   => __( 'Parent Brand:', 'roots' ),
  		'all_items'           => __( 'All Brands', 'roots' ),
  		'view_item'           => __( 'View Brand', 'roots' ),
  		'add_new_item'        => __( 'Add New Brand', 'roots' ),
  		'add_new'             => __( 'New Brand', 'roots' ),
  		'edit_item'           => __( 'Edit Brand', 'roots' ),
  		'update_item'         => __( 'Update Brand', 'roots' ),
  		'search_items'        => __( 'Search brands', 'roots' ),
  		'not_found'           => __( 'No brands found', 'roots' ),
  		'not_found_in_trash'  => __( 'No brands found in Trash', 'roots' ),
  	);

  	$args = array(
  		'label'               => __( 'brand', 'roots' ),
  		'description'         => __( 'Brand information pages', 'roots' ),
  		'labels'              => $labels,
  		'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'custom-fields', 'page-attributes', ),
  		'hierarchical'        => true,
  		'public'              => true,
  		'show_ui'             => true,
  		'show_in_menu'        => true,
  		'show_in_nav_menus'   => true,
  		'show_in_admin_bar'   => true,
  		'menu_position'       => 30,
  		'menu_icon'           => $admin_img_path,
  		'can_export'          => true,
  		'has_archive'         => true,
  		'exclude_from_search' => false,
  		'publicly_queryable'  => true,
  		'capability_type'     => 'page',
  	);

  	register_post_type( 'brand', $args );

  	// Resources Sections Taxonomy
  	$labels = array(
  		'name'                       => _x( 'Brand Category', 'Taxonomy General Name', 'roots' ),
  		'singular_name'              => _x( 'Brand Category', 'Taxonomy Singular Name', 'roots' ),
  		'menu_name'                  => __( 'Categories', 'roots' ),
  		'all_items'                  => __( 'All Categories', 'roots' ),
  		'parent_item'                => __( 'Parent Category', 'roots' ),
  		'parent_item_colon'          => __( 'Parent Category:', 'roots' ),
  		'new_item_name'              => __( 'New Category Name', 'roots' ),
  		'add_new_item'               => __( 'Add New Category', 'roots' ),
  		'edit_item'                  => __( 'Edit Category', 'roots' ),
  		'update_item'                => __( 'Update Category', 'roots' ),
  		'separate_items_with_commas' => __( 'Separate Category with commas', 'roots' ),
  		'search_items'               => __( 'Search categories', 'roots' ),
  		'add_or_remove_items'        => __( 'Add or remove categories', 'roots' ),
  		'choose_from_most_used'      => __( 'Choose from the most used categories', 'roots' ),
  	);

  	$capabilities = array(
  		'manage_terms'               => 'manage_categories',
  		'edit_terms'                 => 'manage_categories',
  		'delete_terms'               => 'manage_categories',
  		'assign_terms'               => 'edit_posts',
  	);

  	$args = array(
  		'labels'                     => $labels,
  		'hierarchical'               => true,
  		'public'                     => true,
  		'show_ui'                    => true,
  		'show_admin_column'          => true,
  		'show_in_nav_menus'          => true,
  		'show_tagcloud'              => true,
  		'query_var'                  => 'brand-category',
  		'rewrite'                    => false,
  		'capabilities'               => $capabilities,
  	);

  	register_taxonomy( 'brand-category', 'brand', $args );


}

// Hook into the 'init' action
add_action( 'init', 'atkore_post_type_brands', 0 );

}

