<?php
/**
 * Atkore functions
 */
if ( ! function_exists('atkore_post_types') ) {

// Register Custom Post Types
function atkore_post_types() {
  $admin_img_path = get_stylesheet_directory_uri() . '/assets/img/atkore-admin-icon.png';

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
		'menu_position'       => 5,
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


	// Products Post Type	
	$labels = array(
		'name'                => _x( 'Products', 'Post Type General Name', 'roots' ),
		'singular_name'       => _x( 'Product', 'Post Type Singular Name', 'roots' ),
		'menu_name'           => __( 'Products', 'roots' ),
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
		'taxonomies'          => array( 'category','post_tag'),
		'hierarchical'        => true,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 6,
		'menu_icon'           => $admin_img_path,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'page',
	);

	register_post_type( 'product', $args );

	// Sequense	Post Type
	$labels = array(
		'name'                => _x( 'Sequences', 'Post Type General Name', 'roots' ),
		'singular_name'       => _x( 'Sequence', 'Post Type Singular Name', 'roots' ),
		'menu_name'           => __( 'Sequences', 'roots' ),
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
		'menu_icon'           => $admin_img_path,
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => false,
		'capability_type'     => 'post',
	);

	register_post_type( 'sequence', $args );

	// Resources Post Type
	$labels = array(
		'name'                => _x( 'Resources', 'Post Type General Name', 'roots' ),
		'singular_name'       => _x( 'Resources', 'Post Type Singular Name', 'roots' ),
		'menu_name'           => __( 'Resources', 'roots' ),
		'parent_item_colon'   => __( 'Parent Resource:', 'roots' ),
		'all_items'           => __( 'All Resources', 'roots' ),
		'view_item'           => __( 'View Resource', 'roots' ),
		'add_new_item'        => __( 'Add New Resource', 'roots' ),
		'add_new'             => __( 'New Resource', 'roots' ),
		'edit_item'           => __( 'Edit Resource', 'roots' ),
		'update_item'         => __( 'Update Resource', 'roots' ),
		'search_items'        => __( 'Search Resources', 'roots' ),
		'not_found'           => __( 'Nothing found', 'roots' ),
		'not_found_in_trash'  => __( 'Nothing found in Trash', 'roots' ),
	);
	
	$rewrite = array(
	'slug'                  => 'resources',
	);

	$args = array(
		'label'               => __( 'Resources', 'roots' ),
		'description'         => __( 'Resources information pages', 'roots' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'custom-fields', 'page-attributes', 'post-formats',),
		'taxonomies'          => array( 'category','post_tag'),
		'rewrite'             => $rewrite,
		'hierarchical'        => true,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 7,
		'menu_icon'           => $admin_img_path,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'post',
	);

	register_post_type( 'resource', $args );


}

// Hook into the 'init' action
add_action( 'init', 'atkore_post_types', 0 );

}