<?php
/**
 * Custom functions
 */

if ( ! function_exists('atkore_post_types') ) {

// Register Custom Post Type
function atkore_post_types() {
	$labels = array(
		'name'                => _x( 'Brands', 'Post Type General Name', 'roots' ),
		'singular_name'       => _x( 'Brand', 'Post Type Singular Name', 'roots' ),
		'menu_name'           => __( 'Brand', 'roots' ),
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
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 5,
		'menu_icon'           => '/assets/atkore-admin-icon.png',
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'page',
	);

	register_post_type( 'brand', $args );
	
	$labels = array(
		'name'                => _x( 'Products', 'Post Type General Name', 'roots' ),
		'singular_name'       => _x( 'Product', 'Post Type Singular Name', 'roots' ),
		'menu_name'           => __( 'Product', 'roots' ),
		'parent_item_colon'   => __( 'Parent Product:', 'roots' ),
		'all_items'           => __( 'All Products', 'roots' ),
		'view_item'           => __( 'View Product', 'roots' ),
		'add_new_item'        => __( 'Add New Product', 'roots' ),
		'add_new'             => __( 'New Product', 'roots' ),
		'edit_item'           => __( 'Edit Product', 'roots' ),
		'update_item'         => __( 'Update Product', 'roots' ),
		'search_items'        => __( 'Search products', 'roots' ),
		'not_found'           => __( 'No products found', 'roots' ),
		'not_found_in_trash'  => __( 'No products found in Trash', 'roots' ),
	);

	$args = array(
		'label'               => __( 'product', 'roots' ),
		'description'         => __( 'Product information pages', 'roots' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'custom-fields', 'page-attributes', ),
		'hierarchical'        => true,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 6,
		'menu_icon'           => '/assets/atkore-admin-icon.png',
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'page',
	);

	register_post_type( 'product', $args );
	
	$labels = array(
		'name'                => _x( 'Sequences', 'Post Type General Name', 'roots' ),
		'singular_name'       => _x( 'Sequence', 'Post Type Singular Name', 'roots' ),
		'menu_name'           => __( 'Sequence', 'roots' ),
		'parent_item_colon'   => __( 'Parent Sequence:', 'roots' ),
		'all_items'           => __( 'All Sequences', 'roots' ),
		'view_item'           => __( 'View Sequence', 'roots' ),
		'add_new_item'        => __( 'Add New Sequence', 'roots' ),
		'add_new'             => __( 'New Sequence', 'roots' ),
		'edit_item'           => __( 'Edit Sequence', 'roots' ),
		'update_item'         => __( 'Update Sequence', 'roots' ),
		'search_items'        => __( 'Search Sequences', 'roots' ),
		'not_found'           => __( 'No Sequences found', 'roots' ),
		'not_found_in_trash'  => __( 'No Sequences found in Trash', 'roots' ),
	);

	$args = array(
		'label'               => __( 'sequence', 'roots' ),
		'description'         => __( 'sequence information pages', 'roots' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'custom-fields', 'page-attributes', ),
		'hierarchical'        => true,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 7,
		'menu_icon'           => '/assets/atkore-admin-icon.png',
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'post',
	);

	register_post_type( 'sequence', $args );
}

// Hook into the 'init' action
add_action( 'init', 'atkore_post_types', 0 );

}