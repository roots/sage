# [Sage](https://roots.io/sage/)
[![Packagist](https://img.shields.io/packagist/vpre/roots/sage.svg?style=flat-square)](https://packagist.org/packages/roots/sage)
[![devDependency Status](https://img.shields.io/david/dev/roots/sage.svg?style=flat-square)](https://david-dm.org/roots/sage#info=devDependencies)
[![Build Status](https://img.shields.io/travis/roots/sage.svg?style=flat-square)](https://travis-ci.org/roots/sage)

Sage is a WordPress starter theme with a modern development workflow.

Write stylesheets with Sass, automatically check your JavaScript for errors, optimize images, enable synchronized browser testing, and more.

## Features

* [Webpack](https://webpack.github.io/) is used as a build tool for compiling stylesheets, checking for JavaScript errors, optimizing images, and concatenating and minifying files
* [BrowserSync](http://www.browsersync.io/) for keeping multiple browsers and devices synchronized while testing, along with injecting updated CSS and JS into your browser while you're developing
* [Bootstrap](http://getbootstrap.com/)
* Template inheritance with the [theme wrapper](https://roots.io/sage/docs/theme-wrapper/)
* ARIA roles and microformats
* Posts use the [hNews](http://microformats.org/wiki/hnews) microformat
* [Multilingual ready](https://roots.io/wpml/) and over 30 available [community translations](https://github.com/roots/sage-translations)

Install the [Soil](https://roots.io/plugins/soil/) plugin to enable additional recommended features:

* Load jQuery from the jQuery CDN
* Cleaner WordPress markup
* Cleaner HTML output of navigation menus
* Root relative URLs
* Nice search
* Google Analytics snippet from [HTML5 Boilerplate](http://html5boilerplate.com/)
* Move all JS to the footer
* Disable trackbacks and pingbacks

See a complete working example in the [roots-example-project.com repo](https://github.com/roots/roots-example-project.com).

## Requirements

Make sure all dependencies have been installed before moving on:

* [PHP](http://php.net/manual/en/install.php) >= 5.5.x
* [Composer](https://getcomposer.org/download/)
* [Node.js](http://nodejs.org/) >= 0.12.x

## Theme installation

From the command line, run the following commands from the root of your WordPress site (where `composer.json` exists). These instructions assume you're using a [Bedrock](https://roots.io/bedrock/)-based WordPress setup. If you're using Vagrant, make sure to run these commands from the Vagrant box (`vagrant ssh`). Create a new theme based on Sage by using Composer's [`create-project`](https://getcomposer.org/doc/03-cli.md#create-project):

```shell
# @ example.com/site
$ composer create-project roots/sage web/app/themes/your-theme-name 9.0.0-alpha.1
```

Then activate the theme via [wp-cli](http://wp-cli.org/commands/theme/activate/):

```shell
# @ example.com/site
$ wp theme activate your-theme-name
```

## Theme structure

```shell
themes/theme-name/        # → Root of your Sage based theme
├── assets                # → Front-end assets
│   ├── config.json       # → Settings for compiled assets
│   ├── fonts/            # → Theme fonts
│   ├── images/           # → Theme images
│   ├── scripts/          # → Theme JS
│   └── styles/           # → Theme stylesheets
├── composer.json         # → Autoloading for `src/` files
├── composer.lock         # → Composer lock file (never manually edit)
├── dist/                 # → Built theme assets (never manually edit)
├── functions.php         # → Never manually edit
├── index.php             # → Never manually edit
├── node_modules/         # → Node.js packages (never manually edit)
├── package.json          # → Node.js dependencies and scripts
├── screenshot.png        # → Theme screenshot for WP admin
├── src/                  # → Theme PHP
├── style.css             # → Theme meta information
├── templates/            # → Theme templates
│   ├── layouts/          # → Base templates
│   └── partials/         # → Partial templates
├── vendor/               # → Composer packages (never manually edit)
├── watch.js              # → Webpack/BrowserSync watch config
└── webpack.config.js     # → Webpack config
```

## Theme setup

Edit `src/lib/setup.php` to enable or disable theme features, setup navigation menus, post thumbnail sizes, post formats, and sidebars.

## Theme development

Sage uses [Webpack](https://webpack.github.io/) as a build tool and [npm](https://www.npmjs.com/) to manage front-end packages.

### Install dependencies

From the command line on your host machine (not on your Vagrant development box), navigate to the theme directory then run `npm install`:

```shell
# @ example.com/site/web/app/themes/your-theme-name
$ npm install
```

You now have all the necessary dependencies to run the build process.

### Available build commands

* `npm run build` — Compile and optimize the files in your assets directory
* `npm run watch` — Compile assets when file changes are made, start BrowerSync session
* `npm run build:production` — Compile assets for production

### Using BrowserSync

To use BrowserSync during `npm watch` you need to update `devUrl` at the bottom of `assets/config.json` to reflect your local development hostname.

For example, if your local development URL is `https://project-name.dev` you would update the file to read:
```json
...
  "devUrl": "https://project-name.dev",
...
```

## Documentation

Sage documentation is available at [https://roots.io/sage/docs/](https://roots.io/sage/docs/).

## Contributing

Contributions are welcome from everyone. We have [contributing guidelines](https://github.com/roots/guidelines/blob/master/CONTRIBUTING.md) to help you get started.

## Community

Keep track of development and community news.

* Participate on the [Roots Discourse](https://discourse.roots.io/)
* Follow [@rootswp on Twitter](https://twitter.com/rootswp)
* Read and subscribe to the [Roots Blog](https://roots.io/blog/)
* Subscribe to the [Roots Newsletter](https://roots.io/subscribe/)
* Listen to the [Roots Radio podcast](https://roots.io/podcast/)
