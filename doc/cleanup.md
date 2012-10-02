[Roots Theme homepage](http://www.rootstheme.com/) | [Documentation
table of contents](TOC.md)

# Clean up

Clean up is handled by `lib/cleanup.php`. Major parts include:

### wp_head() clean up

1. Remove unnecessary `<link>`'s
2. Remove inline CSS used by Recent Comments widget
3. Remove inline CSS used by posts with galleries
4. Remove self-closing tag and change `'`'s to `"`'s on `rel_canonical()`

### Add and remove body_class() classes

1. Add `top-navbar` class to `<body>` if using Bootstrap's navbar (enabled in `lib/config.php`). Used to add styling in `app.css` to account for the WordPress admin bar.
2. Add post/page slug class to `<body>`
3. Remove `page-template-default` class

### Root relative URLs

Root relative URLs are enabled from `lib/config.php`.

Return URLs such as `/assets/css/app.css` instead of `http://example.com/assets/css/app.css`.

### Wrap embedded media as suggested by Readability

The [Readability article publishing guidelines](http://www.readability.com/developers/guidelines#publisher) suggest wrapping embedded media with a class of `entry-content-asset`.

### Use HTML5 figure and figcaption for images with captions

Any images that contain captions will be returned with `<figure>` and `<figcaption>` elements. They also get a `caption` class for additional styling from Bootstrap.

### Clean up gallery_shortcode()

The `[gallery]` shortcode has been re-created to use Bootstrap thumbnail styling.

### Remove unnecessary dashboard widgets

1. Remove Incoming Links
2. Remove Plugins
3. Remove WordPress Blog
4. Remove Other WordPress News

### Clean up the_excerpt()

The excerpt length is defined in `lib/config.php`. Excerpts are ended with anchor to the post and with "… Continued" instead of "[…]".

### Cleaner walker for wp_nav_menu()

Walker_Nav_Menu (WordPress default) example output:

     <li id="menu-item-8" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-8"><a href="/">Home</a></li>
     <li id="menu-item-9" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-9"><a href="/sample-page/">Sample Page</a></l

Roots_Nav_Walker example output:

    <li class="menu-home"><a href="/">Home</a></li>
    <li class="menu-sample-page"><a href="/sample-page/">Sample Page</a></li>

If using the Bootstrap top navbar (enabled in `lib/config.php`), the proper markup is added to the items and the depth is restricted to 2 (Bootstrap doesn't support multi-level dropdowns).

Instead of the many different active class varities that WordPress usually uses, only `active` is returned on active items.

### Remove unnecessary self-closing tags

Self-closing tags aren't necessary with HTML5. They're removed on:

1. `get_avatar()` (`<img />`)
2. `comment_id_fields()` (`<input />`)
3. `post_thumbnail_html()` (`<img />`)

### Don't return the default description in the RSS feed if it hasn't been changed

If your site tagline is still `Just another WordPress site` then the description isn't returned in the feed.

### Allow more tags in TinyMCE

Allow `<iframe>` and `<script>` to be used without issues.

### Add additional classes onto widgets

Add `widget-first`, `widget-last`, and `widget-X` (X is the position) classes to widgets.

### Clean up search URLs

Redirect `/?s=query` to `/search/query/`, convert `%20` to `+`.