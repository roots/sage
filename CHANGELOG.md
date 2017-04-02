### HEAD
* Add option to configure build settings ([#1822](https://github.com/roots/sage/pull/1822))
* Add support for HTML injection ([#1817](https://github.com/roots/sage/pull/1817))
* Add Tachyons as a CSS framework option

### 9.0.0-beta.2: January 19th, 2016
* Fix Browersync ([#1815](https://github.com/roots/sage/pull/1815))
* Add option to select CSS framework, add Foundation as an option ([#1813](https://github.com/roots/sage/pull/1813))
* Add option to add Font Awesome ([#1812](https://github.com/roots/sage/pull/1812))
* Add option to change theme file headers ([#1811](https://github.com/roots/sage/pull/1811))
* Add option to remove Bootstrap ([#1810](https://github.com/roots/sage/pull/1810))
* Remove Font Awesome ([#1809](https://github.com/roots/sage/pull/1809))
* Remove grid defaults ([#1808](https://github.com/roots/sage/pull/1808))
* Fix for `publicPath` ([#1806](https://github.com/roots/sage/pull/1806))
* Update clean task name ([#1800](https://github.com/roots/sage/pull/1800))
* Allow browser versions to be configured in `config.json` ([#1798](https://github.com/roots/sage/pull/1798))
* Use stock ESLint ([#1796](https://github.com/roots/sage/pull/1796))

### 9.0.0-beta.1: January 10th, 2016
* Update to Bootstrap 4 Alpha 6 ([#1792](https://github.com/roots/sage/pull/1792))
* Add Blade ([#1765](https://github.com/roots/sage/pull/1765) and [#1777](https://github.com/roots/sage/pull/1777))
* Remove sidebar defaults ([#1760](https://github.com/roots/sage/pull/1760))
* Remove post formats ([#1759](https://github.com/roots/sage/pull/1759))

### 9.0.0-alpha.4: November 16th, 2016
* Use new webpack api schema ([8ac5f15](https://github.com/roots/sage/commit/e6e60aa))
* Update dependencies ([70ebba7](https://github.com/roots/sage/commit/70ebba7))
* Variables organization ([8ac5f15](https://github.com/roots/sage/commit/8ac5f15))
* Use `$.fn.ready()` (reverts [724d550](https://github.com/roots/sage/commit/724d550)) ([e7fccbe](https://github.com/roots/sage/commit/e7fccbe))
* Theme activation updates 'stylesheet' option instead of 'template' ([fb19145](https://github.com/roots/sage/commit/fb19145))
* Reorganize and refactor build routine ([8c9ba05](https://github.com/roots/sage/commit/8c9ba05))
* Switch assets manifest plugin ([c1bb2b3](https://github.com/roots/sage/commit/c1bb2b3))
* Add images to assets manifest ([c49793c](https://github.com/roots/sage/commit/c49793c))
* Switch from babel to buble ([0d38ab8](https://github.com/roots/sage/commit/0d38ab8))
* Update dependencies & webpack compatibility ([eae52fd](https://github.com/roots/sage/commit/eae52fd))
* Use http by default (not https) to be consistent with Trellis ([e6f2f49](https://github.com/roots/sage/commit/e6f2f49))

### 9.0.0-alpha.3: September 11th, 2016
* Fix webpack HMR ([#1713](https://github.com/roots/sage/issues/1713))
* Remove minor edits from CHANGELOG.md ([3516629](https://github.com/roots/sage/commit/3516629))

### 9.0.0-alpha.2: September 4th, 2016
* Refactor build routine ([#1703](https://github.com/roots/sage/pull/1703))
* Update `_grid.scss` to use `@include make-col-ready()` mixin ([#1706](https://github.com/roots/sage/pull/1706))

### 9.0.0-alpha.1: August 30th, 2016
* Move assets found in `node_modules/` to `dist/vendor`, simpler `assets/config.json` ([#1697](https://github.com/roots/sage/pull/1697))
* Set dynamically absolute public path ([#1696](https://github.com/roots/sage/pull/1696))
* Load Tether ([#1686](https://github.com/roots/sage/pull/1686))
* Squash asset pipeline bugs ([4d58f88](https://github.com/roots/sage/commit/4d58f88))
* Update dependencies, incl Bootstrap 4 alpha 3 ([447c24d](https://github.com/roots/sage/commit/447c24d))
* Fix path to stylesheet in `add_editor_style` ([#1684](https://github.com/roots/sage/pull/1684))
* Update required Node.js version ([72b2d89](https://github.com/roots/sage/commit/72b2d89))
* Router doesn't require jQuery, use default params ([339cc8e](https://github.com/roots/sage/commit/339cc8e))
* Fix display of theme name in Customizer ([3425386](https://github.com/roots/sage/commit/3425386))
* Convert scripts to ES6 ([f34af48](https://github.com/roots/sage/commit/f34af48))
* Refactor functions.php ([eae36be](https://github.com/roots/sage/commit/eae36be))
* Rework template wrapper, bring back template_part() ([#1678](https://github.com/roots/sage/pull/1678))
* Remove unused static variable in Wrapper ([9bfdd5a](https://github.com/roots/sage/commit/9bfdd5a))
* Remove `path.extname()` check ([#1673](https://github.com/roots/sage/pull/1673))
* Updated to align with the Bootstrap 4 docs ([#1667](https://github.com/roots/sage/pull/1667))
* Add `npm prune` to Travis CI ([#1663](https://github.com/roots/sage/pull/1663))
* Bootstrap NPM ^4.0.0-alpha.2 ([#1650](https://github.com/roots/sage/pull/1650))
* Fix Bootstrap 4 styles ([#1642](https://github.com/roots/sage/pull/1642))
* Fix autoloader load order issue when including Sage 9 via composer.json in Bedrock ([#1628](https://github.com/roots/sage/pull/1628))
* `is_page_template()` requires the name of the subfolder ([#1626](https://github.com/roots/sage/pull/1626))
* Webpack config improvements ([#1629](https://github.com/roots/sage/pull/1629))
* Webpack implementation improvements ([#1627](https://github.com/roots/sage/pull/1627))
* Webpack implementation ([#1625](https://github.com/roots/sage/pull/1625))
* Fix BS4 grid after their grid updates ([5551dde](https://github.com/roots/sage/commit/5551dde))
* Pass the correct template ([856a482](https://github.com/roots/sage/commit/856a482))
* Optimize travis a bit ([b42c425](https://github.com/roots/sage/commit/b42c425))
* Move single-post loop to single.php, closes #1582  ([6efa099](https://github.com/roots/sage/commit/6efa099))
* All function braces need to be on new line ([b491f76](https://github.com/roots/sage/commit/b491f76))
* Fix issue with WP loading wrong index.php ([9e2917e](https://github.com/roots/sage/commit/9e2917e))
* Use 4 spaces for `src/*.php` ([fe659f4](https://github.com/roots/sage/commit/fe659f4))
* Use phpcs.xml for all rules ([246955c](https://github.com/roots/sage/commit/246955c))
* Conform to new rules ([6a4d3bd](https://github.com/roots/sage/commit/6a4d3bd))
* Create new phpcs rules ([2d02544](https://github.com/roots/sage/commit/2d02544))
* Remove Template class ([1df3fee](https://github.com/roots/sage/commit/1df3fee))
* Remove closure from sidebar registration ([12d6ac3](https://github.com/roots/sage/commit/12d6ac3))
* Remove sage.pot ([d4461fa](https://github.com/roots/sage/commit/d4461fa))
* Remove template_part, template_sidebar, temp sidebar fix ([abeea0f](https://github.com/roots/sage/commit/abeea0f))
* Update asset handles ([fa0e51f](https://github.com/roots/sage/commit/fa0e51f))
* Remove comment-reply JS ([d217ba6](https://github.com/roots/sage/commit/d217ba6))
* Update node in travis, remove unsupported php ([8712dc8](https://github.com/roots/sage/commit/8712dc8))
* Update dependencies, switch to eslint ([e51e41e](https://github.com/roots/sage/commit/e51e41e))
* Bootstrap 4 ([c9ef232](https://github.com/roots/sage/commit/c9ef232))
* Fix: page title not displaying ([9283bbb](https://github.com/roots/sage/commit/9283bbb))
* Rename interfaces, unset after unwrapping ([97906e9](https://github.com/roots/sage/commit/97906e9))
* Restructure theme, use autoloader ([9eaffa3](https://github.com/roots/sage/commit/9eaffa3a2d4df462dd8020a10551334208bd32a3))

### 8.5.0: September 20th, 2016
* Update installation instructions
* Update dependencies
* Update to Bootstrap 4.0.0-alpha.4 ([5eb01fd](https://github.com/roots/sage/commit/5eb01fd0319a7b6576e31579dc50e16b023abb74))

### 8.4.2: February 19th, 2016
* Add Composer vendor directory to gitignore ([#1618](https://github.com/roots/sage/issues/1618))
* Fix build test by removing trailing space ([#1617](https://github.com/roots/sage/issues/1617))
* Replace deprecated gulp-minify-css with gulp-cssnano ([#1610](https://github.com/roots/sage/issues/1610))

### 8.4.1: January 27th, 2016
* Add `composer.json` and update installation instructions ([#1583](https://github.com/roots/sage/issues/1583))

### 8.4.0: December 1st, 2015
* Update to Bootstrap 3.3.6 ([#1578](https://github.com/roots/sage/pull/1578))
* Remove unnecessary underscore ([#1577](https://github.com/roots/sage/pull/1577))
* Drop support for older browsers ([#1571](https://github.com/roots/sage/pull/1571))
* Add support for theme customizer ([#1573](https://github.com/roots/sage/pull/1573))
* Remove extraneous no-js ([#1562](https://github.com/roots/sage/pull/1562))
* Simplify/speed up editor style process ([#1560](https://github.com/roots/sage/pull/1560))

### 8.3.0: October 13th, 2015
* Setup organization ([#1558](https://github.com/roots/sage/pull/1558))
* Remove redundancy with WAI-ARIA in HTML ([#1557](https://github.com/roots/sage/pull/1557))
* Rename config.php to setup.php ([#1556](https://github.com/roots/sage/pull/1556))
* Move init.php to config.php ([#1555](https://github.com/roots/sage/pull/1555))
* Use Sass to style search form, remove search template ([#1545](https://github.com/roots/sage/pull/1545))
* Remove Modernizr ([#1541](https://github.com/roots/sage/pull/1541))
* Remove references to WP_ENV ([#1554](https://github.com/roots/sage/pull/1554))
* Use WP core's HTML5 gallery markup ([#1546](https://github.com/roots/sage/pull/1546))
* Use slash in handle names for theme CSS and JS ([#1537](https://github.com/roots/sage/pull/1537))
* Add compatibility with WooCommerce Multilingual plugin ([#1530](https://github.com/roots/sage/pull/1530))
* Remove ConditionalTagCheck class ([#1494](https://github.com/roots/sage/pull/1494))
* Add search templates ([#1459](https://github.com/roots/sage/issues/1459))
* Allow `debugger` statements in development JavaScript ([#1487](https://github.com/roots/sage/issues/1487))

### 8.2.1: May 7th, 2015
* Update BrowserSync ([#1457](https://github.com/roots/sage/issues/1457))
* Bump dependencies ([#1448](https://github.com/roots/sage/issues/1448))
* Allow revved files to resolve in development if they exist ([#1456](https://github.com/roots/sage/issues/1456))
* Disable advanced minification features to fix incorrect file path in compiled CSS ([#1452](https://github.com/roots/sage/issues/1452))
* Fix Glyphicon font path ([#1455](https://github.com/roots/sage/issues/1455))

### 8.2.0: April 29th, 2015
* Use Sass Bootstrap by default ([#1437](https://github.com/roots/sage/issues/1437))
* Remove nav walker and Bootstrap navbar ([#1427](https://github.com/roots/sage/issues/1427))
* Remove Bootstrap gallery ([#1421](https://github.com/roots/sage/issues/1421))
* Remove hardcoded feed link ([#1426](https://github.com/roots/sage/issues/1426))
* Move jQuery CDN feature to Soil ([#1422](https://github.com/roots/sage/issues/1422))
* Bump `gulp-load-plugins` to 0.10.0 ([#1419](https://github.com/roots/sage/issues/1419))
* Switch from [yargs](https://github.com/bcoe/yargs) to [minimist](https://github.com/substack/minimist) ([#1418](https://github.com/roots/sage/issues/1418))
* Remove `$content_width` ([#1417](https://github.com/roots/sage/issues/1417))
* Lowercase `X-UA-Compatible` ([#1409](https://github.com/roots/sage/issues/1409))
* Remove mention of Google Analytics from the config ([#1384](https://github.com/roots/sage/issues/1384))

### 8.1.1: March 31st, 2015
* Remove pleeease dependency in favor of vanilla gulp-autoprefixer and gulp-minify-css ([#1402](https://github.com/roots/sage/issues/1402))
* Fix `gulp --production` race condition ([#1398](https://github.com/roots/sage/issues/1398))
* Update to Bootstrap 3.3.4 ([#1387](https://github.com/roots/sage/issues/1387))

### 8.1.0: March 13th, 2015
* Move HTML5 Boilerplate's Google Analytics snippet to Soil ([#1382](https://github.com/roots/sage/issues/1382))
* Run `gulp build` if `bower.json` is changed ([#1378](https://github.com/roots/sage/issues/1378))
* Remove namespace from base.php ([#1372](https://github.com/roots/sage/issues/1372))
* Allow build directory to be customized ([#1352](https://github.com/roots/sage/issues/1352), [#1366](https://github.com/roots/sage/issues/1366))
* Update ConditionalTagCheck and usage docs ([#1365](https://github.com/roots/sage/issues/1365))
* Change default gallery columns to 3 ([#1364](https://github.com/roots/sage/issues/1364))
* Apply `script_loader_src` filter to jQuery fallback ([#1363](https://github.com/roots/sage/issues/1363))

### 8.0.1: February 26th, 2015
* Update asset-builder version to fix Windows compatibility ([#1351](https://github.com/roots/sage/issues/1351))
* Fix broken wiredep imports with main.scss.example ([Discussion](https://discourse.roots.io/t/issue-with-sage-sass-version/2962))

### 8.0.0: February 25th, 2015
* Change theme name from Roots to Sage
* Bump required PHP version to >=5.4
* Add coding standards based on PSR-2
* Add Travis CI
* Add namespace
* Use short array syntax
* Use short echo syntax
* Switch from Grunt to gulp, new front-end development workflow
* Switch from Livereload to [BrowserSync](http://www.browsersync.io/)
* Use wiredep for Sass and Less injection
* Implement JSON file based asset pipeline with [asset-builder](https://github.com/austinpray/asset-builder)
* Re-organize asset file structure
* Re-organize stylesheet file structure
* Add main.scss.example and instructions for using Sass
* Use the primary theme stylesheet for the editor stylesheet
* Remove theme activation, move to [wp-cli-theme-activation](https://github.com/roots/wp-cli-theme-activation)
* Simplify 404 page
* Convert Sidebar to ConditionalTagCheck
* Update to jQuery 1.11.2
* Use new core navigation template tag
* Update sidebar to fix default template check
* Update nav walker to correctly assign `active` classes for custom post types
* Better support for CPT templates

### 7.0.3: December 18th, 2014
* Use `get_the_archive_title`
* Remove `wp_title`, add title-tag theme support
* Remove `Roots_Nav_Walker` as default for all menus
* Update to Bootstrap 3.3.1
* Add some base comment styling
* Make search term `required` in search form

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
