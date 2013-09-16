# Redux Options Framework v3.0.0 Beta [![Build Status](https://secure.travis-ci.org/ghost1227/Redux-Framework.png?branch=master)](http://travis-ci.org/ghost1227/Redux-Framework)

## Please help us beta test. As soon as the community verifies there are no bugs, we will release.

Wordpress options framework which uses the [WordPress Settings API](http://codex.wordpress.org/Settings_API "WordPress Settings API"), Custom Error/Validation Handling, Custom Field/Validation Types, and import/export functionality.

## Donate to the Framework ##

If you can, please donate to help support the ongoing development of Redux Framework!

[![Donate to the framework](https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif "Donate to the framework")](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=N5AD7TSH8YA5U)

## Features ##

* Uses the [WordPress Settings API](http://codex.wordpress.org/Settings_API "WordPress Settings API")
* Multiple built in field types
* Multple layout field types
* Fields can be over-ridden with a callback function, for custom field types
* Easily extendable by creating Field Classes
* Built in Validation Classes
* Easily extendable by creating Validation Classes
* Custom Validation error handling, including error counts for each section, and custom styling for error fields
* Custom Validation warning handling, including warning counts for each section, and custom styling for warning fields
* Multiple Hook Points for customisation
* Import / Export Functionality - including cross site importing of settings
* Easily add page help through the class
* Much more

## Stay In The Loop! ##

[![Follow us on Twitter](http://iod.unh.edu/Images/Twitter_follow_us.png "Follow us on Twitter")](https://www.twitter.com/ReduxFramework)

## Getting Redux ##

Redux can be downloaded in several ways which are outlined below. Please use whichever way you are most comfortable with.

### Download as a .zip archive ###

* Stable release: [download](https://github.com/ghost1227/Redux-Framework/archive/stable.zip) 
* Dev release: [download](https://github.com/ghost1227/Redux-Framework/archive/master.zip)

Once you have downloaded the framework, upload the .zip file to the root of your project and unzip it as follows:

```bash
$ cd my-project
$ unzip Redux-Framework-master.zip -d Redux-Framework
```

### Cloning the repository using git ###

*Stable release*
```bash
$ cd my-project
$ git clone git://github.com/ghost1227/Redux-Framework/ -b stable
```

*Dev release*
```bash
$ cd my-project
$ git clone git://github.com/ghost1227/Redux-Framework/
```

### Cloning the repository as a git submodule ###

*Stable release*
```bash
$ cd my-project
$ git submodule add git://github.com/ghost1227/Redux-Framework/ -b stable
```

*Dev release*
```bash
$ cd my-project
$ git submodule add git://github.com/ghost1227/Redux-Framework/
```

## Setting up Redux ##

Copy the included options.php file outside of the Redux folder (recommended).

Include Redux in your theme ```functions.php``` or plugin as follows:

```php
require_once('path/to/copied/options.php');
```

Edit ```options.php``` as needed.

## FAQs ##

1. Why should we use ```require_once``` instead of ```get_template_part```?
 * First, because ```get_template_part``` is for... you guessed it, themes! Redux is designed to work with both themes *and* plugins.
 * Second, read [this](http://kovshenin.com/2013/get_template_part/).
2. Why shouldn't we edit ```defaults.php```?
 * Because ```defaults.php``` is for *defaults*. Anything that is defined in ```defaults.php``` can be overridden in ```options.php```.

## Are you using Redux? ##

Send me an email at ghost1227@reduxframework.com so I can add you to our user spotlight!

## Changelog ##

### Development Branch ###

* Added option to override ```icon_type``` per icon
* Minor bug/versioning fixes
* Added Font Awesome intro
* Added ```raw_html``` option
* Added ```text_sortable``` option
* Switched from Aristo to Bootstrap jQuery UI theme

### Version 2.0.0 (January 31, 2013) ###

* Fixed SSL error which occurred occasionally with Google Webfonts 
* Added optional flag for ```wpautop``` on editors
* Added password field type
* Added ```checkbox_hide_all``` option
* Added WP3.5 media chooser
* Added Google webfonts previews
* Updated to WP3.5 color picker
* Minor style tweaks
* Added graphical 'switch' option for checkboxes
* Removed dependency on class extension for fields
* Deprecated icons in favor of iconfonts

### Version 1.0.0 (December 5, 2012) ###

* Based on NHP Theme Options Framework v1.0.6
* Cleaned up codebase
* Changed option group name to allow multiple instances
* Changed checkbox name attribute to id
* Added rows attribute to textareas
* Removed extra linebreak in upload field
* Set default menu position to null to avoid conflicts
* Added sample content for dashboard credit line
* Minor style changes
* Changed name of upload button
* Refactored Google Webfonts function
* Replaced ```stylesheet_override``` with ```admin_stylesheet```
* Made text domain a constant
* Removed PHP closing tags to prevent issues with newlines
* Added option to define custom start tab
