<?php

/**
 * Theme setup.
 */

namespace App;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\Str;

/**
 * Inject the Vite assets into the head.
 *
 * @return void
 */
add_filter('wp_head', function () {
    echo Str::wrap(app('assets.vite')([
        'resources/css/app.css',
        'resources/js/app.js',
    ]), "\n");
});

/**
 * Inject CSS into the block editor.
 *
 * @return array
 */
add_filter('block_editor_settings_all', function ($settings) {
    $manifest = File::json(public_path('build/manifest.json')) ?? [];

    if (Vite::isRunningHot()) {
        $devUrl = trim(File::get(Vite::hotFile()));
        $settings['styles'][] = [
            'css' => "@import url('{$devUrl}/resources/css/editor.css')",
        ];
        $settings['styles'][] = [
            'css' => "@import url('{$devUrl}/@vite/client')",
        ];

        return $settings;
    }

    if (isset($manifest['resources/css/editor.css'])) {
        $cssPath = public_path("build/{$manifest['resources/css/editor.css']['file']}");

        if (file_exists($cssPath)) {
            $settings['styles'][] = [
                'css' => file_get_contents($cssPath),
            ];
        }
    }

    return $settings;
});

/**
 * Inject JS into the block editor.
 *
 * @return void
 */
add_filter('admin_head', function () {
    if (! get_current_screen()?->is_block_editor()) {
        return;
    }

    $dependencies = File::json(public_path('build/editor.deps.json')) ?? [];

    foreach ($dependencies as $dependency) {
        if (! wp_script_is($dependency)) {
            wp_enqueue_script($dependency);
        }
    }

    echo Str::wrap(app('assets.vite')([
        'resources/js/editor.js',
    ]), "\n");
});

/**
 * Add Vite's HMR client to iframed block editor.
 *
 * @return void
 */
add_action('enqueue_block_assets', function () {
    if (! is_admin() || ! get_current_screen()?->is_block_editor()) {
        return;
    }

    if (! Vite::isRunningHot()) {
        return;
    }

    $devUrl = trim(File::get(Vite::hotFile()));
    $devUrl = str_replace('http://[::1]', 'ws://localhost', $devUrl);

    wp_register_script('vite-hmr', false);
    wp_enqueue_script('vite-hmr');
    wp_add_inline_script('vite-hmr', "
        if (window.self !== window.top) {
            const wsUrl = '{$devUrl}/';
            const client = new WebSocket(wsUrl, 'vite-hmr');
            window.top.console.log('WS attempting connection');

            client.addEventListener('open', () => window.top.console.log('WS connected'));
            client.addEventListener('close', (e) => window.top.console.log('WS closed:', e.code, e.reason));
            client.addEventListener('error', (e) => window.top.console.log('WS error'));
            client.addEventListener('message', ({data}) => {
                window.top.console.log('WS message:', data);
                const msg = JSON.parse(data);
                if (msg.type === 'update' && msg.updates.some(u => u.path.includes('editor.css'))) {
                    window.top.location.reload();
                }
            });
        }
    ");
});

/**
 * Use theme.json from the build directory
 *
 * @param  string  $path
 * @param  string  $file
 * @return string
 */
add_filter('theme_file_path', function (string $path, string $file): string {
    if ($file === 'theme.json') {
        return public_path().'/build/assets/theme.json';
    }

    return $path;
}, 10, 2);

/**
 * Register the initial theme setup.
 *
 * @return void
 */
add_action('after_setup_theme', function () {
    /**
     * Disable full-site editing support.
     *
     * @link https://wptavern.com/gutenberg-10-5-embeds-pdfs-adds-verse-block-color-options-and-introduces-new-patterns
     */
    remove_theme_support('block-templates');

    /**
     * Register the navigation menus.
     *
     * @link https://developer.wordpress.org/reference/functions/register_nav_menus/
     */
    register_nav_menus([
        'primary_navigation' => __('Primary Navigation', 'sage'),
    ]);

    /**
     * Disable the default block patterns.
     *
     * @link https://developer.wordpress.org/block-editor/developers/themes/theme-support/#disabling-the-default-block-patterns
     */
    remove_theme_support('core-block-patterns');

    /**
     * Enable plugins to manage the document title.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#title-tag
     */
    add_theme_support('title-tag');

    /**
     * Enable post thumbnail support.
     *
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support('post-thumbnails');

    /**
     * Enable responsive embed support.
     *
     * @link https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-support/#responsive-embedded-content
     */
    add_theme_support('responsive-embeds');

    /**
     * Enable HTML5 markup support.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#html5
     */
    add_theme_support('html5', [
        'caption',
        'comment-form',
        'comment-list',
        'gallery',
        'search-form',
        'script',
        'style',
    ]);

    /**
     * Enable selective refresh for widgets in customizer.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#customize-selective-refresh-widgets
     */
    add_theme_support('customize-selective-refresh-widgets');
}, 20);

/**
 * Register the theme sidebars.
 *
 * @return void
 */
add_action('widgets_init', function () {
    $config = [
        'before_widget' => '<section class="widget %1$s %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ];

    register_sidebar([
        'name' => __('Primary', 'sage'),
        'id' => 'sidebar-primary',
    ] + $config);

    register_sidebar([
        'name' => __('Footer', 'sage'),
        'id' => 'sidebar-footer',
    ] + $config);
});
