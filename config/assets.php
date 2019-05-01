<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Assets Directory URI
    |--------------------------------------------------------------------------
    |
    | The asset manifest contains relative paths to your assets. This URI will
    | be prepended when using Sage's asset management system. Change this if
    | you are pushing to a CDN.
    |
    */

    'uri' => get_theme_file_uri('/dist'),

    /*
    |--------------------------------------------------------------------------
    | Assets Directory Path
    |--------------------------------------------------------------------------
    |
    | The asset manifest contains relative paths to your assets. This path will
    | be prepended when using Sage's asset management system.
    |
    */

    'path' => get_theme_file_path('/dist'),

    /*
    |--------------------------------------------------------------------------
    | Assets Manifest
    |--------------------------------------------------------------------------
    |
    | Your asset manifest is used by Sage to assist WordPress and your views
    | with rendering the correct URLs for your assets. This is especially
    | useful for statically referencing assets with dynamically changing names
    | as in the case of cache-busting.
    |
    */

    'manifest' => get_theme_file_path('/dist/assets.json'),
];
