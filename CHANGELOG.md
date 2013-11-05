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