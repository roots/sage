<?php

return [

    /*
    |--------------------------------------------------------------------------
    | View Storage Paths
    |--------------------------------------------------------------------------
    |
    | Most template systems load templates from disk. Here you may specify
    | an array of paths that should be checked for your views.
    |
    */

    'paths' => [
        get_theme_file_path('/resources/views'),
        get_parent_theme_file_path('/resources/views'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Compiled View Path
    |--------------------------------------------------------------------------
    |
    | This option determines where all the compiled Blade templates will be
    | stored for your application. Typically, this is within the uploads
    | directory. However, as usual, you are free to change this value.
    |
    */

    'compiled' => get_theme_file_path('/storage/framework/views'),

    /*
    |--------------------------------------------------------------------------
    | View Debugger
    |--------------------------------------------------------------------------
    |
    | Enabling this option will display the current view name and data. Giving
    | it a value of 'view' will only display view names. Giving it a value of
    | 'data' will only display current data. Giving it any other truthy value
    | will display both.
    |
    */

    'debug' => false,

    /*
    |--------------------------------------------------------------------------
    | View Namespaces
    |--------------------------------------------------------------------------
    |
    | Blade has an underutilized feature that allows developers to add
    | supplemental view paths that may contain conflictingly named views.
    | These paths are prefixed with a namespace to get around the conflicts.
    | A use case might be including views from within a plugin folder.
    |
    */

    'namespaces' => [
        /*
         | Given the below example, in your views use something like:
         |     @include('MyPlugin::some.view.or.partial.here')
         */
        // 'MyPlugin' => WP_PLUGIN_DIR . '/my-plugin/resources/views',
    ],

    /*
    |--------------------------------------------------------------------------
    | View Composers
    |--------------------------------------------------------------------------
    |
    | View composers allow data to always be passed to certain views. This can
    | be useful when passing data to components such as hero elements,
    | navigation, banners, etc.
    |
    */

    'composers' => [
        //
    ],

    /*
    |--------------------------------------------------------------------------
    | View Directives
    |--------------------------------------------------------------------------
    |
    | The namespaces where view components reside. Components can be referenced
    | with camelCase & dot notation.
    |
    */

    'directives' => [
        'asset'  => Roots\Acorn\Assets\AssetDirective::class,
    ],

   /*
    |--------------------------------------------------------------------------
    | Blade Component Aliases
    |--------------------------------------------------------------------------
    |
    | Component aliases allow you to use a shorthand to call a Blade component.
    | Instead of referencing your components like this:
    |
    | @component('components.alert', ['type' => 'warning'])
    |   {{ __('Page not found') }}
    | @endcomponent
    |
    | You can use an alias instead:
    |
    | @alert(['type' => 'error'])
    |   {{ __('Page not found') }}
    | @endalert
    |
    | Use the key to set the alias and the value to set the path to the
    | view.
    |
    */

    'components' => [
        'alert'  => 'components.alert',
    ],
];
