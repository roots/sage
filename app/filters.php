<?php

/**
 * Use this file for registering new filters
 */

namespace App;

/**
 * Add <body> classes
 */
add_filter('body_class', function (array $classes) {
    /** Add page slug if it doesn't exist */
    if (is_single() || is_page() && !is_front_page()) {
        if (!in_array(basename(get_permalink()), $classes)) {
            $classes[] = basename(get_permalink());
        }
    }

    /** Add class if sidebar is active */
    if (display_sidebar()) {
        $classes[] = 'sidebar-primary';
    }

    /** Add shortcodes to body classes */
    if($current = get_post()) {
        
        $shortcodes = get_shortcode_tags();

        // Use key for shortcode name, value for shortcode
        // callback function name
        foreach ($shortcodes as $shortcode => $value) {
            if(has_shortcode($current->content, $shortcode)) {
                $classes[] = $shortcode;
            }
        }
    }

    /** Clean up class names for custom templates */
    $classes = array_map(function ($class) {
        return preg_replace(['/-blade(-php)?$/', '/^page-template-views/'], '', $class);
    }, $classes);

    return array_filter($classes);
});

/**
 * Add "… Continued" to the excerpt
 */
add_filter('excerpt_more', function () {
    return ' &hellip; <a href="' . get_permalink() . '">' . __('Continued', 'sage') . '</a>';
});

/**
 * Template Hierarchy should search for .blade.php files
 */
collect([
    'index', '404', 'archive', 'author', 'category', 'tag', 'taxonomy', 'date', 'home',
    'frontpage', 'page', 'paged', 'search', 'single', 'singular', 'attachment'
])->map(function ($type) {
    add_filter("{$type}_template_hierarchy", function ($templates) {
        return collect($templates)->flatMap(function ($template) {
            $transforms = [
                '%^/?(resources[\\/]views)?[\\/]?%' => '',
                '%(\.blade)?(\.php)?$%' => ''
            ];
            $normalizedTemplate = preg_replace(array_keys($transforms), array_values($transforms), $template);
            return ["{$normalizedTemplate}.blade.php", "{$normalizedTemplate}.php"];
        })->toArray();
    });
});

/**
 * Render page using Blade
 */
add_filter('template_include', function ($template) {
    $data = collect(get_body_class())->reduce(function ($data, $class) use ($template) {
        return apply_filters("sage/template/{$class}/data", $data, $template);
    }, []);
    echo template($template, $data);
    // Return a blank file to make WordPress happy
    return get_theme_file_path('index.php');
}, PHP_INT_MAX);

/**
 * Tell WordPress how to find the compiled path of comments.blade.php
 */
add_filter('comments_template', 'App\\template_path');

/**
 * Repace default search form with Foundation search form
 */
add_filter('get_search_form', function($form) {
    ob_start(); ?>

    <form role="search" method="get" class="search-form" action="<?=home_url('/');?>">
        <div class="input-group">
            <span class="input-group-label"><i class="fa fa-search"></i></span>
            <input class="search-field input-group-field" type="text" value="<?=get_search_query();?>" name="s" id="s" placeholder="Search..." />
            <div class="input-group-button">
                <input type="submit" class="search-submit button" value="<?=esc_attr__('Search');?>" />
            </div>
        </div>
    </form>

    <?php return ob_get_clean();
});

/**
 * Add attributes to post links
 */
function post_link_attributes($output) {
        $code = 'class="button"';
        return str_replace('<a href=', '<a '.$code.' href=', $output);
}
add_filter('next_post_link', 'App\\post_link_attributes');
add_filter('previous_post_link', 'App\\post_link_attributes');

/**
 * Add a body class for custom posts types
 */
add_filter('body_class', function($classes) {
    global $post;

    if (is_single() && $post->post_type !== 'post') {
        $classes[] = $post->post_type;
    }

    return $classes;
});

/**
 * Add custom image size to the list of selectable sizes
 */
add_filter('image_size_names_choose', function($sizes) {
    return array_merge( $sizes, array(
        'xlarge' => __( 'HD' ),
    ) );
});

/**
 * Allow SVGs to be uploaded through the Wordpress Media Library
 */
add_filter('upload_mimes', function($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
});
