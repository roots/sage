<?php
/*
Plugin Name: Atkore Post Types: Projects
Plugin URI: http://atkore.com
Description: Adds projects as a custom post type
Version: 1.0
Author: Maintain Web
Author URI: http://maintainweb.co/
License: GPL
Copyright: Maintain Web
*/
// http://wp.tutsplus.com/tutorials/creative-coding/the-rewrite-api-post-types-taxonomies/
//add_rewrite_tag('%project%','([^/]+)','post_type=');
//add_rewrite_tag('%project_cat%','([^/]+)','project_cat=');

//add_permastruct('project', '%project_cat%/%project%');
//add_permastruct('project_cat', '/%project_cat%');

if ( ! function_exists('atkore_post_type_projects') ) {

// Register Custom Post Types
function atkore_post_type_projects() {
    $admin_img_path = 'http://atkore.com/assets/img/atkore-admin-icon.png';

 	$labels = array(
  		'name'                => _x( 'Projects', 'Post Type General Name', 'atkore' ),
  		'singular_name'       => _x( 'Project', 'Post Type Singular Name', 'atkore' ),
  		'menu_name'           => __( 'Projects', 'atkore' ),
  		'parent_item_colon'   => __( 'Parent Project:', 'atkore' ),
  		'all_items'           => __( 'All Projects', 'atkore' ),
  		'view_item'           => __( 'View Project', 'atkore' ),
  		'add_new_item'        => __( 'Add New Project', 'atkore' ),
  		'add_new'             => __( 'New Project', 'atkore' ),
  		'edit_item'           => __( 'Edit Project', 'atkore' ),
  		'update_item'         => __( 'Update Project', 'atkore' ),
  		'search_items'        => __( 'Search projects', 'atkore' ),
  		'not_found'           => __( 'No projects found', 'atkore' ),
  		'not_found_in_trash'  => __( 'No projects found in Trash', 'atkore' ),
  	);

	$rewrite = array(
		'slug'                => 'projects',
		'with_front'          => false,
		'pages'               => true,
		'feeds'               => true,
	);

  	$args = array(
  		'label'                 => __( 'Project', 'atkore' ),
  		'description'           => __( 'Project information pages', 'atkore' ),
  		'labels'                => $labels,
  		'supports'              => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'custom-fields', 'page-attributes', ),
  		'taxonomies'            => array('project_cat', 'project_tag'),
  		'hierarchical'          => true,
  		'public'                => true,
  		'show_ui'               => true,
  		'show_in_menu'          => true,
  		'show_in_nav_menus'     => true,
  		'show_in_admin_bar'     => true,
  		'menu_position'         => 50,
  		'menu_icon'             => $admin_img_path,
  		'can_export'            => true,
  		'has_archive'           => true,
  		'exclude_from_search'   => false,
  		'publicly_queryable'    => true,
  		'capability_type'       => 'page',
   		'rewrite'               => $rewrite,
  	);

  	register_post_type( 'project', $args );

  	$labels = array(
  		'name'                       => _x( 'Project Category', 'Taxonomy General Name', 'atkore' ),
  		'singular_name'              => _x( 'Project Category', 'Taxonomy Singular Name', 'atkore' ),
  		'menu_name'                  => __( 'Project Categories', 'atkore' ),
  		'all_items'                  => __( 'All Categories', 'atkore' ),
  		'parent_item'                => __( 'Parent Category', 'atkore' ),
  		'parent_item_colon'          => __( 'Parent Category:', 'atkore' ),
  		'new_item_name'              => __( 'New Category Name', 'atkore' ),
  		'add_new_item'               => __( 'Add New Category', 'atkore' ),
  		'edit_item'                  => __( 'Edit Category', 'atkore' ),
  		'update_item'                => __( 'Update Category', 'atkore' ),
  		'separate_items_with_commas' => __( 'Separate Category with commas', 'atkore' ),
  		'search_items'               => __( 'Search Project Categories', 'atkore' ),
  		'add_or_remove_items'        => __( 'Add or remove Category', 'atkore' ),
  		'choose_from_most_used'      => __( 'Choose from the most used Project Categories', 'atkore' ),
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
  		'query_var'                  => 'project_cat',
  		//'rewrite'                    => $rewrite,
  	);

  	register_taxonomy( 'project_cat', 'project', $args );

  	$labels = array(
  		'name'                       => _x( 'Project Tag', 'Taxonomy General Name', 'atkore' ),
  		'singular_name'              => _x( 'Project Tag', 'Taxonomy Singular Name', 'atkore' ),
  		'menu_name'                  => __( 'Project Tags', 'atkore' ),
  		'all_items'                  => __( 'All Project Tags', 'atkore' ),
  		'parent_item'                => __( 'Parent Project Tag', 'atkore' ),
  		'parent_item_colon'          => __( 'Parent Project Tag:', 'atkore' ),
  		'new_item_name'              => __( 'New Project Tag Name', 'atkore' ),
  		'add_new_item'               => __( 'Add New Project Tag', 'atkore' ),
  		'edit_item'                  => __( 'Edit Project Tag', 'atkore' ),
  		'update_item'                => __( 'Update Project Tag', 'atkore' ),
  		'separate_items_with_commas' => __( 'Separate project tag with commas', 'atkore' ),
  		'search_items'               => __( 'Search Project Tags', 'atkore' ),
  		'add_or_remove_items'        => __( 'Add or remove project tags', 'atkore' ),
  		'choose_from_most_used'      => __( 'Choose from the most used project tags', 'atkore' ),
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
  		'query_var'                  => 'project_tag',
  		//'rewrite'                    => $rewrite,
  	);

  	register_taxonomy( 'project_tag', 'project', $args );

}
// Hook into the 'init' action
add_action( 'init', 'atkore_post_type_projects', 0 );

}