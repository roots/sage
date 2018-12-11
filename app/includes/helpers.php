<?php

namespace App;

define('FONTELLO_CONFIG_PATH', 'config/fontello-config.json');
define('JQUERY_VERSION', '3.3.1');

add_action('wp_enqueue_scripts', function () {
    wp_deregister_script('wp-embed');

    wp_deregister_script('jquery');
    wp_register_script(
        'jquery',
        '//ajax.googleapis.com/ajax/libs/jquery/' . JQUERY_VERSION . '/jquery.min.js',
        [],
        null,
        true
    );
});

add_filter('script_loader_tag', function ($tag, $handle) {
    if ($handle !== 'jquery') {
        return $tag;
    }

    $jquery_version = JQUERY_VERSION;

    $fallback_jquery_script = <<<EOT
<script>
window.jQuery || document.write('<script src="//code.jquery.com/jquery-$jquery_version.min.js"><\/script>');
</script>\n
EOT;

    return $tag . $fallback_jquery_script;
}, 10, 2);

/**
 * Disable comments for posts and pages.
 */
add_action('init', function () {
    remove_post_type_support('post', 'comments');
    remove_post_type_support('page', 'comments');
    remove_post_type_support('page', 'thumbnail');
});

// Remove REST API info from head and headers
remove_action('xmlrpc_rsd_apis', 'rest_output_rsd');
remove_action('wp_head', 'rest_output_link_wp_head', 10);
remove_action('template_redirect', 'rest_output_link_header', 11);

add_filter('wp_nav_menu_items', __NAMESPACE__ . '\\fix_target_blank');
add_filter('the_content', __NAMESPACE__ . '\\fix_target_blank');
add_filter('acf_the_content', __NAMESPACE__ . '\\fix_target_blank');

function fix_target_blank($html)
{
    return preg_replace('/(target="_blank")/', '\1 rel="noopener noreferrer"', $html);
}

add_filter('script_loader_src', __NAMESPACE__ . '\\filter_double_slash_src');
add_filter('style_loader_src', __NAMESPACE__ . '\\filter_double_slash_src');

function filter_double_slash_src($src)
{
    if (false !== strpos($src, home_url())) {
        $src = preg_replace('/^https?\:/', '', $src);
    }

    return $src;
}

/**
* Add Favicons to wp-admin and wp-login.php
**/
function add_favicons()
{
    include template_path(locate_template('partials/favicons.blade.php'));
}

add_action('login_head', __NAMESPACE__ . '\\add_favicons');
add_action('admin_head', __NAMESPACE__ . '\\add_favicons');
add_action('wp_head', __NAMESPACE__ . '\\add_favicons');

/**
 * Remove inline width set on WP captions.
 */
add_filter('img_caption_shortcode_width', '__return_zero');

add_action('wp_head', function () {
    ?>
    <style>
        body {
            opacity: 0;
            background-color: #fff;
        }
    </style>
    <?php
});

/**
 * Remove unnecessary meta boxes.
 */
add_action('admin_init', function () {
    remove_meta_box('postcustom', 'post', 'normal');
    remove_meta_box('postcustom', 'page', 'normal');
    remove_meta_box('tagsdiv-post_tag', 'post', 'side');
});

/**
 * Filters several urls to fix cross-domain css and js problem when using multiple domains with Polylang.
 */
if (function_exists('pll_home_url') &&
    get_option('polylang', false) &&
    isset(get_option('polylang', false)['force_lang']) &&
    get_option('polylang', false)['force_lang'] === 3
) {
    function tf_filter_polylang_url($url)
    {
        return str_replace(untrailingslashit(get_option('home')), 'http://' . $_SERVER['HTTP_HOST'], $url);
    }

    add_filter('stylesheet_directory_uri', 'tf_filter_polylang_url');
    add_filter('template_directory_uri', 'tf_filter_polylang_url');
    add_filter('plugins_url', 'tf_filter_polylang_url');
    add_filter('upload_dir', function ($wp_upload_dir) {
        $wp_upload_dir['url'] = tf_filter_polylang_url($wp_upload_dir['url']);
        $wp_upload_dir['baseurl'] = tf_filter_polylang_url($wp_upload_dir['baseurl']);

        return $wp_upload_dir;
    });
}

/**
 * Fetch Fontello icon-classes as numerical array with the classes being values or
 * as associative array with the classes being both keys and values.
 *
 * @param  boolean $for_select true for a associative array with the classes being both keys and values.
 * @return array              The Fontello icon-classes.
 */
