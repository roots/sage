<?php

namespace App;

use Timber;
use TimberExtended;
use Twig_SimpleFunction;
use Twig_SimpleFilter;
use Genero\Sage\TwigExtensionLinkify;
use Genero\Sage\PostTypeConnection;

/**
 * Define where to look for twig templates.
 *
 * Rather than adding a multitude of directories, consider prefixing the
 * included templates with the directory name: `parts/hero.twig`
 */
Timber::$dirname = ['templates', 'templates/pages'];

/**
 * Site components injected into every timber context.
 */
add_filter('timber/context', function ($context) {
    // Add your menus.
    $context['primary_menu'] = new PostTypeConnection\Menu('primary_navigation');
    $context['language_menu'] = new TimberExtended\LanguageMenu('language-menu');

    // Set the page title.
    $context['title'] = \App\title();

    // Add your sidebars.
    $context['sidebar_primary'] = Timber::get_widgets('sidebar-primary');
    $context['sidebar_footer'] = Timber::get_widgets('sidebar-footer');
    $context['sidebar_content_below'] = Timber::get_widgets('sidebar-content-below');

    return $context;
});

/**
 * Preprocess meta data to be used in TimberPost objects.
 */
// add_filter('timber_post_get_meta', function ($customs, $pid, $post) {
//     foreach ($customs as $key => $value) {
//         switch ($key) {
//             case 'background_image':
//             case 'image':
//                 $customs[$key] = new Timber\Image($value);
//                 break;
//         }
//     }
//     return $customs;
// }, 10, 3);

/**
 * Configure twig with functions and filters.
 */
add_filter('get_twig', function ($twig) {
    // Provide a `linkify` filter which transforms URL addresses to HTML links.
    // @example
    // {{ footnote|linkify }}
    $twig->addExtension(new Twig_Extension_Linkify());

    // Use Finnish number format by default.
    // @example
    // {{ price|number_format }}
    $twig->getExtension('Twig_Extension_Core')->setNumberFormat(0, ',', ' ');

    // Get the asset path using Sage logic
    // @example
    // {{ asset_path('images/foo.svg') }}
    $twig->addFunction('asset_path', new Twig_SimpleFunction('asset_path', function ($filename) {
        return asset_path($filename);
    }));

    // Wrap the asset in a TimberImage object.
    // @example
    // {{ asset_image('images/foo.svg') }}
    $twig->addFunction('asset_image', new Twig_SimpleFunction('asset_image', function ($filename) {
        return new Timber\Image(asset_path($filename));
    }));

    // Format a phone number string.
    // @example
    // {{ post.phone|format_number }}
    $twig->addFilter('format_phone', new Twig_SimpleFilter('format_phone', function ($number) {
        return App\Utils\format_phone($number);
    }));

    return $twig;
});
