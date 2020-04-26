# [Sage](https://roots.io/sage/)

[![Packagist](https://img.shields.io/packagist/vpre/roots/sage.svg?style=flat-square)](https://packagist.org/packages/roots/sage)
[![devDependency Status](https://img.shields.io/david/dev/roots/sage.svg?style=flat-square)](https://david-dm.org/roots/sage#info=devDependencies)
[![Build Status](https://img.shields.io/circleci/project/github/roots/sage/10.0.0-dev.svg?style=flat-square)](https://circleci.com/gh/roots/sage)
[![Follow Roots](https://img.shields.io/twitter/follow/rootswp.svg?style=flat-square&color=1da1f2)](https://twitter.com/rootswp)

Sage is a WordPress starter theme with a modern development workflow.

**Sage 10 is in active development and is currently in alpha. The `10.0.0-dev` branch tracks Sage 10 development. If you want a stable version, use the [latest Sage 9 release](https://github.com/roots/sage/releases/latest).**

## Features

* Sass for stylesheets
* Modern JavaScript
* [Laravel Mix](https://github.com/JeffreyWay/laravel-mix) for compiling assets and concatenating and minifying files
* [Browsersync](http://www.browsersync.io/) for synchronized browser testing
* [Blade](https://laravel.com/docs/5.8/blade) as a templating engine
* [Bootstrap 4](https://getbootstrap.com/) (optional)

See a working example at [roots-example-project.com](https://roots-example-project.com/).

## Requirements

Make sure all dependencies have been installed before moving on:

* [WordPress](https://wordpress.org/) >= 5.4
* [PHP](https://secure.php.net/manual/en/install.php) >= 7.2.0 (with [`php-mbstring`](https://secure.php.net/manual/en/book.mbstring.php) enabled)
* [Composer](https://getcomposer.org/download/)
* [Node.js](http://nodejs.org/) >= 8.0.0
* [Yarn](https://yarnpkg.com/en/docs/install)

## Theme installation

Install Sage using Composer from your WordPress themes directory (replace `your-theme-name` below with the name of your theme):

```sh
# @ app/themes/ or wp-content/themes/
$ composer create-project roots/sage your-theme-name
```

To install the latest development version of Sage, add `dev-10.0.0-dev` to the end of the command:

```shell
$ composer create-project roots/sage your-theme-name dev-10.0.0-dev
```

During theme installation you will have options to update `style.css` theme headers, select a CSS framework, and configure Browsersync.

## Theme structure

```sh
themes/your-theme-name/   # → Root of your Sage based theme
├── app/                  # → Theme PHP
│   ├── Composers/        # → View composers
│   ├── Providers/        # → Service providers
│   ├── admin.php         # → Theme customizer setup
│   ├── filters.php       # → Theme filters
│   ├── helpers.php       # → Helper functions
│   └── setup.php         # → Theme setup
├── config/               # → Config files
│   ├── app.php           # → Application configuration
│   ├── assets.php        # → Asset configuration
│   ├── filesystems.php   # → Filesystems configuration
│   └── view.php          # → View configuration
├── composer.json         # → Autoloading for `app/` files
├── composer.lock         # → Composer lock file (never edit)
├── dist/                 # → Built theme assets (never edit)
├── functions.php         # → Composer autoloader, Acorn bootloader
├── index.php             # → Never manually edit
├── node_modules/         # → Node.js packages (never edit)
├── package.json          # → Node.js dependencies and scripts
├── resources/            # → Theme assets and templates
│   ├── assets/           # → Front-end assets
│   │   ├── fonts/        # → Theme fonts
│   │   ├── images/       # → Theme images
│   │   ├── scripts/      # → Theme JS
│   │   └── styles/       # → Theme stylesheets
│   └── views/            # → Theme templates
│       ├── components/   # → Component templates
│       ├── layouts/      # → Base templates
│       └── partials/     # → Partial templates
├── screenshot.png        # → Theme screenshot for WP admin
├── storage/              # → Storage location for cache (never edit)
├── style.css             # → Theme meta information
├── vendor/               # → Composer packages (never edit)
└── webpack.mix.js        # → Laravel Mix configuration
```

## Theme setup

Edit `app/setup.php` to enable or disable theme features, setup navigation menus, post thumbnail sizes, and sidebars.

## Theme development

* Run `yarn` from the theme directory to install dependencies
* Update `webpack.mix.js` with your local dev URL

### Build commands

* `yarn start` — Compile assets when file changes are made, start Browsersync session
* `yarn build` — Compile and optimize the files in your assets directory
* `yarn build:production` — Compile assets for production

## Documentation

* [Sage documentation](https://roots.io/sage/docs/)

## Contributing

Contributions are welcome from everyone. We have [contributing guidelines](https://github.com/roots/guidelines/blob/master/CONTRIBUTING.md) to help you get started.

## Sage sponsors

Help support our open-source development efforts by [becoming a patron](https://www.patreon.com/rootsdev).

<a href="https://kinsta.com/?kaid=OFDHAJIXUDIV"><img src="https://cdn.roots.io/app/uploads/kinsta.svg" alt="Kinsta" width="200" height="150"></a> <a href="https://k-m.com/"><img src="https://cdn.roots.io/app/uploads/km-digital.svg" alt="KM Digital" width="200" height="150"></a> <a href="https://nestify.io/?utm_source=roots&utm_medium=banner&utm_campaign=footer"><img src="https://cdn.roots.io/app/uploads/nestify.svg" alt="Nestify" width="200" height="150"></a>

## Community

Keep track of development and community news.

* Participate on the [Roots Discourse](https://discourse.roots.io/)
* Follow [@rootswp on Twitter](https://twitter.com/rootswp)
* Read and subscribe to the [Roots Blog](https://roots.io/blog/)
* Subscribe to the [Roots Newsletter](https://roots.io/subscribe/)
* Listen to the [Roots Radio podcast](https://roots.io/podcast/)
