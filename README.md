# [Roots Starter Theme](http://roots.io/)
[![Build Status](https://travis-ci.org/roots/roots.svg)](https://travis-ci.org/roots/roots)
[![devDependency Status](https://david-dm.org/roots/roots/dev-status.svg)](https://david-dm.org/roots/roots#info=devDependencies)

Roots is a WordPress starter theme based on [HTML5 Boilerplate](http://html5boilerplate.com/) & [Bootstrap](http://getbootstrap.com/) that will help you make better themes.

* Source: [https://github.com/roots/roots](https://github.com/roots/roots)
* Homepage: [http://roots.io/](http://roots.io/)
* Documentation: [http://roots.io/docs/](http://roots.io/docs/)
* Twitter: [@rootswp](https://twitter.com/rootswp), [@retlehs](https://twitter.com/retlehs), [@swalkinshaw](https://twitter.com/swalkinshaw), [@Foxaii](https://twitter.com/Foxaii), [@c2foryou](https://twitter.com/c2foryou) [@austinpray](https://twitter.com/austinpray)
* Newsletter: [Subscribe](http://roots.io/subscribe/)
* Forum: [http://discourse.roots.io/](http://discourse.roots.io/)

## Features

* [gulp](http://gulpjs.com/) for compiling Sass and LESS, checking for JavaScript errors, live reloading, concatenating and minifying files, and versioning assets
* [Bower](http://bower.io/) for front-end package management
* [HTML5 Boilerplate](http://html5boilerplate.com/)
  * The latest [jQuery](http://jquery.com/) via Google CDN, with a local fallback
  * The latest [Modernizr](http://modernizr.com/) build for feature detection
  * An optimized Google Analytics snippet
* [Bootstrap](http://getbootstrap.com/)
* ARIA roles and microformats
* [Theme activation](http://roots.io/roots-101/#theme-activation)
* [Theme wrapper](http://roots.io/an-introduction-to-the-roots-theme-wrapper/)
* Cleaner HTML output of navigation menus
* Posts use the [hNews](http://microformats.org/wiki/hnews) microformat
* [Multilingual ready](http://roots.io/wpml/) and over 30 available [community translations](https://github.com/roots/roots-translations)

### Go further with Roots

#### Clean up WordPress
Install the [Soil](https://github.com/roots/soil) plugin to enable additional features:

* Root relative URLs
* Nice search (`/search/query/`)
* Cleaner output of `wp_head` and enqueued assets markup

#### Modernize your WordPress stack
[Bedrock](https://github.com/roots/bedrock) gets you started with the best development tools, practices, and project structure.

* Dependency management with Composer
* Automated deployments with Capistrano
* Easy environment specific configuration

## Installation

Clone the git repo - `git clone git://github.com/roots/roots.git` - or [download it](https://github.com/roots/roots/zipball/master) and then rename the directory to the name of your theme or website.

If you don't use [Bedrock](https://github.com/roots/bedrock), you'll need to add the following to your `wp-config.php` on your development installation:

```php
define('WP_ENV', 'development');
```

## Theme activation

Reference the [theme activation](http://roots.io/roots-101/#theme-activation) documentation to understand everything that happens once you activate Roots.

## Configuration

Edit `lib/config.php` to enable or disable theme features and to define a Google Analytics ID.

Edit `lib/init.php` to setup navigation menus, post thumbnail sizes, post formats, and sidebars.

## Theme development

Roots uses [gulp](http://gulpjs.com/) as its build system.

### Install gulp

**Unfamiliar with npm? Don't have node installed?** [Download and install node.js](http://nodejs.org/download/) before proceeding.

From the command line:

1. Install [gulp](http://gulpjs.com) and [bower](http://bower.io) globally with `npm install -g gulp bower`.
2. Navigate to the theme directory, then run `npm install`. 

You now have all the necessary dependencies to run the build process.

### Available gulp commands

* `gulp` — Compile and optimize the files in your assets directory
* `gulp watch` — Compile assets when file changes are made
* `gulp --tasks` — Lists all the available tasks and what they do

## Documentation

* [Roots 101](http://roots.io/roots-101/) — A guide to installing Roots, the files, and theme organization
* [Theme Wrapper](http://roots.io/an-introduction-to-the-roots-theme-wrapper/) — Learn all about the theme wrapper
* [Build Script](http://roots.io/using-gulp-for-wordpress-theme-development/) — A look into how Roots uses gulp
* [Roots Sidebar](http://roots.io/the-roots-sidebar/) — Understand how to display or hide the sidebar in Roots

## Contributing

Contributions are welcome from everyone. We have [contributing guidelines](CONTRIBUTING.md) to help you get started. You can help out by:

* Reporting issues (please follow the [issue guidelines](https://github.com/necolas/issue-guidelines))
* Fixing [issues](https://github.com/roots/roots/issues)
* Suggesting new features
* Answering questions on the [forum](http://discourse.roots.io/)

## Support

Use the [Roots Discourse](http://discourse.roots.io/) to ask questions and get support.
