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
    $admin_img_path = 'http://atkore.com/assets/img/atkore-admin-icon.png';

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

  	$capabilities = array(
  		'manage_terms'               => 'manage_categories',
  		'edit_terms'                 => 'manage_categories',
  		'delete_terms'               => 'manage_categories',
  		'assign_terms'               => 'edit_posts',
  	);
  	
  	$rewrite = array(
  	  'slug'                => 'product',
  	  'with_front'          => false,
  	  'hierarchical'        => true,
  	);

  	$args = array(
  		'label'                 => __( 'product', 'roots' ),
  		'description'           => __( 'Product information pages', 'roots' ),
  		'labels'                => $labels,
  		'supports'              => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'custom-fields', 'page-attributes', ),
  		'taxonomies'            => array( 'product-category','shopp_tag','shopp_country','shopp_brand'),
  		'hierarchical'          => true,
  		'public'                => true,
  		'show_ui'               => true,
  		'show_in_menu'          => true,
  		'show_in_nav_menus'     => true,
  		'show_in_admin_bar'     => true,
  		'menu_position'         => 35,
  		'menu_icon'             => $admin_img_path,
  		'can_export'            => true,
  		'has_archive'           => true,
  		'exclude_from_search'   => false,
  		'publicly_queryable'    => true,
  		'capability_type'       => 'page',
  		'rewrite'               => $rewrite,
  		'capabilities'          => $capabilities,
  	);

  	register_post_type( 'product', $args );

  	$labels = array(
  		'name'                       => _x( 'Brand', 'Taxonomy General Name', 'roots' ),
  		'singular_name'              => _x( 'Brand', 'Taxonomy Singular Name', 'roots' ),
  		'menu_name'                  => __( 'Brands', 'roots' ),
  		'all_items'                  => __( 'All Brands', 'roots' ),
  		'parent_item'                => __( 'Parent Brand', 'roots' ),
  		'parent_item_colon'          => __( 'Parent Brand:', 'roots' ),
  		'new_item_name'              => __( 'New Brand Name', 'roots' ),
  		'add_new_item'               => __( 'Add New Brand', 'roots' ),
  		'edit_item'                  => __( 'Edit Brand', 'roots' ),
  		'update_item'                => __( 'Update Brand', 'roots' ),
  		'separate_items_with_commas' => __( 'Separate Brand with commas', 'roots' ),
  		'search_items'               => __( 'Search Brands', 'roots' ),
  		'add_or_remove_items'        => __( 'Add or remove Brand', 'roots' ),
  		'choose_from_most_used'      => __( 'Choose from the most used Brands', 'roots' ),
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
  		'capabilities'               => $capabilities,
  	);

  	register_taxonomy( 'shopp_brand', 'product', $args );

  	$labels = array(
  		'name'                       => _x( 'Country', 'Taxonomy General Name', 'roots' ),
  		'singular_name'              => _x( 'Country', 'Taxonomy Singular Name', 'roots' ),
  		'menu_name'                  => __( 'Countries', 'roots' ),
  		'all_items'                  => __( 'All Countries', 'roots' ),
  		'parent_item'                => __( 'Parent Country', 'roots' ),
  		'parent_item_colon'          => __( 'Parent Country:', 'roots' ),
  		'new_item_name'              => __( 'New Country Name', 'roots' ),
  		'add_new_item'               => __( 'Add New Country', 'roots' ),
  		'edit_item'                  => __( 'Edit Country', 'roots' ),
  		'update_item'                => __( 'Update Country', 'roots' ),
  		'separate_items_with_commas' => __( 'Separate Country with commas', 'roots' ),
  		'search_items'               => __( 'Search Countries', 'roots' ),
  		'add_or_remove_items'        => __( 'Add or remove Country', 'roots' ),
  		'choose_from_most_used'      => __( 'Choose from the most used Countries', 'roots' ),
  	);

  	$capabilities = array(
  		'manage_terms'               => 'manage_categories',
  		'edit_terms'                 => 'manage_categories',
  		'delete_terms'               => 'manage_categories',
  		'assign_terms'               => 'edit_posts',
  	);
  	
  	$rewrite = array(
  	  'slug'                => 'country',
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
  		'query_var'                  => 'country',
  		'rewrite'                    => $rewrite,
  		'capabilities'               => $capabilities,
  	);

  	register_taxonomy( 'shopp_country', 'product', $args );

  	$labels = array(
  		'name'                       => _x( 'Product Category', 'Taxonomy General Name', 'roots' ),
  		'singular_name'              => _x( 'Product Category', 'Taxonomy Singular Name', 'roots' ),
  		'menu_name'                  => __( 'Product Categories', 'roots' ),
  		'all_items'                  => __( 'All Categories', 'roots' ),
  		'parent_item'                => __( 'Parent Category', 'roots' ),
  		'parent_item_colon'          => __( 'Parent Category:', 'roots' ),
  		'new_item_name'              => __( 'New Category Name', 'roots' ),
  		'add_new_item'               => __( 'Add New Category', 'roots' ),
  		'edit_item'                  => __( 'Edit Category', 'roots' ),
  		'update_item'                => __( 'Update Category', 'roots' ),
  		'separate_items_with_commas' => __( 'Separate Category with commas', 'roots' ),
  		'search_items'               => __( 'Search Product Categories', 'roots' ),
  		'add_or_remove_items'        => __( 'Add or remove Category', 'roots' ),
  		'choose_from_most_used'      => __( 'Choose from the most used Product Categories', 'roots' ),
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
  		'query_var'                  => 'category',
  		'rewrite'                    => $rewrite,
  		'capabilities'               => $capabilities,
  	);

  	register_taxonomy( 'product-category', 'product', $args );
  	
  	$labels = array(
  		'name'                       => _x( 'Tag', 'Taxonomy General Name', 'roots' ),
  		'singular_name'              => _x( 'Tag', 'Taxonomy Singular Name', 'roots' ),
  		'menu_name'                  => __( 'Tags', 'roots' ),
  		'all_items'                  => __( 'All Tags', 'roots' ),
  		'parent_item'                => __( 'Parent Tag', 'roots' ),
  		'parent_item_colon'          => __( 'Parent Tag:', 'roots' ),
  		'new_item_name'              => __( 'New Tag Name', 'roots' ),
  		'add_new_item'               => __( 'Add New Tag', 'roots' ),
  		'edit_item'                  => __( 'Edit Tag', 'roots' ),
  		'update_item'                => __( 'Update Tag', 'roots' ),
  		'separate_items_with_commas' => __( 'Separate Tag with commas', 'roots' ),
  		'search_items'               => __( 'Search Tags', 'roots' ),
  		'add_or_remove_items'        => __( 'Add or remove Tag', 'roots' ),
  		'choose_from_most_used'      => __( 'Choose from the most used Tags', 'roots' ),
  	);

  	$capabilities = array(
  		'manage_terms'               => 'manage_categories',
  		'edit_terms'                 => 'manage_categories',
  		'delete_terms'               => 'manage_categories',
  		'assign_terms'               => 'edit_posts',
  	);
  	
  	$rewrite = array(
  	  'slug'                => 'tag',
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
  		'query_var'                  => 'tag',
  		'rewrite'                    => $rewrite,
  		'capabilities'               => $capabilities,
  	);

  	register_taxonomy( 'shopp_tag', 'product', $args );

  	$labels = array(
  		'name'                       => _x( 'Finish', 'Taxonomy General Name', 'roots' ),
  		'singular_name'              => _x( 'Finish', 'Taxonomy Singular Name', 'roots' ),
  		'menu_name'                  => __( 'Finishes', 'roots' ),
  		'all_items'                  => __( 'All Finishes', 'roots' ),
  		'parent_item'                => __( 'Parent Finish', 'roots' ),
  		'parent_item_colon'          => __( 'Parent Finish:', 'roots' ),
  		'new_item_name'              => __( 'New Finish Name', 'roots' ),
  		'add_new_item'               => __( 'Add New Finish', 'roots' ),
  		'edit_item'                  => __( 'Edit Finish', 'roots' ),
  		'update_item'                => __( 'Update Finish', 'roots' ),
  		'separate_items_with_commas' => __( 'Separate Finish with commas', 'roots' ),
  		'search_items'               => __( 'Search Finishes', 'roots' ),
  		'add_or_remove_items'        => __( 'Add or remove Finish', 'roots' ),
  		'choose_from_most_used'      => __( 'Choose from the most used Finishes', 'roots' ),
  	);

  	$capabilities = array(
  		'manage_terms'               => 'manage_categories',
  		'edit_terms'                 => 'manage_categories',
  		'delete_terms'               => 'manage_categories',
  		'assign_terms'               => 'edit_posts',
  	);
  	
  	$rewrite = array(
  	  'slug'                => 'finish',
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
  		'query_var'                  => 'finish',
  		'rewrite'                    => $rewrite,
  		'capabilities'               => $capabilities,
  	);

  	register_taxonomy( 'shopp_finish', 'product', $args );

  	$labels = array(
  		'name'                       => _x( 'Group', 'Taxonomy General Name', 'roots' ),
  		'singular_name'              => _x( 'Group', 'Taxonomy Singular Name', 'roots' ),
  		'menu_name'                  => __( 'Groups', 'roots' ),
  		'all_items'                  => __( 'All Groups', 'roots' ),
  		'parent_item'                => __( 'Parent Group', 'roots' ),
  		'parent_item_colon'          => __( 'Parent Group:', 'roots' ),
  		'new_item_name'              => __( 'New Group Name', 'roots' ),
  		'add_new_item'               => __( 'Add New Group', 'roots' ),
  		'edit_item'                  => __( 'Edit Group', 'roots' ),
  		'update_item'                => __( 'Update Group', 'roots' ),
  		'separate_items_with_commas' => __( 'Separate Group with commas', 'roots' ),
  		'search_items'               => __( 'Search Groups', 'roots' ),
  		'add_or_remove_items'        => __( 'Add or remove Group', 'roots' ),
  		'choose_from_most_used'      => __( 'Choose from the most used Groups', 'roots' ),
  	);

  	$capabilities = array(
  		'manage_terms'               => 'manage_categories',
  		'edit_terms'                 => 'manage_categories',
  		'delete_terms'               => 'manage_categories',
  		'assign_terms'               => 'edit_posts',
  	);
  	
  	$rewrite = array(
  	  'slug'                => 'group',
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
  		'query_var'                  => 'group',
  		'rewrite'                    => $rewrite,
  		'capabilities'               => $capabilities,
  	);

  	register_taxonomy( 'shopp_group', 'product', $args );


}
// Hook into the 'init' action
add_action( 'init', 'atkore_post_type_products', 0 );

}