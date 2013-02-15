[Roots Theme homepage](http://www.rootstheme.com/) | [Documentation
table of contents](TOC.md)

# Theme library

### activation.php

This file handles the theme activation. [About the theme activation](activation.md).

### cleanup.php

This file handles the various WordPress clean up. [About the clean up](cleanup.md).

### config.php

This file is used to enable various theme features, define which pages get the sidebar, set the CSS classes for `.main` and `.sidebar`, set a Google Analytics ID, and set the post excerpt length.

#### Enable theme features

`add_theme_support()` is used to enable/disable:

1. Root relative URLs
2. [Rewrites](rewrites.md)
3. HTML5 Boilerplate's `.htaccess`
4. Bootstrap's top navbar
5. Nice Search (redirect `/?s=` to `/search/`)

If you don't want to use one of the features, either comment out the line or remove it.

#### Define which pages shouldn't have the sidebar

`roots_display_sidebar()` is used to define which pages shouldn't get the sidebar. By default, the 404, front `front-page.php` and `page-custom.php` templates are full width. If you would like to remove the sidebar from additional pages, add in the appropriate conditional or page template name.

### h5bp-htaccess

This file contains HTML5 Boilerplate's `.htaccess` which is automatically added by `htaccess.php` if enabled in `config.php`. There are a few changes to the H5BP version:

* Added block to access WordPress files that reveal version information (`wp-config.php`, `readme.html`, `license.txt`)
* Commented out expires headers (we recommend the use of [W3 Total Cache](http://wordpress.org/extend/plugins/w3-total-cache/))
* Commented out ETag removal (we recommend the use of [W3 Total Cache](http://wordpress.org/extend/plugins/w3-total-cache/))
* Commented out start rewrite engine (handled by WordPress)
* Commented out suppress/force www (handled by WordPress)
* Commented out `Options -MultiViews` (causes a server 500 error on most shared hosts)
* Commented out custom 404 page (handled by WordPress)

### htaccess.php

This file handles the HTML5 Boilerplate `.htaccess`.

### init.php

This file runs the initial theme setup and defines helper constants for later use

### nav.php

This file contains all the custom nav modifications (for Bootstrap) and clean up.

### rewrites.php

This file handles the clean URL rewrites. [About the rewrites](rewrites.md).

### scripts.php

This file handles all of the CSS and JavaScript.

### sidebar.php

Class which provides a simple configuration interface to define what pages you want to show the sidebar on.

#### Stylesheets

Stylesheets are enqueued in the following order:

1. `/theme/assets/css/bootstrap.css`
2. `/theme/assets/css/bootstrap-responsive.css`
3. `/theme/assets/css/app.css`
4. `/child-theme/style.css` (if a child theme is activated)

`app.css` should be used for your site specific styling.

If you're using LESS, make sure you compile the files to the proper locations:

1. `css/less/bootstrap.less` -> `css/bootstrap.css`
2. `css/less/responsive.less` -> `css/bootstrap-responsive.css`

#### JavaScript

JavaScript is loaded in the following order:

1. `jquery-1.9.1.min.js` via Google CDN with local fallback
2. `/theme/assets/js/vendor/modernizr-2.6.2.min.js`
3. `/theme/assets/js/plugins.js` (in footer)
4. `/theme/assets/js/main.js` (in footer)

jQuery is loaded using the same method from HTML5 Boilerplate: grab Google CDN's jQuery, with a protocol relative URL; fallback to local if offline. It's kept in the header instead of footer to avoid conflicts with plugins.

`plugins.js` contains a minified version of all the latest Bootstrap plugins.

Learn about `plugins.js` and `main.js` in the HTML5 Boilerplate [JavaScript docs](https://github.com/h5bp/html5-boilerplate/blob/master/doc/js.md).

##### jQuery in the footer

It's safe to move jQuery to the footer if you're able to avoid problems with certain plugins that improperly use jQuery. Copy the necessary lines from `head.php` to `footer.php` right before `wp_footer()`, then update the `wp_register_script()` calls `scripts.php` to have scripts in the footer by setting the last argument to `true`.

### utils.php

This file contains utility functions used by other files in the theme.

The theme wrapper is used to serve all of the template files. [About the theme wrapper](wrapper.md).

### widgets.php

This file registers the custom sidebars and custom widgets. There are two initial sidebars:

1. Primary Sidebar (used by `templates/sidebar.php`, included from `base.php` within `.sidebar`)
2. Footer (used by `templates/footer.php`)

The included vCard widget can be used to build additional, custom widgets.
