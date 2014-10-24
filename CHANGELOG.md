### 7.0.2: October 24th, 2014
* Simplify comments, use core comment form and list
* Remove HTML5 shiv from Modernizr build
* Move JavaScript to footer
* Update hEntry schema to use `updated` instead of `published`
* Move variables into `main.less`
* Add `roots_body_class` function that checks for page slug in `body_class`
* Move `wp_footer` from footer template into `base.php`

### 7.0.1: August 15th, 2014
* Move `<main>` and `.sidebar` markup out of PHP and into LESS
* Define `WP_ENV` if it is not already defined
* Only load Google Analytics in production environment

### 7.0.0: July 3rd, 2014
* Updated Grunt workflow
* Use grunt-modernizr to make a lean Modernizr build
* Use Bower for front-end package management
* Update to Bootstrap 3.2.0
* Update to Modernizr 2.8.2
* Update to jQuery 1.11.1
* Move clean up, relative URLs, and nice search to [Soil](https://github.com/roots/soil)
* Update LESS organization
* Move [community translations](https://github.com/roots/roots-translations) to separate repository

### 6.5.2: February 4th, 2014
* Update to Bootstrap 3.1.0
* Move DOM routing into an anonymous function to support jQuery noConflict
* Update to jQuery 1.11.0
* Add notice to theme activation, tidy activation table markup
* Remove changing media folder from theme activation (use [Bedrock](https://github.com/roots/bedrock) for clean URLs out of the box)
* Switch `div.main` to `main` element now that Modernizr uses the latest HTML5 Shiv
* Update to Modernizr 2.7.0
* Don't run JSHint on plugins (`assets/js/plugins/`)
* Disable warnings about undefined variables (JSHint)
* Merge in updates from HTML5 Boilerplate
* Add JS source map (disabled by default)
* Replace `grunt-recess` with `grunt-contrib-less`, add LESS source map support

### 6.5.1: November 5th, 2013
* Move clean URLs to a [plugin](https://github.com/roots/roots-rewrites)
* Update to Bootstrap 3.0.1

### 6.5.0: August 23rd, 2013
* Reference new site, [http://roots.io/](http://roots.io/)
* Remove bundled docs, reference [http://roots.io/docs/](http://roots.io/docs/)
* Use Bootstrap variables for media queries
* Update to Bootstrap 3.0.0
* Update to jQuery 1.10.2
* Change media directory from `/assets/` to `/media/`
* Update to Google Universal Analytics
* Show author display name for author archives
* Add Serbian translation
* Remove post tags from templates
* Remove TinyMCE valid elements tweaks (no longer necessary)
* Remove additional widget classes
* Move `/assets/css/less/` to `/assets/less/`
* Add wrapper templates filter
* Fix relative external URLs issue

### 6.4.0: May 1st, 2013
* Fix Theme Activation page issues
* Fix issues with root relative URLs and rewrites on non-standard setups
* Make sure rewrites are added to `.htaccess` immediately after activation
* Move HTML5 Boilerplate's `.htaccess` to a [plugin](https://github.com/roots/wp-h5bp-htaccess)
* Rename `page-custom.php` to `template-custom.php`
* Don't warn about unwritable htaccess if that option is disabled
* Add missing collapse class for top navbar
* Add comment template
* Update is_dropdown evaluation in nav walker
* Re-organize archives template
* Add missing comment ID
* hNews consistency with entry-title class
* Add `wp_title()` filter
* Fix missing closing div in comments
* Fix for navbar dropdowns
* Add option for using jQuery on Google CDN
* Correct logic in `roots_enable_root_relative_urls`
* Add Greek translation, update Brazilian Portuguese translation
* Update to Bootstrap 2.3.1
* Simplify alerts
* Remove disabled post nav links
* Use Bootstrap media object for listing comments
* Move Google Analytics to `lib/scripts.php`
* Static top navbar instead of fixed

### 6.3.0: February 8th, 2013
* Update to Bootstrap 2.3.0
* Update to jQuery 1.9.1
* Output author title with `get_the_author()`
* Add EditorConfig
* Update 404 template based on H5BP
* Update H5BP's included .htaccess
* Don't show comments on passworded posts
* Add `do_action('get_header')` for WooSidebars compatibility
* Simplify entry meta
* Allow `get_search_form()` to be called more than once per request
* Move plugins.js and main.js to footer
* JavaScript clean up (everything is now enqueued)
* Remove conditional feed
* Introduce `add_theme_support('bootstrap-gallery')`
* Rewrites organization (introduce `lib/rewrites.php`)
* Fix `add_editor_style` path
* Updated translations: French, Bulgarian, Turkish, Korean
* Enable `add_theme_support` for Nice Search
* Replace ID's with classes
* Add support for dynamic sidebar templates
* Fix PHP notice on search with no results
* Update to jQuery 1.9.0

### 6.2.0: January 13th, 2013
* Implement latest Nice Search
* Update [gallery] shortcode
* Add Simplified Chinese, Indonesian, Korean translations
* Move template title to `lib/utils.php`
* Update to Bootstrap 2.2.2
* Update to jQuery 1.8.3
* Use `entry-summary` class for excerpts per Readability's Article Publishing Guidelines
* Cleanup/refactor `lib/activation.php`
* Remove `lib/post-types.php` and `lib/metaboxes.php`
* Make sure Primary Navigation menu always gets created and has the location set upon activation, update activation permalink method
* Update to Bootstrap 2.2.1
* Update conditional feed method
* Update to Bootstrap 2.2.0
* Return instead of echo class names in `roots_main_class` and `roots_sidebar_class`
* Move nav customizations into `lib/nav.php`

### 6.1.0: October 2nd, 2012
* Change roots_sidebar into a more explicit configuration array
* Re-organize configuration/setup files
* Update to jQuery 1.8.2
* Refactor/simplify Roots vCard Widget
* Move custom entry_meta code into template
* Move Google Analytics code into footer template
* Add CONTRIBUTING.md to assist with the new GitHub UI
* Add nav walker support for CSS dividers and nav-header

### 6.0.0: September 16th, 2012
* Simplify nav walker and support 3rd level dropdowns
* Update to Bootstrap 2.1.1, jQuery 1.8.1, Modernizr 2.6.2
* Add bundled docs
* Update all templates to use [PHP Alternative Syntax](http://php.net/manual/en/control-structures.alternative-syntax.php)
* Add MIT License
* Implement scribu's [Theme Wrapper](http://scribu.net/wordpress/theme-wrappers.html) (see `base.php`)
* Move `css/`, `img/`, and `js/` folders within a new `assets/` folder
* Move templates, `comments.php`, and `searchform.php` to `templates/` folder
* Rename `inc/` to `lib/`
* Add placeholder `lib/post-types.php` and `lib/metaboxes.php` files
* Rename `loop-` files to `content-`
* Remove all hooks
* Use `templates/page-header.php` for page titles
* Use `head.php` for everything in `<head>`

### 5.2.0: August 18th, 2012
* Update to jQuery 1.8.0 and Modernizr 2.6.1
* Fix duplicate active class in `wp_nav_menu` items
* Merge `Roots_Navbar_Nav_Walker` into `Roots_Nav_Walker`
* Add and update code documentation
* Use `wp_get_theme()` to get the theme name on activation
* Use `<figure>` & `<figcaption>` for captions
* Wrap embedded media as suggested by Readability
* Remove unnecessary `remove_action`'s on `wp_head` as of WordPress 3.2.1
* Add updates from HTML5 Boilerplate
* Remove well class from sidebar
* Flush permalinks on activation to avoid 404s with clean URLs
* Show proper classes on additional `wp_nav_menu()`'s
* Clean up `inc/cleanup.php`
* Remove old admin notice for tagline
* Remove default tagline admin notice, hide from feed
* Fix for duplicated classes in widget markup
* Show title on custom post type archive template
* Fix for theme preview in WordPress 3.3.2
* Introduce `inc/config.php` with options for clean URLs, H5BP's `.htaccess`, root relative URLs, and Bootstrap features
* Allow custom CSS classes in menus, walker cleanup
* Remove WordPress version numbers from stylesheets
* Don't include HTML5 Boilerplate's `style.css` by default
* Allow `inc/htaccess.php` to work with Litespeed
* Update to Bootstrap 2.0.4
* Update Bulgarian translation
* Don't use clean URLs with default permalink structure
* Add translations for Catalan, Polish, Hungarian, Norwegian, Russian

### 5.1.0: April 14th, 2012
* Various bugfixes for scripts, stylesheets, root relative URLs, clean URLs, and htaccess issues
* Add a conditional feed link
* Temporarily remove Gravity Forms customizations
* Update to Bootstrap 2.0.2
* Update `roots.pot` for translations
* Add/update languages: Vietnamese, Swedish, Bulgarian, Turkish, Norwegian, Brazilian Portugese
* Change widgets to use `<section>` instead of `<article>`
* Add comment-reply.js
* Remove optimized robots.txt
* HTML5 Boilerplate, Modernizr, and jQuery updates

### 5.0.0: February 5th, 2012
* Remove all frameworks except Bootstrap
* Update to Bootstrap 2.0
* Remove `roots-options.php` and replaced with a more simple `roots-config.php`
* Now using Bootstrap markup on forms, page titles, image galleries, alerts and errors, post and comment navigation
* Remove Roots styles from `style.css` and introduced `app.css` for site-specific CSS
* Remove almost all previous default Roots styling
* Latest updates from HTML5 Boilerplate

### 4.1.0: February 1st, 2012
* Update translations
* HTML5 Boilerplate updates
* Fix for Server 500 errors
* Add `roots-scripts.php`, now using `wp_enqueue_script`
* Re-organize `roots-actions.php`
* Allow `<script>` tags in TinyMCE
* Add full width class and search form to 404 template
* Remove Blueprint CSS specific markup
* Use Roots Nav Walker as default
* Add author name and taxonomy name to archive template title
* Add Full Width CSS class options

### 4.0.0: January 4th, 2012
* Add theme activation options
* HTML5 Boilerplate updates
* Add CSS frameworks: Bootstrap, Foundation
* Add translations: Dutch, Italian, Macedonian, German, Finnish, Danish, Spanish, and Turkish
* Update jQuery
* Remove included jQuery plugins
* Clean up whitespace, switched to two spaces for tabs
* Clean up `body_class()` some more with `roots_body_class()`
* Post meta information is now displayed using a function (similar to Twenty Eleven)
* Bugfixes for 1140 options
* Add first and last classes to widgets
* Fix bug with initial options save
* Remove sitemap and listing subpages templates
* Child themes can now unregister sidebars
* Add fix for empty search query
* Update README
* Blocking access to readme.html and license.txt to hide WordPress version information

### 3.6.0: August 12th, 2011
* HTML5 Boilerplate 2.0 updates
* Cleaner output of enqueued styles and scripts
* Adde option for root relative URLs
* Small fixes to root relative URLs and clean assets
* Update included jQuery plugins
* Add French translation (thanks @johnraz)
* Add Brazilian Portuguese translation (thanks @weslly)
* Switch the logo to use `add_custom_image_header`
* Add a function that strips unnecessary self-closing tags
* Code cleanup and re-organization

### 3.5.0: July 30th, 2011
* Complete rewrite of theme options based on Twenty Eleven
* CSS frameworks: refactor code and add default classes for each framework
* CSS frameworks: add support for Adapt.js and LESS
* CSS frameworks: add option for None
* Add support for WPML and theme translation
* Add option for cleaner nav menu output
* Add option for FOUT-B-Gone
* Add authorship rel attribute to post author link
* Activation bugfix for pages being added multiple times
* Bugfixes to the root relative URL function
* Child themes will now load their CSS automatically and properly
* HTML5 Boilerplate updates (including Normalize.css, Modernizr 2.0, and Respond.js)
* Introduce cleaner way of including HTML5 Boilerplate's `.htaccess`
* Add hooks &amp; actions
* Rename `includes/` directory to `inc/`
* Add a blank `inc/roots-custom.php` file

### 3.2.4: May 19th, 2011
* Bugfixes
* Match latest changes to HTML5 Boilerplate and Blueprint CSS
* Update jQuery to 1.6.1

### 3.2.3: May 10th, 2011
* Bugfixes
* Add `language_attributes()` to `<html>`
* Match latest changes to HTML5 Boilerplate and Blueprint CSS
* Update jQuery to 1.6

### 3.2.2: April 24th, 2011
* Bugfixes

### 3.2.1: April 20th, 2011
* Add support for child themes

### 3.2.0: April 15th, 2011
* Add support for the 1140px Grid
* Update the conditional comment code to match latest changes to HTML5 Boilerplate

### 3.1.1: April 7th, 2011
* Fix relative path function to work correctly when WordPress is installed in a subdirectory
* Update jQuery to 1.5.2
* Fix comments to show avatars correctly

### 3.1.0: April 1st, 2011
* Add support for 960.gs thanks to John Liuti
* Add more onto the `.htaccess` from HTML5 Boilerplate
* Allow the theme directory and name to be renamable

### 3.0.0: March 28th, 2011
* Change name from BB to Roots and release to the public
* Update various areas to match the latest changes to HTML5 Boilerplate
* Change the theme markup based on hCard/Readability Guidelines and work by Jonathan Neal
* Create the navigation menus and automatically set their locations during theme activation
* Set permalink structure to `/%year%/%postname%/`
* Set uploads folder to `/assets/`
* Rewrite static folders in `/wp-content/themes/roots/` (`css/`, `js/`, `img/`) to the root (`/css/`, `/js/`, `/img/`)
* Rewrite `/wp-content/plugins/` to `/plugins/`
* Add more root relative URLs on WordPress functions
* Search results (`/?s=query`) rewrite to `/search/query/`
* `l10n.js` is deregistered
* Change [gallery] to output `<figure>` and `<figcaption>` and link to file by default
* Add more `loop.php` templates
* Made the HTML editor have a monospaced font
* Add `front-page.php`
* Update CSS for Gravity Forms 1.5
* Add `searchform.php template`

### 2.4.0: January 25th, 2011
* Add a notification when saving the theme settings
* Add support for navigation menus
* Create function that makes sure there is a Home page on theme activation
* Update various areas to match the latest changes to HTML5 Boilerplate

### 2.3.0: December 8th, 2010
* Logo is no longer an `<h1>`
* Add ARIA roles again
* Change `ul#nav` to `nav#nav-main`
* Add vCard to footer
* Made all URL's root relative
* Add Twitter and Facebook widgets to footer
* Add SEO optimized `robots.txt` from WordPress codex

### 2.2.0: September 20th, 2010
* Add asynchronous Google Analytics
* Update `.htaccess` with latest changes from HTML5 Boilerplate

### 2.1.0: August 19th, 2010
* Remove optimizeLegibility from headings
* Update jQuery to latest version
* Implement HTML5 Boilerplate `.htaccess`

### 2.0.1: August 2nd, 2010
* Add some presentational CSS classes
* Add footer widget
* Add more Gravity Forms default styling

### 2.0.0: July 19th, 2010
* Add HTML5 Boilerplate changes
* Implement `loop.php`
* wp_head cleanup
* Add `page-subpages.php` template

### 1.5.0: April 15th, 2010
* Integrate Paul Irish's frontend-pro-template (the original HTML5 Boilerplate)

### 1.0.0: December 18th, 2009
* Add Blueprint CSS to Starkers
