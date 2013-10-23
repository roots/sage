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
// http://wp.tutsplus.com/tutorials/creative-coding/the-rewrite-api-post-types-taxonomies/
//add_rewrite_tag('%product%','([^/]+)','post_type=');
//add_rewrite_tag('%product_cat%','([^/]+)','product_cat=');

//add_permastruct('product', '%product_cat%/%product%');
//add_permastruct('product_cat', '/%product_cat%');

if ( ! function_exists('atkore_post_type_products') ) {

// Register Custom Post Types
function atkore_post_type_products() {
    $admin_img_path = 'http://atkore.com/assets/img/atkore-admin-icon.png';

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
/*
	$rewrite = array(
		'slug'                => '',
		'with_front'          => false,
		'pages'               => true,
		'feeds'               => true,
	);
*/
  	$args = array(
  		'label'                 => __( 'Product', 'atkore' ),
  		'description'           => __( 'Product information pages', 'atkore' ),
  		'labels'                => $labels,
  		'supports'              => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'custom-fields', 'page-attributes', ),
  		'taxonomies'            => array('product_brand', 'product_cat', 'product_tag'),
  		'hierarchical'          => true,
  		'public'                => true,
  		'show_ui'               => true,
  		'show_in_menu'          => true,
  		'show_in_nav_menus'     => true,
  		'show_in_admin_bar'     => true,
  		'menu_position'         => 5,
  		'menu_icon'             => $admin_img_path,
  		'can_export'            => true,
  		'has_archive'           => true,
  		'exclude_from_search'   => false,
  		'publicly_queryable'    => true,
  		'capability_type'       => 'page',
   		//'rewrite'               => $rewrite,
  	);

  	register_post_type( 'product', $args );

  	$labels = array(
  		'name'                       => _x( 'Product Brand', 'Taxonomy General Name', 'atkore' ),
  		'singular_name'              => _x( 'Product Brand', 'Taxonomy Singular Name', 'atkore' ),
  		'menu_name'                  => __( 'Product Brands', 'atkore' ),
  		'all_items'                  => __( 'All Product Brands', 'atkore' ),
  		'parent_item'                => __( 'Parent Product Brand', 'atkore' ),
  		'parent_item_colon'          => __( 'Parent Product Brand:', 'atkore' ),
  		'new_item_name'              => __( 'New Product Brand Name', 'atkore' ),
  		'add_new_item'               => __( 'Add New Product Brand', 'atkore' ),
  		'edit_item'                  => __( 'Edit Product Brand', 'atkore' ),
  		'update_item'                => __( 'Update Product Brand', 'atkore' ),
  		'separate_items_with_commas' => __( 'Separate Product Brand with commas', 'atkore' ),
  		'search_items'               => __( 'Search Product Brands', 'atkore' ),
  		'add_or_remove_items'        => __( 'Add or remove Product Brand', 'atkore' ),
  		'choose_from_most_used'      => __( 'Choose from the most used Product Brands', 'atkore' ),
  	);
/*
  	$rewrite = array(
  	  'slug'                => '',
  	  'with_front'          => false,
  	  'hierarchical'        => true,
  	);
*/
  	$args = array(
  		'labels'                     => $labels,
  		'hierarchical'               => true,
  		'public'                     => true,
  		'show_ui'                    => true,
  		'show_admin_column'          => true,
  		'show_in_nav_menus'          => true,
  		'show_tagcloud'              => true,
  		'query_var'                  => 'product_brand',
  		//'rewrite'                    => $rewrite,
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

/*
  	$rewrite = array(
  	  'slug'                => '',
  	  'with_front'          => false,
  	  'hierarchical'        => true,
  	);
*/
  	$args = array(
  		'labels'                     => $labels,
  		'hierarchical'               => true,
  		'public'                     => true,
  		'show_ui'                    => true,
  		'show_admin_column'          => true,
  		'show_in_nav_menus'          => true,
  		'show_tagcloud'              => true,
  		'query_var'                  => 'product_cat',
  		//'rewrite'                    => $rewrite,
  	);

  	register_taxonomy( 'product_cat', 'product', $args );

  	$labels = array(
  		'name'                       => _x( 'Product Tag', 'Taxonomy General Name', 'atkore' ),
  		'singular_name'              => _x( 'Product Tag', 'Taxonomy Singular Name', 'atkore' ),
  		'menu_name'                  => __( 'Product Tags', 'atkore' ),
  		'all_items'                  => __( 'All Product Tags', 'atkore' ),
  		'parent_item'                => __( 'Parent Product Tag', 'atkore' ),
  		'parent_item_colon'          => __( 'Parent Product Tag:', 'atkore' ),
  		'new_item_name'              => __( 'New Product Tag Name', 'atkore' ),
  		'add_new_item'               => __( 'Add New Product Tag', 'atkore' ),
  		'edit_item'                  => __( 'Edit Product Tag', 'atkore' ),
  		'update_item'                => __( 'Update Product Tag', 'atkore' ),
  		'separate_items_with_commas' => __( 'Separate product tag with commas', 'atkore' ),
  		'search_items'               => __( 'Search Product Tags', 'atkore' ),
  		'add_or_remove_items'        => __( 'Add or remove product tags', 'atkore' ),
  		'choose_from_most_used'      => __( 'Choose from the most used product tags', 'atkore' ),
  	);
/*
  	$rewrite = array(
  	  'slug'                => '',
  	  'with_front'          => false,
  	  'hierarchical'        => true,
  	);
*/
  	$args = array(
  		'labels'                     => $labels,
  		'hierarchical'               => false,
  		'public'                     => true,
  		'show_ui'                    => true,
  		'show_admin_column'          => true,
  		'show_in_nav_menus'          => true,
  		'show_tagcloud'              => true,
  		'query_var'                  => 'product_tag',
  		//'rewrite'                    => $rewrite,
  	);

  	register_taxonomy( 'product_tag', 'product', $args );

}
// Hook into the 'init' action
add_action( 'init', 'atkore_post_type_products', 0 );

}