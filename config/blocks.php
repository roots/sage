<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Register theme color palette
    |--------------------------------------------------------------------------
    |
    | Colors defined in this array will be registered with the
    | WordPress block editor.
    |
    | Styles must still be implemented in `styles/common/variables`.
    |
    */

    'colors' => [
        [
            'name'  => __('Primary', 'sage'),
            'slug'  => 'primary',
            'color' => '#525ddc',
        ],
        [
            'name'  => __('Secondary', 'sage'),
            'slug'  => 'secondary',
            'color' => '#6c757d',
        ],
        [
            'name'  => __('Light', 'sage'),
            'slug'  => 'light',
            'color' => '#f8f9fa',
        ],
        [
            'name'  => __('Dark', 'sage'),
            'slug'  => 'dark',
            'color' => '#343a40',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Register theme font sizes
    |--------------------------------------------------------------------------
    |
    | Font-sizes defined in this array will be registered with the
    | WordPress block editor.
    |
    | Styles must still be implemented in `styles/common/variables`.
    |
    */

    'font_sizes' => [
        [
            'name'      => __('small', 'sage'),
            'shortName' => __('S', 'sage'),
            'size'      => 12,
            'slug'      => 'small'
        ],
        [
            'name'      => __('normal', 'sage'),
            'shortName' => __('M', 'sage'),
            'size'      => 16,
            'slug'      => 'normal'
        ],
        [
            'name'      => __('large', 'sage'),
            'shortName' => __('L', 'sage'),
            'size'      => 20,
            'slug'      => 'large'
        ],
        [
            'name'      => __('larger', 'sage'),
            'shortName' => __('XL', 'sage'),
            'size'      => 24,
            'slug'      => 'larger'
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Whitelist blocks
    |--------------------------------------------------------------------------
    |
    | Blocks whitelisted for use in the editor. Whitelists can be set globally
    | or maintained on a posttype-by-posttype basis.
    |
    | - Blocks in `global` are made available to the inserter for all posttypes.
    | - Blocks in `posts`  are made available to the inserter for all posts.
    | - Blocks in `pages`  are made available to the inserter for all pages.
    |
    | - If you have custom post types this schema can be easily extended
    |   using the 'allowed_block_type' filter found in `app/filters.php`.
    |
    | Should you prefer to not use a whitelist (WordPress default) then
    | the entirety of this array can be commented out or removed.
    |
    */

    'whitelist' => [

        'global' => [
            /**
             * Category: Common
             */
            'core/paragraph',
            'core/heading',
            'core/gallery',
            'core/list',
            'core/quote',
            'core/audio',
            'core/cover',
            'core/file',
            'core/video',

            /**
             * Category: Formatting
             */
            'core/table',
            'core/code',
            'core/freeform',
            'core/html',
            'core/preformatted',
            'core/pullquote',

            // 'core/verse',

            /**
             * Category: Layout Elements
             **/
            'core/button',
            'core/text-columns',
            'core/media-text',
            'core/more',
            'core/nextpage',
            'core/separator',
            'core/spacer',

            /**
             * Category: Widgets
             **/
            'core/shortcode',

            // 'core/archives',
            // 'core/categories',
            // 'core/latest-comments',
            // 'core/latest-posts',

            /**
             * Category: Embeds
             **/
            'core/embed',
            'core-embed/twitter',
            'core-embed/youtube',
            'core-embed/facebook',
            'core-embed/instagram',
            'core-embed/wordpress',
            'core-embed/soundcloud',
            'core-embed/spotify',
            'core-embed/flickr',
            'core-embed/vimeo',
            'core-embed/imgur',
            'core-embed/reddit',

            // 'core-embed/animoto',
            // 'core-embed/cloudup',
            // 'core-embed/collegehumor',
            // 'core-embed/dailymotion',
            // 'core-embed/funny-or-die',
            // 'core-embed/hulu',
            // 'core-embed/issuu',
            // 'core-embed/kickstarter',
            // 'core-embed/meetup-com',
            // 'core-embed/mixcloud',
            // 'core-embed/photobucket',
            // 'core-embed/polldaddy',
            // 'core-embed/reverbnation',
            // 'core-embed/screencast',
            // 'core-embed/scribd',
            // 'core-embed/slideshare',
            // 'core-embed/smugmug',
            // 'core-embed/ted',
            // 'core-embed/tumblr',
            // 'core-embed/videopress',
            // 'core-embed/wordpress-tv',
        ],
        'post' => [],
        'page' => [],
    ],
];
