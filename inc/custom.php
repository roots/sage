<?php

// Custom functions
function codex_custom_init() {
  $labels = array(
    'name' => _x('Books', 'post type general name'),
    'singular_name' => _x('Book', 'post type singular name'),
    'add_new' => _x('Add New', 'book'),
    'add_new_item' => __('Add New Book'),
    'edit_item' => __('Edit Book'),
    'new_item' => __('New Book'),
    'all_items' => __('All Books'),
    'view_item' => __('View Book'),
    'search_items' => __('Search Books'),
    'not_found' =>  __('No books found'),
    'not_found_in_trash' => __('No books found in Trash'),
    'parent_item_colon' => '',
    'menu_name' => 'Books'

  );
  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true,
    'show_in_menu' => true,
    'query_var' => true,
    'rewrite' => true,
    'capability_type' => 'post',
    'has_archive' => true,
    'hierarchical' => false,
    'menu_position' => null,
    'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
  );
  register_post_type('book',$args);
}
add_action( 'init', 'codex_custom_init' );