function get_fontello_classes($for_select = false)
{
    $classes = [];

    if (! defined('FONTELLO_CONFIG_PATH')) {
        return $classes;
    }

    $config_path = sprintf('%s/%s', get_stylesheet_directory(), FONTELLO_CONFIG_PATH);

    if (! file_exists($config_path)) {
        return $classes;
    }

    // Fetch modification times
    $saved_modification_time = get_option('tf_fontello_config_modification_time', false);
    $file_modification_time = filemtime($config_path);

    if (false === $saved_modification_time ||
        $file_modification_time > $saved_modification_time
    ) {
        $config = file_get_contents($config_path);
        $config = json_decode($config);

        if (! isset($config->css_prefix_text)) {
            return $classes;
        }

        $class_prefix = $config->css_prefix_text;

        foreach ($config->glyphs as $icon) {
            if (! isset($icon->selected) ||
                true === $icon->selected
            ) {
                $classes[] = sanitize_html_class($class_prefix . $icon->css);
            }
        }

        update_option('tf_fontello_classes', $classes, false);
        update_option('tf_fontello_config_modification_time', $file_modification_time, false);
    } else {
        $classes = get_option('tf_fontello_classes', []);
    }

    if (true === $for_select &&
        ! empty($classes)
    ) {
        $classes = array_combine($classes, $classes);
    }

    return $classes;
}

/**
 * Remove current classes from page_for_posts menu item when on an custom post type archive or single url.
 */
add_filter('nav_menu_css_class', function ($classes, $item) {
    if (get_option('page_for_posts') === $item->object_id &&
        (
            ( is_post_type_archive() || is_singular() ) &&
            ! is_singular('post')
        )
    ) {
        return [];
    }

    return $classes;
}, 10, 2);

/**
 * Remove WP gallery styles
 */
add_filter('use_default_gallery_style', '__return_false');

/**
 * Pretty print
 */
function pr()
{
    $output = '<pre>';

    foreach (func_get_args() as $var) {
        ob_start();
        is_scalar($var) ? var_dump($var) : print_r($var);
        $output .= ob_get_clean();
    }

    $output .= '</pre>';

    echo $output;
}

function pr_log($var, $label = '')
{
    if (is_null($var)) {
        $value = 'NULL';
    } elseif (is_bool($var)) {
        $value = ( $var ) ? 'true' : 'false';
    } else {
        $value = print_r($var, true);
    }

    error_log(sprintf('%s%s', $label ? $label . ' ' : '', $value));
}

/**
 * Retrieve the URL of the ajaxhandler
 * @return string URL of the file.
 */
function get_ajaxurl()
{
    return esc_url(
        class_exists('Triggerfish\REST_Ajax\Controller')
            ? \Triggerfish\REST_Ajax\Controller::getURL()
            : admin_url('admin-ajax.php')
    );
}

add_action('wp_enqueue_scripts', function () {
    wp_localize_script(
        'sage/main.js',
        'theme',
        [
            'ajaxurl' => get_ajaxurl(),
        ]
    );
}, 100);

/**
 * Removes admin bar items
 */
add_action('wp_before_admin_bar_render', function () {
    global $wp_admin_bar;

    $wp_admin_bar->remove_menu('wp-logo');
    $wp_admin_bar->remove_menu('comments');
    $wp_admin_bar->remove_menu('customize');
});

/**
 * Removes Emoji support
 */
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('wp_print_styles', 'print_emoji_styles');

/**
 * Widgets init
 *
 */
add_action('widgets_init', function () {
    // Remove widgets
    unregister_widget('WP_Nav_Menu_Widget');
    unregister_widget('WP_Widget_Pages');
    unregister_widget('WP_Widget_Calendar');
    unregister_widget('WP_Widget_Links');
    unregister_widget('WP_Widget_Meta');
    unregister_widget('WP_Widget_Recent_Comments');
    unregister_widget('WP_Widget_RSS');
    unregister_widget('WP_Widget_Recent_Posts');
    unregister_widget('WP_Widget_Search');
    unregister_widget('WP_Widget_Tag_Cloud');
    unregister_widget('WP_Widget_Archives');
    unregister_widget('WP_Widget_Categories');
    unregister_widget('WP_Widget_Text');

    //Polylang
    unregister_widget('PLL_Widget_Calendar');
    unregister_widget('PLL_Widget_Languages');

    //Gravity forms
    unregister_widget('GFWidget');
}, 11);

/**
 * Hide admin menu pages
 */
