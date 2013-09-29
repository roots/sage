<?php
/*
Plugin Name: Atkore Post Types: Applications
Plugin URI: http://atkore.com
Description: Adds applications as a custom post type
Version: 1.0
Author: Maintain Web
Author URI: http://maintainqweb.co/
License: GPL
Copyright: Maintain Web
*/

if ( ! function_exists('atkore_post_type_applications') ) {

// Register Custom Post Types
function atkore_post_type_applications() {
    $admin_img_path = 'http://atkore.com/assets/img/atkore-admin-icon.png';
  	// Applications Post Type
  	$labels = array(
  		'name'                => _x( 'Applications', 'Post Type General Name', 'roots' ),
  		'singular_name'       => _x( 'Application', 'Post Type Singular Name', 'roots' ),
  		'menu_name'           => __( 'Applications', 'roots' ),
  		'parent_item_colon'   => __( 'Parent Service:', 'roots' ),
  		'all_items'           => __( 'All Applications', 'roots' ),
  		'view_item'           => __( 'View Application', 'roots' ),
  		'add_new_item'        => __( 'Add New Application', 'roots' ),
  		'add_new'             => __( 'New Application', 'roots' ),
  		'edit_item'           => __( 'Edit Application', 'roots' ),
  		'update_item'         => __( 'Update Application', 'roots' ),
  		'search_items'        => __( 'Search Applications', 'roots' ),
  		'not_found'           => __( 'Nothing found', 'roots' ),
  		'not_found_in_trash'  => __( 'Nothing found in Trash', 'roots' ),
  	);

  	$rewrite = array(
  	'slug'                  => 'applications',
  	);

  	$args = array(
  		'label'               => __( 'Applications', 'roots' ),
  		'description'         => __( 'Applications information pages', 'roots' ),
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
  		'menu_position'       => 45,
  		'menu_icon'           => $admin_img_path,
  		'can_export'          => true,
  		'has_archive'         => true,
  		'exclude_from_search' => false,
  		'publicly_queryable'  => true,
  		'capability_type'     => 'page',
  	);

  	register_post_type( 'application', $args );


}

// Hook into the 'init' action
add_action( 'init', 'atkore_post_type_applications', 0 );

}