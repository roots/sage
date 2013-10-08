<?php
/*
Plugin Name: Atkore Post Types: Markets
Plugin URI: http://atkore.com
Description: Adds services as a custom post type
Version: 1.0
Author: Maintain Web
Author URI: http://maintainweb.co/
License: GPL
Copyright: Maintain Web
*/

if ( ! function_exists('atkore_post_type_markets') ) {

// Register Custom Post Types
function atkore_post_type_markets() {
    $admin_img_path = 'http://atkore.com/assets/img/atkore-admin-icon.png';
  	// Services Post Type
  	$labels = array(
  		'name'                => _x( 'Markets', 'Post Type General Name', 'atkore' ),
  		'singular_name'       => _x( 'Market', 'Post Type Singular Name', 'atkore' ),
  		'menu_name'           => __( 'Markets', 'atkore' ),
  		'parent_item_colon'   => __( 'Parent Market:', 'atkore' ),
  		'all_items'           => __( 'All Markets', 'atkore' ),
  		'view_item'           => __( 'View Market', 'atkore' ),
  		'add_new_item'        => __( 'Add New Market', 'atkore' ),
  		'add_new'             => __( 'New Market', 'atkore' ),
  		'edit_item'           => __( 'Edit Market', 'atkore' ),
  		'update_item'         => __( 'Update Market', 'atkore' ),
  		'search_items'        => __( 'Search Markets', 'atkore' ),
  		'not_found'           => __( 'Nothing found', 'atkore' ),
  		'not_found_in_trash'  => __( 'Nothing found in Trash', 'atkore' ),
  	);

  	$rewrite = array(
  	'slug'                  => 'markets',
  	);

  	$args = array(
  		'label'               => __( 'Markets', 'atkore' ),
  		'description'         => __( 'Markets information pages', 'atkore' ),
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
  		'menu_position'       => 1.6,
  		'menu_icon'           => $admin_img_path,
  		'can_export'          => true,
  		'has_archive'         => true,
  		'exclude_from_search' => false,
  		'publicly_queryable'  => true,
  		'capability_type'     => 'page',
  	);

  	register_post_type( 'market', $args );


}

// Hook into the 'init' action
add_action( 'init', 'atkore_post_type_markets', 0 );

}