<?php
/*
Plugin Name: Atkore Post Types: Resources
Plugin URI: http://atkore.com
Description: Adds resources as a custom post type
Version: 1.0
Author: Maintain Web
Author URI: http://maintainweb.co/
License: GPL
Copyright: Maintain Web
*/

if ( ! function_exists('atkore_post_type_resources') ) {

// Register Custom Post Types
function atkore_post_type_resources() {
    $admin_img_path = 'http://atkore.com/assets/img/atkore-admin-icon.png';
  	// Applications Post Type
  	// Resources Post Type
  	$labels = array(
  		'name'                => _x( 'Resources', 'Post Type General Name', 'atkore' ),
  		'singular_name'       => _x( 'Resource', 'Post Type Singular Name', 'atkore' ),
  		'menu_name'           => __( 'Resources', 'atkore' ),
  		'parent_item_colon'   => __( 'Parent Resource:', 'atkore' ),
  		'all_items'           => __( 'All Resources', 'atkore' ),
  		'view_item'           => __( 'View Resource', 'atkore' ),
  		'add_new_item'        => __( 'Add New Resource', 'atkore' ),
  		'add_new'             => __( 'New Resource', 'atkore' ),
  		'edit_item'           => __( 'Edit Resource', 'atkore' ),
  		'update_item'         => __( 'Update Resource', 'atkore' ),
  		'search_items'        => __( 'Search Resources', 'atkore' ),
  		'not_found'           => __( 'Nothing found', 'atkore' ),
  		'not_found_in_trash'  => __( 'Nothing found in Trash', 'atkore' ),
  	);

  	$rewrite = array(
  	'slug'                  => 'resources',
  	);

  	$args = array(
  		'label'               => __( 'Resources', 'atkore' ),
  		'description'         => __( 'Resources information pages', 'atkore' ),
  		'labels'              => $labels,
  		'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'custom-fields', 'page-attributes', 'post-formats',),
  		'taxonomies'          => array( 'category','post_tag', 'resource-type'),
  		'rewrite'             => $rewrite,
  		'hierarchical'        => true,
  		'public'              => true,
  		'show_ui'             => true,
  		'show_in_menu'        => true,
  		'show_in_nav_menus'   => true,
  		'show_in_admin_bar'   => true,
  		'menu_position'       => 1.8,
  		'menu_icon'           => $admin_img_path,
  		'can_export'          => true,
  		'has_archive'         => true,
  		'exclude_from_search' => false,
  		'publicly_queryable'  => true,
  		'capability_type'     => 'post',
  	);

  	register_post_type( 'resource', $args );
  	
  	$labels = array(
  		'name'                       => _x( 'Resource Type', 'Taxonomy General Name', 'atkore' ),
  		'singular_name'              => _x( 'Resource Type', 'Taxonomy Singular Name', 'atkore' ),
  		'menu_name'                  => __( 'Resource Types', 'atkore' ),
  		'all_items'                  => __( 'All Resource Types', 'atkore' ),
  		'parent_item'                => __( 'Parent Resource Type', 'atkore' ),
  		'parent_item_colon'          => __( 'Parent Resource Type:', 'atkore' ),
  		'new_item_name'              => __( 'New Resource Type Name', 'atkore' ),
  		'add_new_item'               => __( 'Add New Resource Type', 'atkore' ),
  		'edit_item'                  => __( 'Edit Resource Type', 'atkore' ),
  		'update_item'                => __( 'Update Resource Type', 'atkore' ),
  		'separate_items_with_commas' => __( 'Separate Resource Types with commas', 'atkore' ),
  		'search_items'               => __( 'Search Resource Types', 'atkore' ),
  		'add_or_remove_items'        => __( 'Add or remove Resource Types', 'atkore' ),
  		'choose_from_most_used'      => __( 'Choose from the most used Resource Types', 'atkore' ),
  	);
  	
  	$rewrite = array(
  	  'slug'                => 'product',
  	  'with_front'          => false,
  	  'hierarchical'        => true,
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
  		'query_var'                  => 'resource-type',
  		'rewrite'                    => true,
  		'capabilities'               => $capabilities,
  	);

  	register_taxonomy( 'resource-type', 'resource', $args );

}

// Hook into the 'init' action
add_action( 'init', 'atkore_post_type_resources', 0 );

}