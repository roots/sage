<?php

namespace App;

/**
 * Create a Theme Options page for ACF fields
 */
add_action('init', function () {
    if (function_exists('acf_add_options_page')) {
        $parent = acf_add_options_page([
            'page_title' => __('Theme Options', 'sage'),
            'menu_title' => __('Theme Options', 'sage'),
            'menu_slug' => 'theme_options',
            'capability' => 'manage_options',
            'post_id' => 'theme_options',
        ]);
    }
});


/**
 * Add Google API Key here
 */
add_action('acf/init', function () {
    // TODO: Load API key from envar or const
    acf_update_setting('google_api_key', '');
});


/**
 * Add Post Category Ancestor rule for evaluating on Posts with a common Parent Category
 */
add_filter('acf/location/rule_types', function ($choices) {
    if (!isset($choices['Post']['post_category_ancestor'])) {
        $choices['Post']['post_category_ancestor'] = 'Post Category Ancestor';
    }
    return $choices;
});
add_filter('acf/location/rule_values/post_category_ancestor', function ($choices) {
    // copied from acf rules values for post_category
    $terms = acf_get_taxonomy_terms('category');
    if (!empty($terms)) {
        $choices = array_pop($terms);
    }
    return $choices;
});
add_filter('acf/location/rule_match/post_category_ancestor', function ($match, $rule, $options) {
    // most of this copied directly from acf post category rule
    $terms = $options['post_taxonomy'];
    $data = acf_decode_taxonomy_term($rule['value']);
    $term = get_term_by('slug', $data['term'], $data['taxonomy']);
    if (!$term && is_numeric($data['term'])) {
        $term = get_term_by('id', $data['term'], $data['taxonomy']);
    }
    // this is where it's different than ACf
    // get terms so we can look at the parents
    if (is_array($terms)) {
        foreach ($terms as $index => $term_id) {
            $terms[$index] = get_term_by('id', intval($term_id), $term->taxonomy);
        }
    }
    if (!is_array($terms) && $options['post_id']) {
        $terms = wp_get_post_terms(intval($options['post_id']), $term->taxonomy);
    }
    if (!is_array($terms)) {
        $terms = array($terms);
    }
    $terms = array_filter($terms);
    $match = false;
    // collect a list of ancestors
    $ancestors = array();
    if (count($terms)) {
        foreach ($terms as $term_to_check) {
            $ancestors = array_merge(get_ancestors($term_to_check->term_id, $term->taxonomy));
        } // end foreach terms
    } // end if
    // see if the rule matches any term ancetor
    if ($term && in_array($term->term_id, $ancestors)) {
        $match = true;
    }
    
    if ($rule['operator'] == '!=') {
        // reverse the result
        $match = !$match;
    }
    return $match;
}, 10, 3);
