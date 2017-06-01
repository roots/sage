<?php

namespace App;

use Roots\Sage\Container;
use Illuminate\Contracts\Container\Container as ContainerContract;

/**
 * Get the sage container.
 *
 * @param string $abstract
 * @param array  $parameters
 * @param ContainerContract $container
 * @return ContainerContract|mixed
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function sage($abstract = null, $parameters = [], ContainerContract $container = null)
{
    $container = $container ?: Container::getInstance();
    if (!$abstract) {
        return $container;
    }
    return $container->bound($abstract)
        ? $container->makeWith($abstract, $parameters)
        : $container->makeWith("sage.{$abstract}", $parameters);
}

/**
 * Get / set the specified configuration value.
 *
 * If an array is passed as the key, we will assume you want to set an array of values.
 *
 * @param array|string $key
 * @param mixed $default
 * @return mixed|\Roots\Sage\Config
 * @copyright Taylor Otwell
 * @link https://github.com/laravel/framework/blob/c0970285/src/Illuminate/Foundation/helpers.php#L254-L265
 */
function config($key = null, $default = null)
{
    if (is_null($key)) {
        return sage('config');
    }
    if (is_array($key)) {
        return sage('config')->set($key);
    }
    return sage('config')->get($key, $default);
}

/**
 * @param string $file
 * @param array $data
 * @return string
 */
function template($file, $data = [])
{
    return sage('blade')->render($file, $data);
}

/**
 * Retrieve path to a compiled blade view
 * @param $file
 * @param array $data
 * @return string
 */
function template_path($file, $data = [])
{
    return sage('blade')->compiledPath($file, $data);
}

/**
 * @param $asset
 * @return string
 */
function asset_path($asset)
{
    return sage('assets')->getUri($asset);
}

/**
 * Determine whether to show the sidebar
 * @return bool
 */
function display_sidebar()
{
    static $display;
    isset($display) || $display = apply_filters('sage/display_sidebar', false);
    return $display;
}

/**
 * Page titles
 * @return string
 */
function title()
{
    if (is_home()) {
        if ($home = get_option('page_for_posts', true)) {
            return get_the_title($home);
        }
        return __('Latest Posts', 'sage');
    }
    if (is_archive()) {
        return get_the_archive_title();
    }
    if (is_search()) {
        return sprintf(__('Search Results for %s', 'sage'), get_search_query());
    }
    if (is_404()) {
        return __('Not Found', 'sage');
    }
    return get_the_title();
}

/**
 * Create a pagination element
 *
 * @param string $before
 * @param string $after
 * @return Echoed Post Pagination HTML
 */
function the_post_pagination($before = '', $after = '') {
    $wpdb = \get_the_DB();
    $wp_query = \get_the_query();
    $request = $wp_query->request;
    $posts_per_page = intval(get_query_var('posts_per_page'));
    $paged = intval(get_query_var('paged'));
    $numposts = $wp_query->found_posts;
    $max_page = $wp_query->max_num_pages;
    if ( $numposts <= $posts_per_page ) { return; }
    if(empty($paged) || $paged == 0) {
        $paged = 1;
    }
    $pages_to_show = 7;
    $pages_to_show_minus_1 = $pages_to_show-1;
    $half_page_start = floor($pages_to_show_minus_1/2);
    $half_page_end = ceil($pages_to_show_minus_1/2);
    $start_page = $paged - $half_page_start;
    if($start_page <= 0) {
        $start_page = 1;
    }
    $end_page = $paged + $half_page_end;
    if(($end_page - $start_page) != $pages_to_show_minus_1) {
        $end_page = $start_page + $pages_to_show_minus_1;
    }
    if($end_page > $max_page) {
        $start_page = $max_page - $pages_to_show_minus_1;
        $end_page = $max_page;
    }
    if($start_page <= 0) {
        $start_page = 1;
    }
    echo $before.'<nav class="page-navigation"><ul class="pagination">'."";
    if ($start_page >= 2 && $pages_to_show < $max_page) {
        $first_page_text = __( 'First', 'sage' );
        echo '<li><a href="'.get_pagenum_link().'" title="'.$first_page_text.'">'.$first_page_text.'</a></li>';
    }
    echo '<li>';
    previous_posts_link( __('Previous', 'sage') );
    echo '</li>';
    for($i = $start_page; $i  <= $end_page; $i++) {
        if($i == $paged) {
            echo '<li class="current"> '.$i.' </li>';
        } else {
            echo '<li><a href="'.get_pagenum_link($i).'">'.$i.'</a></li>';
        }
    }
    echo '<li>';
    next_posts_link( __('Next', 'sage'), 0 );
    echo '</li>';
    if ($end_page < $max_page) {
        $last_page_text = __( 'Last', 'sage' );
        echo '<li><a href="'.get_pagenum_link($max_page).'" title="'.$last_page_text.'">'.$last_page_text.'</a></li>';
    }
    echo '</ul></nav>'.$after."";
}

/**
 * Tests if any of a post's assigned categories are descendants of target categories
 * 
 * @param int|array $cats The target categories. Integer ID or array of integer IDs
 * @param int|object $_post The post. Omit to test the current post in the Loop or main query
 * @return bool True if at least 1 of the post's categories is a descendant of any of the target categories
 * @link http://codex.wordpress.org/Function_Reference/in_category#Testing_if_a_post_is_in_a_descendant_category
 */

function in_descendant_category( $cats, $_post = null ) {
    foreach ((array) $cats as $cat) {
        // get_term_children() accepts integer ID only
        $descendants = get_term_children((int) $cat, 'category');
        if ($descendants && in_category($descendants, $_post))
                return true;
    }
    return false;
}