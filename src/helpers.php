<?php namespace App;

use Roots\Sage\Asset;
use Roots\Sage\Assets\JsonManifest;
use Roots\Sage\Template\WrapperCollection;
use Roots\Sage\Template\WrapperInterface;

/**
 * @param WrapperInterface $wrapper
 * @param string $slug
 * @return string
 * @throws \Exception
 * @SuppressWarnings(PHPMD.StaticAccess) This is a helper function, so we can suppress this warning
 */
function template_wrap(WrapperInterface $wrapper, $slug = 'base')
{
    WrapperCollection::add($wrapper, $slug);
    return $wrapper->getWrapper();
}

/**
 * @param string $slug
 * @return string
 */
function template_unwrap($slug = 'base')
{
    return WrapperCollection::get($slug)->getTemplate();
}

/**
 * @param $filename
 * @return string
 */
function asset_path($filename)
{
    static $manifest;
    isset($manifest) || $manifest = new JsonManifest(get_template_directory() . '/' . Asset::$dist . '/assets.json');
    return (string) new Asset($filename, $manifest);
}

/**
 * Determine whether to show the page header
 * @return bool
 */
function display_page_header()
{
    static $display;
    isset($display) || $display = apply_filters('sage/display_page_header', true);
    return $display;
}

/**
 * Determine whether to show the sidebar
 * @return bool
 */
function display_sidebar()
{
    static $display;
    isset($display) || $display = apply_filters('sage/display_sidebar', true);
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
