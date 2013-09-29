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
  		'name'                       => _x( 'Media Type', 'Taxonomy General Name', 'roots' ),
  		'singular_name'              => _x( 'Media Type', 'Taxonomy Singular Name', 'roots' ),
  		'menu_name'                  => __( 'Media Types', 'roots' ),
  		'all_items'                  => __( 'All Media Types', 'roots' ),
  		'parent_item'                => __( 'Parent Media Type', 'roots' ),
  		'parent_item_colon'          => __( 'Parent Media Type:', 'roots' ),
  		'new_item_name'              => __( 'New Media Type Name', 'roots' ),
  		'add_new_item'               => __( 'Add New Media Type', 'roots' ),
  		'edit_item'                  => __( 'Edit Media Type', 'roots' ),
  		'update_item'                => __( 'Update Media Type', 'roots' ),
  		'separate_items_with_commas' => __( 'Separate Media Types with commas', 'roots' ),
  		'search_items'               => __( 'Search Media Types', 'roots' ),
  		'add_or_remove_items'        => __( 'Add or remove Media Types', 'roots' ),
  		'choose_from_most_used'      => __( 'Choose from the most used Media Types', 'roots' ),
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