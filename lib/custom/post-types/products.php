<?php
/*
Plugin Name: Atkore Post Types: Products
Plugin URI: http://atkore.com
Description: Adds products as a custom post type
Version: 1.0
Author: Maintain Web
Author URI: http://maintainweb.co/
License: GPL
Copyright: Maintain Web
*/

if ( ! function_exists('atkore_post_type_products') ) {

// Register Custom Post Types
function atkore_post_type_products() {
    $admin_img_path = '//atkore.com/assets/img/atkore-admin-icon.png';

  	$labels = array(
  		'name'                => _x( 'Products', 'Post Type General Name', 'atkore' ),
  		'singular_name'       => _x( 'Product', 'Post Type Singular Name', 'atkore' ),
  		'menu_name'           => __( 'Products', 'atkore' ),
  		'parent_item_colon'   => __( 'Parent Product:', 'atkore' ),
  		'all_items'           => __( 'All Products', 'atkore' ),
  		'view_item'           => __( 'View Product', 'atkore' ),
  		'add_new_item'        => __( 'Add New Product', 'atkore' ),
  		'add_new'             => __( 'New Product', 'atkore' ),
  		'edit_item'           => __( 'Edit Product', 'atkore' ),
  		'update_item'         => __( 'Update Product', 'atkore' ),
  		'search_items'        => __( 'Search products', 'atkore' ),
  		'not_found'           => __( 'No products found', 'atkore' ),
  		'not_found_in_trash'  => __( 'No products found in Trash', 'atkore' ),
  	);
  	
  	$rewrite = array(
  	  'slug'                => 'product',
  	  'with_front'          => false,
  	  'hierarchical'        => true,
  	);

  	$args = array(
  		'label'                 => __( 'product', 'atkore' ),
  		'description'           => __( 'Product information pages', 'atkore' ),
  		'labels'                => $labels,
  		'supports'              => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'custom-fields', 'page-attributes', ),
  		'taxonomies'            => array(),
  		'hierarchical'          => true,
  		'public'                => true,
  		'show_ui'               => true,
  		'show_in_menu'          => true,
  		'show_in_nav_menus'     => true,
  		'show_in_admin_bar'     => true,
  		'menu_position'         => 20,
  		'menu_icon'             => $admin_img_path,
  		'can_export'            => true,
  		'has_archive'           => true,
  		'exclude_from_search'   => false,
  		'publicly_queryable'    => true,
  		'capability_type'       => 'page',
  		'rewrite'               => $rewrite,
  		//'capabilities'          => $capabilities,
  	);

  	register_post_type( 'product', $args );

  	$labels = array(
  		'name'                       => _x( 'Brand', 'Taxonomy General Name', 'atkore' ),
  		'singular_name'              => _x( 'Brand', 'Taxonomy Singular Name', 'atkore' ),
  		'menu_name'                  => __( 'Brands', 'atkore' ),
  		'all_items'                  => __( 'All Brands', 'atkore' ),
  		'parent_item'                => __( 'Parent Brand', 'atkore' ),
  		'parent_item_colon'          => __( 'Parent Brand:', 'atkore' ),
  		'new_item_name'              => __( 'New Brand Name', 'atkore' ),
  		'add_new_item'               => __( 'Add New Brand', 'atkore' ),
  		'edit_item'                  => __( 'Edit Brand', 'atkore' ),
  		'update_item'                => __( 'Update Brand', 'atkore' ),
  		'separate_items_with_commas' => __( 'Separate Brand with commas', 'atkore' ),
  		'search_items'               => __( 'Search Brands', 'atkore' ),
  		'add_or_remove_items'        => __( 'Add or remove Brand', 'atkore' ),
  		'choose_from_most_used'      => __( 'Choose from the most used Brands', 'atkore' ),
  	);

  	$capabilities = array(
  		'manage_terms'               => 'manage_categories',
  		'edit_terms'                 => 'manage_categories',
  		'delete_terms'               => 'manage_categories',
  		'assign_terms'               => 'edit_posts',
  	);
  	
  	$rewrite = array(
  	  'slug'                => 'brand',
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
  		'query_var'                  => 'brand',
  		'rewrite'                    => $rewrite,
  		//'capabilities'               => $capabilities,
  	);

  	register_taxonomy( 'product_brand', 'product', $args );

  	$labels = array(
  		'name'                       => _x( 'Product Category', 'Taxonomy General Name', 'atkore' ),
  		'singular_name'              => _x( 'Product Category', 'Taxonomy Singular Name', 'atkore' ),
  		'menu_name'                  => __( 'Product Categories', 'atkore' ),
  		'all_items'                  => __( 'All Categories', 'atkore' ),
  		'parent_item'                => __( 'Parent Category', 'atkore' ),
  		'parent_item_colon'          => __( 'Parent Category:', 'atkore' ),
  		'new_item_name'              => __( 'New Category Name', 'atkore' ),
  		'add_new_item'               => __( 'Add New Category', 'atkore' ),
  		'edit_item'                  => __( 'Edit Category', 'atkore' ),
  		'update_item'                => __( 'Update Category', 'atkore' ),
  		'separate_items_with_commas' => __( 'Separate Category with commas', 'atkore' ),
  		'search_items'               => __( 'Search Product Categories', 'atkore' ),
  		'add_or_remove_items'        => __( 'Add or remove Category', 'atkore' ),
  		'choose_from_most_used'      => __( 'Choose from the most used Product Categories', 'atkore' ),
  	);

  	$capabilities = array(
  		'manage_terms'               => 'manage_categories',
  		'edit_terms'                 => 'manage_categories',
  		'delete_terms'               => 'manage_categories',
  		'assign_terms'               => 'edit_posts',
  	);
  	
  	$rewrite = array(
  	  'slug'                => 'product-category',
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
  		'query_var'                  => 'product_cat',
  		'rewrite'                    => $rewrite,
  		//'capabilities'               => $capabilities,
  	);

  	register_taxonomy( 'product_cat', 'product', $args );

}
// Hook into the 'init' action
add_action( 'init', 'atkore_post_type_products', 0 );

}