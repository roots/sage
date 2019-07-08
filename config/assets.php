<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Assets Manifest
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default asset manifest that should be used.
    | The "theme" manifest is recommended as the default as it cedes ultimate
    | authority of your application's assets to the theme.
    |
    */

    'default' => 'theme',

    /*
    |--------------------------------------------------------------------------
    | Assets Manifests
    |--------------------------------------------------------------------------
    |
    | Manifests contain lists of assets that are referenced by static keys that
    | point to dynamic locations, such as a cache-busted location. A manifest
    | may employ any number of strategies for determining absolute local and
    | remote paths to assets.
    |
    | Supported Strategies: "relative"
    |
    | Note: We will add first-party support for more strategies in the future.
    |
    */

    'manifests' => [
        'theme' => [
            'strategy' => 'relative',
            'path' => get_theme_file_path('/dist'),
            'uri' => get_theme_file_uri('/dist'),
            'manifest' => get_theme_file_path('/dist/assets.json'),
        ]
    ]
];
