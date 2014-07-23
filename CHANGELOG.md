### 3.2.6

* Fix: Undefined index notices on theme activation
* Fix: Deprecated functions compatibility
* Fix: Featured Image dimentions for archives
* New: Post Format meta element
* New: Moved all Redux options to a single file
* New: Implemented Redux SubSections
* Fix: Allow adding subsections fia a filter
* Fix: Allow adding options via a filter
* Fix: Code cleanups
* Fix: Share links
* Fix: Layouts per post type
* Fix: Removed absolute link to local.wordpress.dev in the stylesheet
* Fix: typos
* Fix: Socail Share Networks compatibility with latest Redux

### 3.2.5

* Fix: Compatibility with WordPress 3.9
* Fix: Header Background
* Fix: Header Text Colors
* Fix: Jumbotron Text Size
* Fix: Regenerating .pot file
* Fix: Titles on archives
* Fix: Changed default height of textareas to 10em.
* Fix: TGL now only loads if Redux is not installed
* Fix: Removed container class from Extra Header widgets.
* New: Support HTML5 Galleries & Captions ([feature added in WP 3.9](http://make.wordpress.org/core/2014/04/15/html5-galleries-captions-in-wordpress-3-9/#comments))

### 3.2.4

* Fix: Footer layout fixes.
* Fix: Metadata now aligned to the left.
* Fix: Performance improvements
* Fix: Removed the updater nag
* Fix: Reset stylesheet path & url transients on compile
* Fix: Fatal error when mb_convert_encoding is not installed on the server
* Fix: Removed \<hr\> from breadcrumbs
* Fix: Removed \<hr\> form pagination links.
* New: Started simplified customizer implementation
* New: Bootstrap styling for non-bootstrap form elements
* New: Started BuddyPress integration. (activity)

### 3.2.3

* Fix: Removing deprecated github-updater zip file
* Fix: Better updater nag.
* Fix: No more https errors when domain mapping is used.
* Fix: Float featured images on archives to the left.
* Fix: Removing \<hr\> from content-single.
* New: Added comment count to Post Meta.
* New: Added default styling for CF7 (conditionally, only when CF7 is installed).

### 3.2.2

* Fix: Header backgrounds
* Fix: Syntax Improvements
* Fix: Better Less compiler class
* Fix: Stylesheets compiling on XAMPP & Windows servers

### 3.2.1

* Fix: Header margins and paddings
* Fix: Social buttons URL decoding
* Fix: Menu classes transliteration
* Fix: Fixed fatal error on XAMPP development instalations

### 3.2

* Complete rewrite [link](https://github.com/shoestrap/shoestrap-3/pull/479)

### 3.1.0.2

* Fix: allow https in assets urls
* Fix: path to github-updater plugin

### 3.1.0.1

* Updating jQuery to 1.11.0
* Fix: Re-Organizing Admin section settings
* New: Modules are now Self-Contained.
* Fix: Re-organizing file structure for modules
* Fix: Fixed inline style generation of header margin. (props @larruda)
* Fix: Adding variables using the shoestrap_compiler filter
* FIx: Variables and Less files are now per-module.
* Fix: Other Bugfixes

### 3.1.0.0

* New: Updating to Bootstrap 3.1.0
* Fix: Removed the presets module
* Fix: Updating dependencies
* New: Removed the PHP compiler from vore and including it as an external dependency
* Fix: Jumbotron border color
* New: Removed ReduxFramework from the theme core and including it as a dependency.
* Fix: Removed link to github repo from redux footer until redux is updated in the WordPress repository
* Fix: Removed the palettes module

### 3.0.309

Important notice:

This update will change the URL of your CSS files.
PLEASE re-compile your stylesheets after updating.

* Fix: Palettes module improvements
* Fix: Better navlist walker
* New: Using github updater
* New: Included TGM Class
* Fix: Better Megadrop triggers
* Fix: Slightly better bbPress breadcrumbs
* New: Ability to toggle container class in navbar on/off #459
* Fix: Collapsed nav padding
* Fix: Navbar toggle color
* Fix: Jumbotron background
* New: New screenshot
* Fix: Footer bugfix
* New: left-static navbar
* Fix: Updating the Less.php compiler
* Fix: Deleting obsolete style.css file
* Fix: Rewrote the stylesheet detection script
* New: Navlists now use variables to set their colors
* New: added more color functions
* Fix: moved bootstrap files to vendor/bootstrap
* Fix: better color handling for button font color
* Fix: Avatar class
* Fix: Other bugfixes

### 3.0.308

* Fix: Better calculation of color differences in the compiler
* New: Added experimentan "palettes" module
* Fix: PostMeta bugfix
* Fix: Typos
* Fix: Bugfix Body Margins
* Fix: Better Pagination implementation
* Fix: Better implementation of Admin Toolbar toggle
* Fix: Bugfix Custom Dimensions in Featured Images
* Fix: Header override colors now work
* Fix: Better implementation of share button
* Fix: Other Minor buxfixes

### 3.0.307

* Fix: recoding the meta module
* Fix: sanitizing colors in the compiler
* New: Introducing some new color functions
* New: moving color functions to a separate file
* New: Added @grid-float-breakpoint control
* Fix: Updating the Less Compiler
* Fix: other minor improvements

### 3.0.3.06

* Fix: Social links in navbars
* Fix: Social sharing actions
* Fix: Re-added option to force-hide the adminbar
* Fix: Better including of compiler classes
* Fix: Removed advanced mode from admin modules
* Fix: Breadcrumbs improvements
* Fix: Footer Border
* Fix: Background Patterns
* Fix: adding default pot and po files
* Fix: bugfix sidebars action-hook
* Fix: non php enclosed functions
* Fix: Redux update

### 3.0.3.05

* Fix: Custom templates now properly detect custom post types
* Fix: Default settings now properly set on Redux
* Fix: ReduxFramework Updates
* Fix: Compiler improvements
* Fix: Margins & Paddings on mibile views

### 3.0.3.04

* Fix: Updates + Bugfixes on the ReduxFramework
* Fix: Safer including of Cache.php
* Fix: Theme menu now called "Theme Options" under the "Appearance" parent menu

### 3.0.3.03

* Fix: excerpt length now works as expected
* Fix: background images now work as expected
* Fix: Removed adminbar option. This is plugin functionality and does not belong in a theme.

### 3.0.3.02

* New: Added advanced mode toggles on many admin sections
* New: Re-coded licencing module.
* New: Now supports non-responsive layouts
* Fix: Removed plugin functionality from the theme
* Fix: Grunt implementation now functional
* Fix: Removed changing media folder control
* Fix: Updated php.less compiler to 1.5.rc2
* Fix: Compiler Improvements
* Fix: Rewrote the Breadcrumbs
* Fix: Layout bugfixes
* Fix: Syntax Improvements
* New: "more" text control
* Fix: removed deprecated icon-large classes for redux
* Fix: Better support for child-theme stylesheets
* Fix: Retina description text
* Fix: Better relative URLs support
* Fix: Updated ReduxFramework
* New: pager/pagination switch
* Fix: Reorder html5shiv.js & respond.js
* Fix: proper z-indexes for navbars

### 3.0.3.01

* Bugfixes

### 3.0.3

* Fix: Updating Redux Framework
* Fix: fixed deprecated class name for unstyled lists
* Fix: bugfix for avatar image in WP admin bar
* New: Added the 'shoestrap_compiler' filter to the compiler
* Fix: Simplifications to the compiler
* New: Update to Bootstrap 3.0.3
* New: Adding NavList Navwalker to widget menus
* Fix: Removed lists styles from all list menus in sidebars

### 3.0.2.01

* Fix: Updating Redux Framework
* Fix: fixed deprecated class name for unstyled lists
* Fix: bugfix for avatar image in WP admin bar
* New: Added the 'shoestrap_compiler' filter to the compiler
* Fix: Simplifications to the compiler
* New: Update to Bootstrap 3.0.3
* New: Adding [NavList Navwalker](https://github.com/twittem/wp-bootstrap-navlist-walker) to widget menus
* Fix: Removed lists styles from all list menus in sidebars

### 3.0.2

* Fix: Bugfixing the meta module for older PHP versions
* New: Updated Bootstrap to version 3.0.2
* New: Changed the compiler and moved from [lessphp](http://leafo.net/lessphp/) to [less.php](http://lessphp.gpeasy.com/)
* New: Updated HTML5 Shiv to v3.7.0
* New: Updated modernizr to 2.7.0
* Fix: Removed duplicate wp_footer() call
* New: Added optional alternative navwalker: [https://github.com/twittem/wp-bootstrap-navwalker](https://github.com/twittem/wp-bootstrap-navwalker)
* Fix: Rewrote base.php
* New: Added layouts via templates for pages
* Fix: Updated [Redux Framework](http://reduxframework.com/)

* Fix: Minor bugfixes

### 3.0.10

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

### 1.51

* New: Added Featured images section on the customizer
* New: Added timthumb alternative script
* Better Implementation of the Google Webfonts API

### 1.50

* Fix: Updated the Elusive-Icons Webfont
* New: Added the ability for Customizer help-texts
* New: Added image resizing script from [https://github.com/matthewruddy/wordpress-timthumb-alternative/](https://github.com/matthewruddy/wordpress-timthumb-alternative/)
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

### 1.49

* Fix: template &amp; syntax improvements
* Fix: better functions.php
* Fix: simplifying some template files and omitting the closing ?&gt; php tag
* New: Admin notices for developer mode
* Fix: Better comment template
* Fix: adding missing classes to the top nav

### 1.48

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

### 1.47

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

### 1.46

* Fix: Updated included H5BP .htaccess
* Fix: Updated 404 template based on H5BP
* New: Added EditorConfig
* New: Output author title with get_the_author
* New: Updated to jQuery 1.9.1
* Fix: updated less-php compiler
* New: Updated Bootstrap to 2.3.0 and customized it for less-php
* Fix: fixed buttons functions.php error

### 1.45

* Fix: Fixed Button colors/li>
* Fix: Bugfixes

### 1.44

* Fix: Compatibility fix for the WooSidebars plugin
* New: Now using javascript-less social sharing (faster, less load)
* New: Moved scripts to the footer
* New: Added option to allow users to load scripts in the of their document
* New: Added Digg to the social sharing networks list
* New: Combined some scripts in 1 single file (less requests, thus faster page loading)
* Fix: Footer background now defaults to transparent

### 1.43

* Fix: Typography caching
* Fix: Minor Bugfixes

### 1.42

* Fix: Fixes the top navbar dropdown
* Fix: Fixes the sharrre script
* New: Added an option in the admin page to disable the customizer caching
* Fix: Re-organized files and functions in the customizer

### 1.39

* Fix: Fixes the “Warning: join() [function.join]: Invalid arguments passed in wp-includes/post-template.php on line 389″ error.
* New: Changed theme licence from MIT to GPL v.3

### 1.38

* New: Replaced font-awesome with Elusive Icons
* Fix: Better implementation for the top navbar classes (fixed/static)
* Fix: .htaccess fixes for sharre path

### 1.37

* New: Added option for fixed navbar (defaults to non-fixed)
* New: Added option for “original-size logo” (defaults to false)
* New: Added option for Navbar Padding (top &amp; bottom)
* New: Added option for flat (no-gradients) on the navbar. Useful if the user has enter a padding in the option above
* Fix: Moved the sharre script to assets/js/vendor

### 1.36

* New: Added fluid layout option
* New: Added option for menu on the right (navbar)
* Fix: Re-organized some settings in the customizer
* New: The hero-content setting is now a textarea
* Fix: Fixed Button text colors
* Fix: Better login &amp; logout links
* Fix: Fixed sharre rewrite rules on .htaccess

### 1.35

* New: Updating jQuery to 1.9.0

### 1.34

* Fix: Minor bugfixes

### 1.33

* New: moving template title to function
* Fix: Updating the gallery shortcode
* New: Better nice search

### 1.32

* Fix: Bugfixes and typos

### 1.31

* New: Better separation of normal and responsive stylesheets
* New: Better administration page
* New: Added option to minimize the CSS
* New: Added page templates for full-width and single-sidebar layouts

### 1.30

* Fix: Improves sidebar classes generation
* New: Added slide-down widget areas on the primary navbar
* New: Updating to jQuery 1.8.3
* New: Updating to Bootstrap 2.2.2
* New: Added textcolor setting
* New: Added theme supports options to the admin page
* New: Added optional searchbox in the primary navbar

### 1.20

* New: Added the advanced custom builder
* New: Added webont weight and locale
* Fix: Improved the secondary navbar
* Fix: Re-organized the customizer sections
* New: Social shares using the sharrre script ( [http://sharrre.com/](http://sharrre.com/) )
* New: Caching the customizer using transients

### 1.15

* When updating from previous versions, you will have to re-visit the customizer and tweak some settings to make it the way you had it set-up before. This is due to the addition of some new settings.

* New: Added Social sharing links on individual entries (pages, posts &amp; custom post types). Users can choose the location of the share buttons (top, bottom, both, none) and the networks that they want to use (facebook, twitter, googleplus, linkedin, pinterest).

* New: New administration page for the theme: allows users to enter their licence key for automatic updates and also allows addon plugin to hook into the same page. So any addons will not create additional administration pages in the future, resulting in a cleaner admin menu.
* New: Added option to completely hide the top navbar
* New: Added an option to allow adding a separate navbar below the hero section (useful if the top navbar is hidden)
* Fix: Transformed many options to checkboxes instead of dropdowns (cleaner customizer
* Fix: Added new customizer sections
* Fix: Re-arranged customizer controls to include the newly-added sections
* Fix: Bugfixes

### 1.14

* Fix: Bugfixes
* Fix: Better theming for login buttons (positioning)
* New: Added the ability to display Sidebars on the frontpage (new customizer setting, defaults to “hide”)

### 1.13

* New: Added second (secondary) sidebar
* Fix: Better layout selection
* Fix: Bugfixes

### 1.12

* Fix: Important Customizer bugfixes

### 1.11

* New: Added ability to choose between responsive and fixed-width layouts
* Fix: Compatibility with ubermenu
* New: Added sidebar width selection (ranging from span2 to span6)
* Fix: Various bugfixes &amp; code cleanups

### 1.10

* New: Updating Bootstrap to 2.2.1
* Fix: Compatibility fixes when upgrading from previous versions
* New: Added basic styling on sidebar lists
* New: Added social links on “NavBar” branding mode
* Fix: Better loading of Google Webfonts
* New: Optional “Affix” sidebar (see [http://twitter.github.com/bootstrap/javascript.html#affix](http://twitter.github.com/bootstrap/javascript.html#affix) )
* New: Added login/logout link in the NavBar

### 1.0.2

* New: Added “Advanced” section on the customizer, allowing users to enter their own scripts/css on the head and on the footer of the document
* Fix: Social links now get sanitized
* New: Twitter linksnow accept @username
* New: Added “Branding mode” on the header &amp; logo section. CAUTION: This setting does NOT auto-refresh the live preview. You have to save and close the customizer in order to see the changes. You can then re-open the customizer and continue your customizations. We also recoment that you have a logo uploaded otherwise it has no purpose
* New: Added Login/Logout links to the Navbar (optional)

### 1.0.0

* Initial version
