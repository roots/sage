<?php

return [

    /*
    |--------------------------------------------------------------------------
    | JavaScript
    |--------------------------------------------------------------------------
    |
    | Links a script file to the generated page at the right time according to
    | the script dependencies, if the script has not been already included and
    | if all the dependencies have been registered. You could either link a
    | script with a handle previously registered using the wp_register_script()
    | function, or provide this function with all the parameters necessary to
    | link a script.
    |
    | @link https://developer.wordpress.org/reference/functions/wp_enqueue_script/#more-information
    |
    */

    'scripts' => [
        [
            'handle'       => 'sage/vendor',
            'src'          => 'scripts/vendor.js',
            'dependencies' => ['jquery'],
            'version'      => null,
            'in_footer'    => true,
        ],
        [
            'handle'       => 'sage/app',
            'src'          => 'scripts/app.js',
            'dependencies' => ['sage/vendor', 'jquery'],
            'version'      => null,
            'in_footer'    => true,
        ]
    ],


    /*
    |--------------------------------------------------------------------------
    | Inline JavaScript
    |--------------------------------------------------------------------------
    |
    | Code will only be added if the script is already in the queue.
    | Accepts a string $data containing the Code. If two or more code blocks
    | are added to the same script $handle, they will be printed in the order
    | they were added, i.e. the latter added code can redeclare the previous.
    |
    | @link https://developer.wordpress.org/reference/functions/wp_add_inline_script/#description
    |
    */

    'inline_scripts' => [
        [
            'handle'   => 'sage/vendor',
            'data'     => 'scripts/manifest.js',
            'position' => 'before',
        ],
    ],


    /*
    |--------------------------------------------------------------------------
    | CSS
    |--------------------------------------------------------------------------
    |
    | A safe way to add/enqueue a stylesheet file to the WordPress generated page.
    |
    | @link https://developer.wordpress.org/reference/functions/wp_enqueue_style/
    |
    */

    'styles' => [
        [
            'handle'       => 'sage/app',
            'src'          => 'styles/app.css',
            'dependencies' => false,
            'version'      => null,
            'media'        => 'all',
        ],
    ],


    /*
    |--------------------------------------------------------------------------
    | Enable Comment Reply Link
    |--------------------------------------------------------------------------
    |
    | Displays a link that lets users post a comment in reply to a
    | specific comment.
    |
    | @link https://codex.wordpress.org/Function_Reference/comment_reply_link
    |
    */

    'comment_reply_enabled' => true,


    /*
    |--------------------------------------------------------------------------
    | Register Nav Menus
    |--------------------------------------------------------------------------
    |
    | Registers multiple custom navigation menus in the custom menu
    | editor.
    |
    | @link https://developer.wordpress.org/reference/functions/register_nav_menus/
    |
    */

    'nav_menus' => [
         'primary_navigation' => __('Primary Navigation', 'sage')
    ],


    /*
    |--------------------------------------------------------------------------
    | Register Widgets
    |--------------------------------------------------------------------------
    |
    | Registering a sidebar tells WordPress that you’re creating a new widget
    | area in Appearance > Widgets that users can drag their widgets to.
    |
    | https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
    |
    */

    'widget_config' => [
        'before_widget' => '<section class="widget %1$s %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3>',
        'after_title'   => '</h3>',
    ],

    'widget_areas' => [
        [
            'name' => __('Primary', 'sage'),
            'id'   => 'sidebar-primary',
        ],
        [
            'name' => __('Footer', 'sage'),
            'id'   => 'sidebar-footer',
        ]
    ],


    /*
    |--------------------------------------------------------------------------
    | Add Editor Styles
    |--------------------------------------------------------------------------
    |
    | Use main stylesheet for visual editor
    |
    | @link https://developer.wordpress.org/themes/customize-api/
    |
    */

    'add_editor_style' => 'styles/app.css',


    /*
    |--------------------------------------------------------------------------
    | Add Soil Plugin Theme Support
    |--------------------------------------------------------------------------
    |
    | Enable features from Soil when plugin is activated
    |
    | @link https://roots.io/plugins/soil/
    |
    */

    'add_soil_support' => [
        'soil-clean-up',
        'soil-jquery-cdn',
        'soil-nav-walker',
        'soil-nice-search',
        'soil-relative-urls',
    ],


    /*
    |--------------------------------------------------------------------------
    | Add Theme Support
    |--------------------------------------------------------------------------
    |
    | Registers theme support for a given feature.
    |
    | @link https://developer.wordpress.org/reference/functions/add_theme_support/
    |
    */

    'add_theme_support' => [


        /*
        |--------------------------------------------------------------------------
        | Add Title Tag Support
        |--------------------------------------------------------------------------
        |
        | Enable plugins to manage the document title
        |
        | @link https://developer.wordpress.org/reference/functions/add_theme_support/#title-tag
        |
        */

        'title',


        /*
        |--------------------------------------------------------------------------
        | Enable post thumbnails
        |--------------------------------------------------------------------------
        |
        | Featured images (also sometimes called Post Thumbnails) are images that
        | represent an individual Post, Page, or Custom Post Type.
        |
        | @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
        |
        */

        'post-thumbnails',


        /*
        |--------------------------------------------------------------------------
        | Enable HTML5 markup support
        |--------------------------------------------------------------------------
        |
        | This feature allows the use of HTML5 markup for the search forms,
        | comment forms, comment lists, gallery, and caption.
        |
        | @link https://developer.wordpress.org/reference/functions/add_theme_support/#html5
        |
        */

        'html5' => ['caption', 'comment-form', 'comment-list', 'gallery', 'search-form'],


        /*
        |--------------------------------------------------------------------------
        | Enable customizer selective refresh
        |--------------------------------------------------------------------------
        |
        | Enable selective refresh for widgets in customizer
        |
        | @link https://developer.wordpress.org/themes/customize-api/
        |
        */

        'customize-selective-refresh-widgets',

    ],

];
