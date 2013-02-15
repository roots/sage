# [Roots Theme](http://www.rootstheme.com/)

Roots is a starting WordPress theme made for developers that’s based on
[HTML5 Boilerplate](http://html5boilerplate.com/) and [Bootstrap from Twitter](http://twitter.github.com/bootstrap/).

* Source: [https://github.com/retlehs/roots](https://github.com/retlehs/roots)
* Home Page: [http://www.rootstheme.com/](http://www.rootstheme.com/)
* Twitter: [@retlehs](https://twitter.com/retlehs)
* Google Group: [http://groups.google.com/group/roots-theme](http://groups.google.com/group/roots-theme)

## Installation

* Clone the git repo - `git clone git://github.com/retlehs/roots.git` - or [download it](https://github.com/retlehs/roots/zipball/master)
* Reference the [theme activation](doc/activation.md) documentation to understand
everything that happens once you activate Roots

## Configuration

Edit `lib/config.php` to enable or disable support for various theme functions
and to define constants that are used throughout the theme.

Edit `lib/init.php` to setup custom navigation menus and post thumbnail sizes.

## Documentation

Take a look at the [documentation table of contents](doc/TOC.md).

## Features

* HTML5 Boilerplate’s markup and `.htaccess`
* Bootstrap from Twitter
* [Theme wrapper](doc/wrapper.md)
* Root relative URLs
* Clean URLs (no more `/wp-content/`)
* All static theme assets are rewritten to the website root (`/assets/css/`,
`/assets/img/`, and `/assets/js/`)
* Cleaner HTML output of navigation menus
* Cleaner output of `wp_head` and enqueued scripts/styles
* Posts use the [hNews](http://microformats.org/wiki/hnews) microformat
* [Multilingual ready](http://www.rootstheme.com/wpml/) (Brazilian Portuguese,
Bulgarian, Catalan, Danish, Dutch, English, Finnish, French, German, Hungarian,
Indonesian, Italian, Korean, Macedonian, Norwegian, Polish, Russian, Simplified
Chinese, Spanish, Swedish, Traditional Chinese, Turkish, Vietnamese)

### Build Script

The [grunt branch](https://github.com/retlehs/roots/tree/grunt) contains a build
script powered by grunt. More information can be found at [Integrating grunt.js with Roots](http://benword.com/integrating-grunt-js-with-roots/).

* Easily compile LESS files
* Minification and concatenation without plugins
* Fewer requests made to the server (one CSS file, one main JS file besides
Modernizr and jQuery)
* Ensures valid JavaScript
* Others working on your project are able to use the same build script and have
a unified development process
* Code is optimized for production use

## Contributing

Everyone is welcome to help [contribute](CONTRIBUTING.md) and improve this project.
There are several ways you can contribute:

* Reporting issues (please read [issue guidelines](https://github.com/necolas/issue-guidelines))
* Suggesting new features
* Writing or editing [docs](doc/TOC.md)
* Writing or refactoring code
* Fixing [issues](https://github.com/retlehs/roots/issues)
* Replying to questions on the [Google Group](http://groups.google.com/group/roots-theme)

## Support

Use the [Google Group](http://groups.google.com/group/roots-theme) to ask
questions and get support.
