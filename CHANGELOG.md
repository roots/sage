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
