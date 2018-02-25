<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Blade Directives
    |--------------------------------------------------------------------------
    |
    | Define your own custom directives for use in Blade.
    |
    */

    /** Create @asset() Blade directive */
    'asset' => function ($asset) {
        return "<?= App\\asset_path({$asset}); ?>";
    },
];
