<?php
/*
Plugin Name: Atkore Post Types: Location
Plugin URI: http://atkore.com
Description: Adds locations as a custom post type
Version: 1.0
Author: Maintain Web
Author URI: http://maintainweb.co/
License: GPL
Copyright: Maintain Web
*/

if ( ! function_exists('atkore_post_type_locations') ) {

// Register Custom Post Types
function atkore_post_type_locations() {
    $admin_img_path = 'http://atkore.com/assets/img/atkore-admin-icon.png';

  	$labels = array(
  		'name'                => _x( 'Locations', 'Post Type General Name', 'roots' ),
  		'singular_name'       => _x( 'Location', 'Post Type Singular Name', 'roots' ),
  		'menu_name'           => __( 'Locations', 'roots' ),
  		'parent_item_colon'   => __( 'Parent Location:', 'roots' ),
  		'all_items'           => __( 'All Locations', 'roots' ),
  		'view_item'           => __( 'View Locations', 'roots' ),
  		'add_new_item'        => __( 'Add New Location', 'roots' ),
  		'add_new'             => __( 'New Location', 'roots' ),
  		'edit_item'           => __( 'Edit Location', 'roots' ),
  		'update_item'         => __( 'Update Location', 'roots' ),
  		'search_items'        => __( 'Search Locations', 'roots' ),
  		'not_found'           => __( 'No Locations found', 'roots' ),
  		'not_found_in_trash'  => __( 'No Locations found in Trash', 'roots' ),
  	);

  	$args = array(
  		'label'               => __( 'location', 'roots' ),
  		'description'         => __( 'Location location information pages', 'roots' ),
  		'labels'              => $labels,
  		'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'custom-fields', 'page-attributes', ),
  		'taxonomies'          => array( 'location-type',),
  		'hierarchical'        => true,
  		'public'              => true,
  		'show_ui'             => true,
  		'show_in_menu'        => true,
  		'show_in_nav_menus'   => true,
  		'show_in_admin_bar'   => true,
  		'menu_position'       => 56,
  		'menu_icon'           => $admin_img_path,
  		'can_export'          => true,
  		'has_archive'         => true,
  		'exclude_from_search' => false,
  		'publicly_queryable'  => true,
  		'capability_type'     => 'page',
  	);

  	register_post_type( 'location', $args );

  	$labels = array(
  		'name'                       => _x( 'Location Type', 'Taxonomy General Name', 'roots' ),
  		'singular_name'              => _x( 'Location Type', 'Taxonomy Singular Name', 'roots' ),
  		'menu_name'                  => __( 'Location Types', 'roots' ),
  		'all_items'                  => __( 'All Location Types', 'roots' ),
  		'parent_item'                => __( 'Parent Location Type', 'roots' ),
  		'parent_item_colon'          => __( 'Parent Location Type:', 'roots' ),
  		'new_item_name'              => __( 'New Location Type Name', 'roots' ),
  		'add_new_item'               => __( 'Add New Location Type', 'roots' ),
  		'edit_item'                  => __( 'Edit Location Type', 'roots' ),
  		'update_item'                => __( 'Update Location Type', 'roots' ),
  		'separate_items_with_commas' => __( 'Separate Location Type with commas', 'roots' ),
  		'search_items'               => __( 'Search Location Types', 'roots' ),
  		'add_or_remove_items'        => __( 'Add or remove Location Types', 'roots' ),
  		'choose_from_most_used'      => __( 'Choose from the most used Location Types', 'roots' ),
  	);

  	$capabilities = array(
  		'manage_terms'               => 'manage_categories',
  		'edit_terms'                 => 'manage_categories',
  		'delete_terms'               => 'manage_categories',
  		'assign_terms'               => 'edit_posts',
  	);
  	
  	$rewrite = array(
  	  'slug'                => 'location-type',
  	  'with_front'          => false,
  	  'hierarchical'        => true,
  	);

  	$args = array(
  		'labels'                     => $labels,
  		'hierarchical'               => true,
  		'public'                     => true,
  		'show_ui'                    => true,
  		'show_admin_column'          => true,
  		'show_in_nav_menus'          => true,
  		'show_tagcloud'              => true,
  		'query_var'                  => 'location-type',
  		'rewrite'                    => $rewrite,
  		'capabilities'               => $capabilities,
  	);

  	register_taxonomy( 'location-type', 'location', $args );

}
// Hook into the 'init' action
add_action( 'init', 'atkore_post_type_locations', 0 );

}