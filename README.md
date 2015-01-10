# [Sage Starter Theme](http://roots.io/sage/)
[![Build Status](https://travis-ci.org/roots/roots.svg)](https://travis-ci.org/roots/roots)
[![devDependency Status](https://david-dm.org/roots/roots/dev-status.svg)](https://david-dm.org/roots/roots#info=devDependencies)

Sage is a WordPress starter theme based on [HTML5 Boilerplate](http://html5boilerplate.com/) that will help you make better themes.

* Source: [https://github.com/roots/sage](https://github.com/roots/sage)
* Homepage: [http://roots.io/sage/](http://roots.io/sage/)
* Documentation: [http://roots.io/sage/getting-started/](http://roots.io/sage/getting-started/)
* Twitter: [@rootswp](https://twitter.com/rootswp), [@retlehs](https://twitter.com/retlehs), [@swalkinshaw](https://twitter.com/swalkinshaw), [@Foxaii](https://twitter.com/Foxaii), [@c2foryou](https://twitter.com/c2foryou), [@austinpray](https://twitter.com/austinpray)
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
* [Theme wrapper](http://roots.io/sage/getting-started/theme-wrapper/)
* Cleaner HTML output of navigation menus
* Posts use the [hNews](http://microformats.org/wiki/hnews) microformat
* [Multilingual ready](http://roots.io/wpml/) and over 30 available [community translations](https://github.com/roots/sage-translations)

### Go further with Sage

#### Clean up WordPress
Install the [Soil](https://github.com/roots/soil) plugin to enable additional features:

* Cleaner output of `wp_head` and enqueued assets
* Root relative URLs
* Nice search (`/search/query/`)

#### Modernize your WordPress stack
[Bedrock](https://github.com/roots/bedrock) gets you started with the best development tools, practices, and project structure:

* Dependency management with Composer
* Automated deployments with Capistrano
* Easy environment specific configuration

## Installation

Clone the git repo - `git clone https://github.com/roots/sage.git` and then rename the directory to the name of your theme or website.

If you don't use [Bedrock](https://github.com/roots/bedrock), you'll need to add the following to your `wp-config.php` on your development installation:

```php
define('WP_ENV', 'development');
```

## Configuration

Edit `lib/config.php` to enable or disable theme features and to define a Google Analytics ID.

Edit `lib/init.php` to setup navigation menus, post thumbnail sizes, post formats, and sidebars.

## Theme development

Sage uses [gulp](http://gulpjs.com/) as its build system and [Bower](http://bower.io/) to manage front-end packages.

### Install gulp and Bower

**Unfamiliar with npm? Don't have node installed?** [Download and install node.js](http://nodejs.org/download/) before proceeding.

From the command line:

1. Install [gulp](http://gulpjs.com) and [Bower](http://bower.io/) globally with `npm install -g gulp bower`
2. Navigate to the theme directory, then run `npm install`

You now have all the necessary dependencies to run the build process.

### Available gulp commands

* `gulp` — Compile and optimize the files in your assets directory
* `gulp watch` — Compile assets when file changes are made
* `gulp --tasks` — Lists all the available tasks and what they do

## Documentation

Sage documentation is available at [http://roots.io/sage/getting-started/](http://roots.io/sage/getting-started/).

## Contributing

Contributions are welcome from everyone. We have [contributing guidelines](CONTRIBUTING.md) to help you get started.

## Community

Keep track of development and community news.

* Participate on the [Roots Discourse](http://discourse.roots.io/)
* Follow [@rootswp on Twitter](https://twitter.com/rootswp)
* Read and subscribe to the [Roots Blog](http://roots.io/blog/)