add_action('admin_menu', function () {
    remove_menu_page('edit-comments.php');
    remove_menu_page('edit.php?post_type=acf-field-group');
    remove_submenu_page('edit.php', 'edit-tags.php?taxonomy=post_tag');

    if (! current_user_can('manage_options')) {
        remove_menu_page('tools.php');
        remove_submenu_page('themes.php', 'customize.php');
    }
});

/**
 * Filter for file names
 */
add_filter('sanitize_file_name', function ($filename) {
    if (false !== mb_strpos($filename, '.')) {
        $extension = explode('.', $filename);
        $extension = end($extension);

        $filename = mb_substr($filename, 0, mb_strrpos($filename, '.'));
        $filename = sprintf('%s.%s', sanitize_key($filename), $extension);
    }

    return $filename;
}, 99);

/**
 * Remove WP version nr
 */
add_filter('the_generator', '__return_empty_string');

/**
 * Filter function to alter the length of excerpts
 */
add_filter('excerpt_length', function ($length) {
    return 20;
});

/**
 * Filter function to alter the ending of excerpts
 */
add_filter('excerpt_more', function ($more) {
    return '...';
}, 11);

/**
 * Action to keep tree structure for categories.
 */
add_action('wp_terms_checklist_args', function ($args) {
    if (is_admin() && 'post' === get_current_screen()->base) {
        $args['checked_ontop'] = false;
    }

    return $args;
});

/**
 * Filter oEmbed HTML
 */
add_filter('oembed_result', function ($html, $url, $args) {
    $regexes = [
        'youtube' => [
            '#http://((m|www)\.)?youtube\.com/watch.*#i',
            '#https://((m|www)\.)?youtube\.com/watch.*#i',
            '#http://((m|www)\.)?youtube\.com/playlist.*#i',
            '#https://((m|www)\.)?youtube\.com/playlist.*#i',
            '#http://youtu\.be/.*#i',
            '#https://youtu\.be/.*#i',
        ],
        'vimeo' => [
            '#https?://(.+\.)?vimeo\.com/.*#i',
        ],
        'container' => [
            '#http://((m|www)\.)?youtube\.com/watch.*#i',
            '#https://((m|www)\.)?youtube\.com/watch.*#i',
            '#http://((m|www)\.)?youtube\.com/playlist.*#i',
            '#https://((m|www)\.)?youtube\.com/playlist.*#i',
            '#http://youtu\.be/.*#i',
            '#https://youtu\.be/.*#i',
            '#https?://(.+\.)?vimeo\.com/.*#i',
        ],
    ];

    $types = [];

    foreach ($regexes as $type => $type_regexes) {
        foreach ($type_regexes as $type_regex) {
            if (1 === preg_match($type_regex, $url)) {
                $types[] = $type;

                break;
            }
        }
    }

    if (empty($types)) {
        return $html;
    }

    if (array_intersect([ 'youtube', 'vimeo' ], $types)) {
        $src = preg_match(
            '/src="([^"]+)/i',
            $html,
            $matches
        );

        if (! empty($matches[1])) {
            $args = [];

            if (in_array('youtube', $types)) {
                $src = add_query_arg(
                    [
                        'feature' => 'oembed',
                        'rel' => 0,
                        'showinfo' => 0,
                    ],
                    $matches[1]
                );
            } elseif (in_array('vimeo', $types)) {
                $src = add_query_arg(
                    [
                        'byline' => 'false',
                        'title' => 'false',
                    ],
                    $matches[1]
                );
            }

            $src = esc_url($src);

            $html = preg_replace(
                '/(src=")([^"]+)/i',
                '${1}' . $src,
                $html
            );
        }
    }

    if (in_array('container', $types)) {
        $html = preg_replace(
            '/\s+width="[^"]+"/i',
            '',
            $html
        );

        $html = preg_replace(
            '/\s+height="[^"]+"/i',
            '',
            $html
        );

        $html = '<div class="embed-container">' . $html . '</div>';
    }

    return $html;
}, 10, 3);

/**
 * Stop Redirect Canonical from trying to redirect 404 errors.
 * @link https://core.trac.wordpress.org/ticket/16557
 **/
add_filter('redirect_canonical', function ($url) {
    return ( is_404() ) ? false : $url;
});

add_filter('upload_mimes', function ($mimes) {
    $mimes['vcf'] = 'text/x-vcard';
    $mimes['svg'] = 'image/svg+xml';

    return $mimes;
});

add_filter('show_admin_bar', function ($show) {
    return (current_user_can('read')) ? $show : false;
});
