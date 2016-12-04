<?php

namespace App;

use Roots\Sage\Template;
use Roots\Sage\Template\Wrapper;
use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;
use Roots\Sage\Template\ViewServiceProvider;

/**
 * Add <body> classes
 */
add_filter('body_class', function (array $classes) {
    // Add page slug if it doesn't exist
    if (is_single() || is_page() && !is_front_page()) {
        if (!in_array(basename(get_permalink()), $classes)) {
            $classes[] = basename(get_permalink());
        }
    }

    // Add class if sidebar is active
    if (display_sidebar()) {
        $classes[] = 'sidebar-primary';
    }

    return $classes;
});

/**
 * Add "… Continued" to the excerpt
 */
add_filter('excerpt_more', function () {
    return ' &hellip; <a href="' . get_permalink() . '">' . __('Continued', 'sage') . '</a>';
});

/**
 * Use Blade template engine
 */
foreach (['index', '404', 'archive', 'author', 'category', 'tag', 'taxonomy', 'date', 'home', 'front_page',
             'page', 'paged', 'search', 'single', 'singular', 'attachment'] as $type) {
    add_filter("{$type}_template_hierarchy", function ($templates) {
        foreach ($templates as $template) {
            $templates[] = str_replace('.php', '.blade.php', $template);
        }
        return $templates;
    });
}
add_filter('template_include', function ($template) {
    $blade_template = (!strpos($template, '.blade.php')) ? str_replace('.php', '.blade.php', $template) : $template;
    $blade_template = locate_template(basename($blade_template));

    if (!file_exists($blade_template)) {
        return $template;
    }

    $container = Container::getInstance();

    $container->singleton('files', function () {
        return new Filesystem;
    });

    $provider = new ViewServiceProvider($container);
    $provider->register();

    $template_engine = $container->make('view');

    $template_name = basename(str_replace('.blade', '', $blade_template));
    $template_name = str_replace('.php', '', $template_name);
    $html = $template_engine->make($template_name, apply_filters('laravel/blade/template_data', []))->render();

    if (!$html) {
        return $template;
    }

    echo $html;

    return false;
}, 1000);

add_filter('comments_template', function ($theme_template) {
    $container = Container::getInstance();
    $template_engine = $container->make('view');

    $template_name = basename(str_replace('.blade.php', '', $theme_template));
    $html = $template_engine->make('partials/'.$template_name, []);
    var_dump($template_engine->exists('partials/'.$template_name));
    $engine = $html->getEngine();
    $compiler = $engine->getCompiler();
    $template = $compiler->getCompiledPath($compiler->getPath());

    if ($compiler->isExpired($template)) {
        $compiler->compile($theme_template);
    }

    return $template;
});
