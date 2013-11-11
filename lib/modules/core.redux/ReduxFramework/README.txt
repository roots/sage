=== Redux Framework ===
Contributors: nohalfpixels, ghost1227, dovyp
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=N5AD7TSH8YA5U
Tags: admin, admin interface, options, theme options, plugin options, options framework, settings
Requires at least: 3.5.1
Tested up to: 3.7
Stable tag: 3.0.9
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Redux is a simple, truly extensible and fully responsive options framework for WordPress themes and plugins. Ships with an integrated demo.

== Description ==

Redux is a simple, truly extensible and fully responsive options framework for WordPress themes and plugins. Built on the WordPress Settings API, Redux supports a multitude of field types as well as custom error handling, custom field & validation types, and import/export functionality.

But what does Redux actually DO? We don't believe that theme and plugin
developers should have to reinvent the wheel every time they start work on a
project. Redux is designed to simplify the development cycle by providing a
streamlined, extensible framework for developers to build on. Through a
simple, well-documented config file, third-party developers can build out an
options panel limited only by their own imagination in a fraction of the time
it would take to build from the ground up!

= Online Demo =
Don't take our word for it, check out our online demo and try Redux without installing a thing!
[**http://demo.reduxframework.com/wp-admin/**](http://demo.reduxframework.com/wp-admin/)


= Docs & Support =
You can find [docs](http://reduxframework.com/docs/), [FAQs](http://reduxframework.com/docs/) and more detailed information about ReduxFramework on [reduxframework.com](http://reduxframework.com). If you were unable to find the answer to your question on the [FAQs](http://reduxframework.com/docs/), or in any of the [documentation](http://reduxframework.com/docs/), you should search [the issue tracker on Github](https://github.com/ReduxFramework/ReduxFramework/issues). If you can't locate any topics that pertain to your particular issue, [post a new issue](https://github.com/ReduxFramework/ReduxFramework/issues/new) for it.


= Redux Framework Needs Your Support =
It is hard to continue development and support for this free plugin without contributions from users like you. If you enjoy using Redux Framework, and find it useful, please consider [making a donation](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=N5AD7TSH8YA5U). Your donation will help encourage and support the plugin's continued development and better user support.

= Fields Types =

* Border
* Button Set
* Checkbox / Multi-Check
* Color (WordPress Native)
* Gradient
* Date
* Dimensions (Height/Width)
* Editor (WordPress Native)
* Gallery (WordPress Native)
* Group (Repeatable/Non-Repeatable)
* Image Select (Patterns/Presets)
* Info (Header)
* Link Color
* Media (WordPress Native)
* Multi-Text
* Password
* Radio (w/ WordPress Data)
* Select (Select/Multi-Select w/ Select2 & WordPress Data)
* Slider
* Sortable (Drag/Drop Checkbox/Input Fields)
* Sorter (Drag/Drop Manager - Works great for content blocks)
* Spacing (Margin/Padding/Absolute)
* Spinner
* Switch
* Text
* Textarea
* Typography 
 * The most advanced typography module complete with preview, Google fonts, and auto-css output!

= Additional Features =

* Field Validation
* Language Packs
* Full value escaping
* Required - Link visibility from parent fields. Set this to affect the visibility of the field on the parent's value.
* Output CSS Automatically - Redux generates CSS and the appropriate Google Fonts stylesheets for you on select fields. You need only specify the CSS selector to apply the CSS to (limited to certain fields).
* Oh, and did we mention a fully integrated Google Webfonts setup that will make you so happy you'll want to cry?


= Redux Framework is the solution for theme and plugin developers alike. =
At least we think so, we hope you feel the same.

  
= Translators & Non-English Speakers =
We need your help to translate Redux into your language. If you have created your own language pack, or have an update of an existing one, you can post [gettext PO and MO files](http://codex.wordpress.org/Translating_WordPress) to the [Github Repo](https://github.com/ReduxFramework/ReduxFramework) via a pull request or you can post an issue with the attached files. You can download the latest [POT file](http://plugins.svn.wordpress.org/redux-framework/trunk/ReduxCore/languages/redux-framework.pot), and see the latest [PO files in each language](http://plugins.svn.wordpress.org/redux-framework/trunk/ReduxCore/languages/).

= Current Translations =

Special thanks to the following people for language translations:

* German [de_DE] @Abu-Taymiyyah
* Bahasa Indonesia [id_ID] @riesurya
* Italian, Romanian [IT_it] [RO_ro] @tirnovanuaurel
* Spanish [es_ES] [RO_ro] @vertigo7x

= Get Involved =
Redux is an ever-changing, living system. Want to stay up to date or
contribute? Subscribe to one of our mailing lists or join us on [Twitter](https://twitter.com/reduxframework) or [Github](https://github.com/ReduxFramework/ReduxFramework)!

NOTE: Redux is not intended to be used on its own. It requires a config file
provided by a third-party theme or plugin developer to actual do anything
cool!

== Installation ==

= Install the Plugin =
1. Upload the "redux-framework" directory to "~/wp-content/plugins/".
2. Activate the plugin through the "Plugins" area in WordPress admin panel.

= Activate "Demo Mode" =
On the Plugins page, beneith the description and an activated Redux Framework, you will find a Demo Mode link. Click that link to activate or deactivate the sample-config file Redux ships with.

= Start Building Your Own Panel =

1. Copy the "~/redux-framework/sample/" directory from within the plugin to a directory within your own theme or plugin.
2. Click on "Deactivate Demo Mode" in the "Plugins" area of the WordPress admin panel to turn off the Redux integrated demo.
3. Edit the "~/sample/sample-config.php" file (now copied to your plugin or theme directory) and change the $args['opt_name'] value to anything custom you would like. Make sure this is truly unque so other plugins/themes can use Redux.
4. Include the sample-config.php file: `require_once(dirname(__FILE__).'/sample/sample-config.php');` in your theme functions.php file or within your plugin's init file.
5. Modify the sample file to your heart's content.

= For Complete Documentation and Examples =
Visit: [http://reduxframework.com/docs/](http://reduxframework.com/docs/)


== Frequently Asked Questions ==

= Why doesn't this plugin do anything? =

Redux is an options framework... in other words, it's not designed to do anything on its own! You can however activate a demo mode to see how it works. 

= How can I learn more about Redux? =

Visit our website at [http://reduxframework.com/](http://reduxframework.com/)

= You don't have much content in this FAQ section =
That's because the real FAQ section is on our site! Please visit [http://reduxframework.com/docs/faqs/](http://reduxframework.com/docs/faqs/

== Screenshots ==

1. This is the demo mode of Redux Framework. Activate it and you will find a fully-functional admin panel that you can play with. On the Plugins page, beneath the description and an activated Redux Framework, you will find a Demo Mode link. Click that link to activate or deactivate the sample-config file Redux ships with.  Don't take our word for it, check out our online demo and try Redux without installing a thing! [**http://demo.reduxframework.com/wp-admin/**](http://demo.reduxframework.com/wp-admin/)

== Changelog ==

= 3.0.9 =
* Feature - Added possibility to set default icon class for all sections and tabs.
* Feature - Make is to the WP dir can be moved elsewhere and Redux still function.
* Added Spanish Language. Thanks @vertigo7x.
* Fix Issue 5 - Small RGBA validation fix.
* Fix Issue 176 - Fold by Image Select. Thanks @andreilupu.
* Fix Issue 194 - Custom taxonomy terms in select field.
* Fix Issue 195 - Border defaults not working.
* Fix Issue 197 - Hidden elements were showing up on a small screen. Thanks @ThinkUpThemes.
* Fix issue 200 - Compiler not working with media field.
* Fix Issue 201 - Spacing field not using default values.
* Fix Issue 202 - Dimensions field not using units.
* Fix Issue 208 - Checkbox + Required issue.
* Fix Issue 211 - Google Font default not working on page load.
* Fix Issue 214 - Validation notice not working for fields.
* Fix Issue 181/224 - Firefox 24 image resize errors.
* Fix Issue 223 - Slides were losing the url input field for the image link.
* Fix - Various issues in the password field.
* Fixed various spelling issues and typos in sample-config file.
* Initialize vars before extract() - to shut down undefined vars wargnings.
* Various other fixes.

= 3.0.8 =
* Version push to ensure all bugs fixes were deployed to users. Various.

= 3.0.7 =
* Feature - Completely redone spacing field. Choose to apply to sides or all at once with CSS output!
* Feature - Completely redone border field. Choose to apply to sides or all at once with CSS output!
* Feature - Added opt-in anonymous tracking, allowing us to further analyze usage.
* Feature - Enable weekly updates of the Google Webfonts cache is desired. Also remove the Google Webfont files from shipping with Redux. Will re-download at first panel run to ensure users always have the most recent copy.
* Language translation of german updated alone with ReduxFramework pot file.
* Fix Issue 146 - Spacing field not storing data.
* Fix - Firefox field description rendering bug.
* Fix - Small issue where themes without tags were getting errors from the sample data.

= 3.0.6 =
* Hide customizer fields by default while still under development.
* Fix Issue 123 - Language translations to actually function properly embedded as well as in the plugin.
* Fix Issue 151 - Media field uses thumbnail not full image for preview. Also now storing the thumbnail URL. Uses the smallest available size as the thumb regardless of the name.
* Fix Issue 147 - Option to pass params to select2. Contributed by @andreilupu. Thanks!
* Added trim function to ace editor value to prevent whitespace before and after value keep being added
* htmlspecialchars() value in pre editor for ace. to prevent html tags being hidden in editor and rendered in dom
* Feature: Added optional 'add_text' argument for multi_text field so users can define button text.
* Added consistent remove button on multi text, and used sanitize function for section id
* Feature: Added roles as data for field data
* Feature: Adding data layout options for multi checkbox and radio, we now have quarter, third, half, and full column layouts for these fields.
* Feature: Eliminate REDUX_DIR and REDUX_URL constants and instead created static ReduxFramework::$_url and ReduxFramework::$_dir for cleaner code.
Feature: Code at bottom of sample-config.php to hide plugin activation text about a demo plugin as well as code to demo how to hide the plugin demo_mode link.
* Started work on class definitions of each field and class. Preparing for the panel builder we are planning to make.

= 3.0.5 =
* Fixed how Redux is initialised so it works in any and all files without hooking into the init function.
* Issue #151: Added thumbnails to media and displayed those instead of full image.
* Issue #144: Slides had error if last slide was deleted.
* Color field was outputting hex in the wrong location.
* Added ACE Editor field, allowing for better inline editing.

= 3.0.4 =
* Fixed an odd saving issue.
* Fixed link issues in the framework
* Issue #135: jQuery UI wasn't being properly queued
* Issue #140: Admin notice glitch. See http://reduxframework.com/2013/10/wordpress-notifications-custom-options-panels/
* Use hooks instead of custom variable for custom admin CSS
* Added "raw" field that allows PHP or a hook to embed anything in the panel.
* Submenus in Admin now change the tabs without reloading the page.
* Small fix for multi-text.
* Added IT_it and RO_ro languages.
* Updated readme file for languages.

= 3.0.3 =
* Fixed Issue #129: Spacing field giving an undefined.
* Fixed Issue #131: Google Fonts stylesheet appending to body and also to the top of the header. Now properly placed both at the end of the head tag as to overload any theme stylesheets.
* Fixed issue #132 (See #134, thanks @andreilupu): Could not have multiple WordPress Editors (wp_editor) as the same ID was shared. Also fixed various styles to match WordPress for this field.
* Fixed Issue #133: Issue when custom admin stylesheet was used, a JS error resulted.

= 3.0.2 =
* Improvements to slides, various field fixes and improvements. Also fixed a few user submitted issues.

= 3.0.1 =
* Backing out a bit of submitted code that caused the input field to not properly break.

= 3.0.0 =
* Initial WordPress.org plugin release.

== Upgrade Notice ==

= 3.0 =
Redux is now hosted on WordPress.org! Update in order to get proper, stable updates.

== Attribution ==

Redux is was originally based off the following frameworks:

* [NHP](https://github.com/leemason/NHP-Theme-Options-Framework) 
* [SMOF](https://github.com/syamilmj/Options-Framework "Slightly Modified Options Framework")

It has now branched and been improved in many ways. If you like what you see, realize this is a labor of love. Please [donate to the Redux Framework](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=N5AD7TSH8YA5U) if you are able.
