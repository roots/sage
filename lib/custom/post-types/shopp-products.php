<?php
/*
Plugin Name: Atkore Post Types: Shopp Product Additions
Plugin URI: http://atkore.com
Description: Adds custom taxonomies to the Shopp Product custom post type
Version: 1.0
Author: Maintain Web
Author URI: http://maintainweb.co/
License: GPL
Copyright: Maintain Web
*/

function register_shopp_taxonomies () {
    shopp_register_taxonomy('brand', array(
        'hierarchical' => true,
        'labels' => array(
        'name' => __('Brands','Shopp'),
        'singular_name' => __('Brand','Shopp'),
        'search_items' => __('Search Brands','Shopp'),
        'popular_items' => __('Popular','Shopp'),
        'all_items' => __('Show All','Shopp'),
        'parent_item' => __('Parent Brand','Shopp'),
        'parent_item_colon' => __('Parent Brand:','Shopp'),
        'edit_item' => __('Edit Brand','Shopp'),
        'update_item' => __('Update Brand','Shopp'),
        'add_new_item' => __('New Brand','Shopp'),
        'new_item_name' => __('New Brand Name','Shopp'),
        'separate_items_with_commas' => __('Separate brands with commas','Shopp'),
        'add_or_remove_items' => __('Add or remove brands','Shopp'),
        'choose_from_most_used' => __('Choose from the most used brands','Shopp')
    ),
    'show_ui' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'brands' ),
    ));

    shopp_register_taxonomy('country', array(
        'hierarchical' => true,
        'labels' => array(
        'name' => __('Countries','Shopp'),
        'singular_name' => __('Country','Shopp'),
        'search_items' => __('Search Countries','Shopp'),
        'popular_items' => __('Popular','Shopp'),
        'all_items' => __('Show All','Shopp'),
        'parent_item' => __('Parent Country','Shopp'),
        'parent_item_colon' => __('Parent Country:','Shopp'),
        'edit_item' => __('Edit Country','Shopp'),
        'update_item' => __('Update Country','Shopp'),
        'add_new_item' => __('New Country','Shopp'),
        'new_item_name' => __('New Country Name','Shopp'),
        'separate_items_with_commas' => __('Separate Countries with commas','Shopp'),
        'add_or_remove_items' => __('Add or remove Countries','Shopp'),
        'choose_from_most_used' => __('Choose from the most used Countries','Shopp')
    ),
    'show_ui' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'countries' ),
    ));

    shopp_register_taxonomy('finish', array(
        'hierarchical' => true,
        'labels' => array(
        'name' => __('Finishes','Shopp'),
        'singular_name' => __('Finish','Shopp'),
        'search_items' => __('Search finishes','Shopp'),
        'popular_items' => __('Popular','Shopp'),
        'all_items' => __('Show All','Shopp'),
        'parent_item' => __('Parent finish','Shopp'),
        'parent_item_colon' => __('Parent finish:','Shopp'),
        'edit_item' => __('Edit finish','Shopp'),
        'update_item' => __('Update finish','Shopp'),
        'add_new_item' => __('New finish','Shopp'),
        'new_item_name' => __('New finish Name','Shopp'),
        'separate_items_with_commas' => __('Separate finishes with commas','Shopp'),
        'add_or_remove_items' => __('Add or remove finishes','Shopp'),
        'choose_from_most_used' => __('Choose from the most used finishes','Shopp')
    ),
    'show_ui' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'finishes' ),
    ));
    
    shopp_register_taxonomy('group', array(
        'hierarchical' => true,
        'labels' => array(
        'name' => __('Groups','Shopp'),
        'singular_name' => __('Group','Shopp'),
        'search_items' => __('Search Groups','Shopp'),
        'popular_items' => __('Popular','Shopp'),
        'all_items' => __('Show All','Shopp'),
        'parent_item' => __('Parent Group','Shopp'),
        'parent_item_colon' => __('Parent Group:','Shopp'),
        'edit_item' => __('Edit Group','Shopp'),
        'update_item' => __('Update finish','Shopp'),
        'add_new_item' => __('New Group','Shopp'),
        'new_item_name' => __('New Group Name','Shopp'),
        'separate_items_with_commas' => __('Separate Groups with commas','Shopp'),
        'add_or_remove_items' => __('Add or remove Groups','Shopp'),
        'choose_from_most_used' => __('Choose from the most used Groups','Shopp')
    ),
    'show_ui' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'groups' ),
    ));
}

add_action('shopp_init','register_shopp_taxonomies');