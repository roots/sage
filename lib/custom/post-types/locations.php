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
    $admin_img_path = '//atkore.com/assets/img/atkore-admin-icon.png';

  	$labels = array(
  		'name'                => _x( 'Office Locations', 'Post Type General Name', 'atkore' ),
  		'singular_name'       => _x( 'Office Location', 'Post Type Singular Name', 'atkore' ),
  		'menu_name'           => __( 'Office Locations', 'atkore' ),
  		'parent_item_colon'   => __( 'Parent Location:', 'atkore' ),
  		'all_items'           => __( 'All Locations', 'atkore' ),
  		'view_item'           => __( 'View Locations', 'atkore' ),
  		'add_new_item'        => __( 'Add New Location', 'atkore' ),
  		'add_new'             => __( 'New Location', 'atkore' ),
  		'edit_item'           => __( 'Edit Location', 'atkore' ),
  		'update_item'         => __( 'Update Location', 'atkore' ),
  		'search_items'        => __( 'Search Locations', 'atkore' ),
  		'not_found'           => __( 'No Locations found', 'atkore' ),
  		'not_found_in_trash'  => __( 'No Locations found in Trash', 'atkore' ),
  	);

  	$rewrite = array(
  	'slug'                  => 'office-locations',
  	);

  	$args = array(
  		'label'               => __( 'Locations', 'atkore' ),
  		'description'         => __( 'Location location information pages', 'atkore' ),
  		'labels'              => $labels,
  		'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'custom-fields', 'page-attributes', ),
  		'taxonomies'          => array( 'location-type',),
  		'rewrite'             => $rewrite,
  		'hierarchical'        => true,
  		'public'              => true,
  		'show_ui'             => true,
  		'show_in_menu'        => true,
  		'show_in_nav_menus'   => true,
  		'show_in_admin_bar'   => true,
  		'menu_position'       => 5,
  		'menu_icon'           => $admin_img_path,
  		'can_export'          => true,
  		'has_archive'         => true,
  		'exclude_from_search' => false,
  		'publicly_queryable'  => true,
  		'capability_type'     => 'page',
  	);

  	register_post_type( 'atkore_location', $args );

  	$labels = array(
  		'name'                       => _x( 'Location Type', 'Taxonomy General Name', 'atkore' ),
  		'singular_name'              => _x( 'Location Type', 'Taxonomy Singular Name', 'atkore' ),
  		'menu_name'                  => __( 'Location Types', 'atkore' ),
  		'all_items'                  => __( 'All Location Types', 'atkore' ),
  		'parent_item'                => __( 'Parent Location Type', 'atkore' ),
  		'parent_item_colon'          => __( 'Parent Location Type:', 'atkore' ),
  		'new_item_name'              => __( 'New Location Type Name', 'atkore' ),
  		'add_new_item'               => __( 'Add New Location Type', 'atkore' ),
  		'edit_item'                  => __( 'Edit Location Type', 'atkore' ),
  		'update_item'                => __( 'Update Location Type', 'atkore' ),
  		'separate_items_with_commas' => __( 'Separate Location Type with commas', 'atkore' ),
  		'search_items'               => __( 'Search Location Types', 'atkore' ),
  		'add_or_remove_items'        => __( 'Add or remove Location Types', 'atkore' ),
  		'choose_from_most_used'      => __( 'Choose from the most used Location Types', 'atkore' ),
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

  	register_taxonomy( 'location_type', 'atkore_location', $args );

}
// Hook into the 'init' action
add_action( 'init', 'atkore_post_type_locations', 0 );

}