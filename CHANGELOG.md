<<<<<<< HEAD
### v 3.0.2
* Fix: Bugfixing the meta module for older PHP versions
* New: Updated Bootstrap to version 3.0.2
* New: Changed the compiler and moved from [lessphp](http://leafo.net/lessphp/) to [less.php](http://lessphp.gpeasy.com/)
* New: Updated HTML5 Shiv to v3.7.0
* New: Updated modernizr to 2.7.0
* Fix: Removed duplicate wp_footer() call
* New: Added optional alternative navwalker: https://github.com/twittem/wp-bootstrap-navwalker
* Fix: Rewrote base.php
* New: Added layouts via templates for pages
* Fix: Updated [Redux Framework](http://reduxframework.com/)
* Fix: Minor bugfixes

### v 3.0.10

* Fix: Updating Redux Framework
* Fix: Broken assets URLs
* Fix: Layouts bug
* Fix: Updating Elusive Icons in the Redux Framework
* Fix: Jumbotron bottom border
* Fix: Footer Top Border
* New: Added post-meta configuration module
* Fix: Various Warnings and PHP notices
* Fix: Various typos
* Fix: added "featured-image" class to featured images
* Fix: Better spacing in nested comments
* Fix: Licencing now works.


### v. 3.0.01
* Fix: Updating Redux Framework
* New: Added some bbPress templates
* Fix: Updating Elusive Icons
* New: Addes tracking


### v. 3.0.0

* New: Complete rewrite
* New: Updated from Bootstrap 2 to Bootstrap 3.0
* New: Re-engineered the Compiler
* New: Added the [Redux FrameWork](http://reduxframework.com) admin panel
* New: Removed the advanced compiler and merged it with the normal compiler
* New: More than 150 options in the admin panel
* New: Added Retina support
* Fix: Multisite compatibility using a separate stylesheet for each site
* New: Custom Layouts per post type
* New: Added more backround options and patterns
* New: 8 styles for the NavBar
* New: Typography options for all elements
* New: Better support of all Google Fonts
* Featured images controls per post type
* New: Added more advanced controls
* New: Added a custom LESS field
* New: Import/export options via the Redux Framework
* TODO: WordPress Customizer


### v. 1.53

* Fix: Minor Bugfixes


### v. 1.52

* Fix: Fixes some multisite bugs


### v 1.51

* New: Added Featured images section on the customizer
* New: Added timthumb alternative script
* Better Implementation of the Google Webfonts API


### v 1.50

* Fix: Updated the Elusive-Icons Webfont
* New: Added the ability for Customizer help-texts
* New: Added image resizing script from https://github.com/matthewruddy/wordpress-timthumb-alternative/
* New: Added actions and hooks to call some various parts pf the templates
* Fix: Better loading of the customizer files
* Fix: Fixed layout issues
* New: Adding icon to tags and making them comma-separated instead of a list
* New: More versatile functions
* Fix: Fixing bug for the alternative navbar styling (wrong conditions)
* New: The excerpt is now called via do_action
* Fix: Better messages for admins
* Fix: Minor improvements on the custom builder
* Fix: Removed advanced customizer conditionals from navbar/functions.php
* Fix: Removed advanced customizer conditionals from navbar/styles.php
* Fix: Removed advanced customizer conditionals from social/styles.php
* Fix: Removed advanced customizer conditionals from typography/functions.php
* Fix: Removed advanced customizer conditionals from caching.php
* Fix: Removed advanced customizer conditionals from buttons/functions.php
* Fix: Removed advanced customizer conditionals from background/functions.php
* Fix: Removed advanced customizer conditionals from customizer.php
* Fix: Removing settings from the advanced customizer (will be using default customizer when needed)
* Fix: Removing unnecessary controls and settings from the advanced customizer
* Fix: Better CSS for some navbar parts
* New: Added alternative menu styling on the main navbar
* Fix: The main content of the page is now pushed down when adding padding to the bavbar
* Fix: Rhe Navbar padding now applies to menu items instead. The result is better styling for the selected menu items.
* Fix: Auto-Disable the advanced customizer if developer mode is off
* Fix: Recompiling CSS with latest phpless.
* Fix: Updating php-less compiler to 0.3.9
* Fix: Use the latest version of jQuery by default instead of the default WordPress version
* Fix: Load scripts in the head by default and not in the footer
* New: Added LICENCE.md file


### v 1.49

* Fix: template & syntax improvements
* Fix: better functions.php
* Fix: simplifying some template files and omitting the closing ?> php tag
* New: Admin notices for developer mode
* Fix: Better comment template
* Fix: adding missing classes to the top nav


### v 1.48

* Fix: Removed disabled post nav links
* Fix: Simplified 404
* Fix: Updated editor-style.css
* Fix: Root Relative URLs
* Fix: Hack for subdir installations
* Fix: Fix for admin bar
* New: Updated Bootstrap to 2.3.1
* New: added ‘jquery-cdn’ theme support
* Fix: Fix for navbar dropdowns
* New: Added wp_title() filter
* Fix: hNews consistency
* Fix: Fix for the jetpack carousel
* New: added option to override the default wordpress jquery with the latest version available
* Fix: Make sure webfonts work in advanced customizer as well
* New: Added fittext script for the hero region title.


### v 1.47

* New: Added a “general” section to the customizer
* New: Added a “No Gradients” setting to the general section
* New: Added a “No Border Radius” setting to the general section
* New: Use Bootstrap media object for listing comments
* Fix: Fixed social networks font-size and less identation
* Fix: Fixed min-width on the extra header social links
* New: Added footer text color setting
* Fix: Removed the call to action button border
* Fix: Moved typography colors settings to the typography section
* Fix: Bugfix for the navbar login link setting
* Fix: Excerpts were not displayed in post listings
* Fix: Code improvements


### v 1.46

* Fix: Updated included H5BP .htaccess
* Fix: Updated 404 template based on H5BP
* New: Added EditorConfig
* New: Output author title with get_the_author
* New: Updated to jQuery 1.9.1
* Fix: updated less-php compiler
* New: Updated Bootstrap to 2.3.0 and customized it for less-php
* Fix: fixed buttons functions.php error


### v 1.45

* Fix: Fixed Button colors
* Fix: Bugfixes


### v 1.44

* Fix: Compatibility fix for the WooSidebars plugin
* New: Now using javascript-less social sharing (faster, less load)
* New: Moved scripts to the footer
* New: Added option to allow users to load scripts in the of their document
* New: Added Digg to the social sharing networks list
* New: Combined some scripts in 1 single file (less requests, thus faster page loading)
* Fix: Footer background now defaults to transparent


### v 1.43

* Fix: Typography caching
* Fix: Minor Bugfixes


### v 1.42

* Fix: Fixes the top navbar dropdown
* Fix: Fixes the sharrre script
* New: Added an option in the admin page to disable the customizer caching
* Fix: Re-organized files and functions in the customizer


### v 1.39

* Fix: Fixes the “Warning: join() [function.join]: Invalid arguments passed in wp-includes/post-template.php on line 389″ error.
* New: Changed theme licence from MIT to GPL v.3


### v 1.38

* New: Replaced font-awesome with Elusive Icons
* Fix: Better implementation for the top navbar classes (fixed/static)
* Fix: .htaccess fixes for sharre path


### v 1.37

* New: Added option for fixed navbar (defaults to non-fixed)
* New: Added option for “original-size logo” (defaults to false)
* New: Added option for Navbar Padding (top & bottom)
* New: Added option for flat (no-gradients) on the navbar. Useful if the user has enter a padding in the option above
* Fix: Moved the sharre script to assets/js/vendor


### v 1.36

* New: Added fluid layout option
* New: Added option for menu on the right (navbar)
* Fix: Re-organized some settings in the customizer
* New: The hero-content setting is now a textarea
* Fix: Fixed Button text colors
* Fix: Better login & logout links
* Fix: Fixed sharre rewrite rules on .htaccess


### v 1.35

* New: Updating jQuery to 1.9.0


### v 1.34

* Fix: Minor bugfixes


# v 1.33

* New: moving template title to function
* Fix: Updating the gallery shortcode
* New: Better nice search


### v 1.32

* Fix: Bugfixes and typos


### v 1.31

* New: Better separation of normal and responsive stylesheets
* New: Better administration page
* New: Added option to minimize the CSS
* New: Added page templates for full-width and single-sidebar layouts


### v 1.30

* Fix: Improves sidebar classes generation
* New: Added slide-down widget areas on the primary navbar
* New: Updating to jQuery 1.8.3
* New: Updating to Bootstrap 2.2.2
* New: Added textcolor setting
* New: Added theme supports options to the admin page
* New: Added optional searchbox in the primary navbar


### v 1.20

* New: Added the advanced custom builder
* New: Added webont weight and locale
* Fix: Improved the secondary navbar
* Fix: Re-organized the customizer sections
* New: Social shares using the sharrre script ( http://sharrre.com/ )
* New: Caching the customizer using transients


### v 1.15

* When updating from previous versions, you will have to re-visit the customizer and tweak some settings to make it the way you had it set-up before. This is due to the addition of some new settings.

* New: Added Social sharing links on individual entries (pages, posts & custom post types). Users can choose the location of the share buttons (top, bottom, both, none) and the networks that they want to use (facebook, twitter, googleplus, linkedin, pinterest).
* New: New administration page for the theme: allows users to enter their licence key for automatic updates and also allows addon plugin to hook into the same page. So any addons will not create additional administration pages in the future, resulting in a cleaner admin menu.
* New: Added option to completely hide the top navbar
* New: Added an option to allow adding a separate navbar below the hero section (useful if the top navbar is hidden)
* Fix: Transformed many options to checkboxes instead of dropdowns (cleaner customizer
* Fix: Added new customizer sections
* Fix: Re-arranged customizer controls to include the newly-added sections
* Fix: Bugfixes


### v 1.14

* Fix: Bugfixes
* Fix: Better theming for login buttons (positioning)
* New: Added the ability to display Sidebars on the frontpage (new customizer setting, defaults to “hide”)


### v 1.13

* New: Added second (secondary) sidebar
* Fix: Better layout selection
* Fix: Bugfixes


### v 1.12

* Fix: Important Customizer bugfixes


### v 1.11

* New: Added ability to choose between responsive and fixed-width layouts
* Fix: Compatibility with ubermenu
* New: Added sidebar width selection (ranging from span2 to span6)
* Fix: Various bugfixes & code cleanups


### v 1.10

* New: Updating Bootstrap to 2.2.1
* Fix: Compatibility fixes when upgrading from previous versions
* New: Added basic styling on sidebar lists
* New: Added social links on “NavBar” branding mode
* Fix: Better loading of Google Webfonts
* New: Optional “Affix” sidebar (see http://twitter.github.com/bootstrap/javascript.html#affix )
* New: Added login/logout link in the NavBar


### v 1.0.2

* New: Added “Advanced” section on the customizer, allowing users to enter their own scripts/css on the head and on the footer of the document
* Fix: Social links now get sanitized
* New: Twitter linksnow accept @username
* New: Added “Branding mode” on the header & logo section. CAUTION: This setting does NOT auto-refresh the live preview. You have to save and close the customizer in order to see the changes. You can then re-open the customizer and continue your customizations. We also recoment that you have a logo uploaded otherwise it has no purpose
* New: Added Login/Logout links to the Navbar (optional)


### v 1.0.0
* Initial version
=======
### HEAD
* Update to Bootstrap 3.0.3
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
>>>>>>> 71e23dcae016ced6eff662fb627935b00be69dd9
