<?php
/*
Plugin Name: Atkore Post Types: Media
Plugin URI: http://atkore.com
Description: Adds taxonomy to attachments (Media)
Version: 1.0
Author: Maintain Web
Author URI: http://maintainweb.co/
License: GPL
Copyright: Maintain Web
*/

if ( ! function_exists('atkore_post_type_attachment') ) {

// Register Custom Post Types
function atkore_post_type_attachment() {
    $admin_img_path = 'http://atkore.com/assets/img/atkore-admin-icon.png';
  	
  	$labels = array(
  		'name'                       => _x( 'Media Type', 'Taxonomy General Name', 'atkore' ),
  		'singular_name'              => _x( 'Media Type', 'Taxonomy Singular Name', 'atkore' ),
  		'menu_name'                  => __( 'Media Types', 'atkore' ),
  		'all_items'                  => __( 'All Media Types', 'atkore' ),
  		'parent_item'                => __( 'Parent Media Type', 'atkore' ),
  		'parent_item_colon'          => __( 'Parent Media Type:', 'atkore' ),
  		'new_item_name'              => __( 'New Media Type Name', 'atkore' ),
  		'add_new_item'               => __( 'Add New Media Type', 'atkore' ),
  		'edit_item'                  => __( 'Edit Media Type', 'atkore' ),
  		'update_item'                => __( 'Update Media Type', 'atkore' ),
  		'separate_items_with_commas' => __( 'Separate Media Types with commas', 'atkore' ),
  		'search_items'               => __( 'Search Media Types', 'atkore' ),
  		'add_or_remove_items'        => __( 'Add or remove Media Types', 'atkore' ),
  		'choose_from_most_used'      => __( 'Choose from the most used Media Types', 'atkore' ),
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
  		'show_tagcloud'              => false,
  		'query_var'                  => 'media-type',
  		'rewrite'                    => false,
  		'capabilities'               => $capabilities,
  	);

  	register_taxonomy( 'media-type', 'attachment', $args );

}

// Hook into the 'init' action
add_action( 'init', 'atkore_post_type_attachment', 0 );

}