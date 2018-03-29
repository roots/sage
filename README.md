# [Sage](https://roots.io/sage/)
[![Packagist](https://img.shields.io/packagist/vpre/roots/sage.svg?style=flat-square)](https://packagist.org/packages/roots/sage)
[![devDependency Status](https://img.shields.io/david/dev/roots/sage.svg?style=flat-square)](https://david-dm.org/roots/sage#info=devDependencies)
[![Build Status](https://img.shields.io/travis/roots/sage.svg?style=flat-square)](https://travis-ci.org/roots/sage)
[![Backers on Open Collective](https://opencollective.com/sage/backers/badge.svg)](#backers) 
[![Sponsors on Open Collective](https://opencollective.com/sage/sponsors/badge.svg)](#sponsors) 

Sage is a WordPress starter theme with a modern development workflow.

## Features

* Sass for stylesheets
* Modern JavaScript
* [Webpack](https://webpack.github.io/) for compiling assets, optimizing images, and concatenating and minifying files
* [Browsersync](http://www.browsersync.io/) for synchronized browser testing
* [Blade](https://laravel.com/docs/5.5/blade) as a templating engine
* [Controller](https://github.com/soberwp/controller) for passing data to Blade templates
* CSS framework (optional): [Bootstrap 4](https://getbootstrap.com/), [Bulma](https://bulma.io/), [Foundation](https://foundation.zurb.com/), [Tachyons](http://tachyons.io/)
* Font Awesome (optional)

See a working example at [roots-example-project.com](https://roots-example-project.com/).

## Requirements

Make sure all dependencies have been installed before moving on:

* [WordPress](https://wordpress.org/) >= 4.7
* [PHP](https://secure.php.net/manual/en/install.php) >= 7.1.3 (with [`php-mbstring`](https://secure.php.net/manual/en/book.mbstring.php) enabled)
* [Composer](https://getcomposer.org/download/)
* [Node.js](http://nodejs.org/) >= 6.9.x
* [Yarn](https://yarnpkg.com/en/docs/install)

## Theme installation

Install Sage using Composer from your WordPress themes directory (replace `your-theme-name` below with the name of your theme):

```shell
# @ app/themes/ or wp-content/themes/
$ composer create-project roots/sage your-theme-name dev-master
```

During theme installation you will have options to update `style.css` theme headers, select a CSS framework, add Font Awesome, and configure Browsersync.

## Theme structure

```shell
themes/your-theme-name/   # → Root of your Sage based theme
├── app/                  # → Theme PHP
│   ├── controllers/      # → Controller files
│   ├── admin.php         # → Theme customizer setup
│   ├── filters.php       # → Theme filters
│   ├── helpers.php       # → Helper functions
│   └── setup.php         # → Theme setup
├── composer.json         # → Autoloading for `app/` files
├── composer.lock         # → Composer lock file (never edit)
├── dist/                 # → Built theme assets (never edit)
├── node_modules/         # → Node.js packages (never edit)
├── package.json          # → Node.js dependencies and scripts
├── resources/            # → Theme assets and templates
│   ├── assets/           # → Front-end assets
│   │   ├── config.json   # → Settings for compiled assets
│   │   ├── build/        # → Webpack and ESLint config
│   │   ├── fonts/        # → Theme fonts
│   │   ├── images/       # → Theme images
│   │   ├── scripts/      # → Theme JS
│   │   └── styles/       # → Theme stylesheets
│   ├── functions.php     # → Composer autoloader, theme includes
│   ├── index.php         # → Never manually edit
│   ├── screenshot.png    # → Theme screenshot for WP admin
│   ├── style.css         # → Theme meta information
│   └── views/            # → Theme templates
│       ├── layouts/      # → Base templates
│       └── partials/     # → Partial templates
└── vendor/               # → Composer packages (never edit)
```

## Theme setup

Edit `app/setup.php` to enable or disable theme features, setup navigation menus, post thumbnail sizes, and sidebars.

## Theme development

* Run `yarn` from the theme directory to install dependencies
* Update `resources/assets/config.json` settings:
  * `devUrl` should reflect your local development hostname
  * `publicPath` should reflect your WordPress folder structure (`/wp-content/themes/sage` for non-[Bedrock](https://roots.io/bedrock/) installs)

### Build commands

* `yarn run start` — Compile assets when file changes are made, start Browsersync session
* `yarn run build` — Compile and optimize the files in your assets directory
* `yarn run build:production` — Compile assets for production

## Documentation

* [Sage documentation](https://roots.io/sage/docs/)
* [Controller documentation](https://github.com/soberwp/controller#usage)

## Contributing

Contributions are welcome from everyone. We have [contributing guidelines](https://github.com/roots/guidelines/blob/master/CONTRIBUTING.md) to help you get started.

## Gold sponsors

Help support our open-source development efforts by [contributing to Sage on OpenCollective](https://opencollective.com/sage).

<a href="https://kinsta.com/?kaid=OFDHAJIXUDIV"><img src="https://roots.io/app/uploads/kinsta.svg" alt="Kinsta" width="200" height="150"></a> <a href="https://k-m.com/"><img src="https://roots.io/app/uploads/km-digital.svg" alt="KM Digital" width="200" height="150"></a>

## Community

Keep track of development and community news.

* Participate on the [Roots Discourse](https://discourse.roots.io/)
* Follow [@rootswp on Twitter](https://twitter.com/rootswp)
* Read and subscribe to the [Roots Blog](https://roots.io/blog/)
* Subscribe to the [Roots Newsletter](https://roots.io/subscribe/)
* Listen to the [Roots Radio podcast](https://roots.io/podcast/)

## Contributors

This project exists thanks to all the people who contribute. [[Contribute](https://github.com/roots/guidelines/blob/master/CONTRIBUTING.md)].
<a href="graphs/contributors"><img src="https://opencollective.com/sage/contributors.svg?width=890&button=false" /></a>


## Backers

Thank you to all our backers! 🙏 [[Become a backer](https://opencollective.com/sage#backer)]

<a href="https://opencollective.com/sage#backers" target="_blank"><img src="https://opencollective.com/sage/backers.svg?width=890"></a>


## Sponsors

Support this project by becoming a sponsor. Your logo will show up here with a link to your website. [[Become a sponsor](https://opencollective.com/sage#sponsor)]

<a href="https://opencollective.com/sage/sponsor/0/website" target="_blank"><img src="https://opencollective.com/sage/sponsor/0/avatar.svg"></a>
<a href="https://opencollective.com/sage/sponsor/1/website" target="_blank"><img src="https://opencollective.com/sage/sponsor/1/avatar.svg"></a>
<a href="https://opencollective.com/sage/sponsor/2/website" target="_blank"><img src="https://opencollective.com/sage/sponsor/2/avatar.svg"></a>
<a href="https://opencollective.com/sage/sponsor/3/website" target="_blank"><img src="https://opencollective.com/sage/sponsor/3/avatar.svg"></a>
<a href="https://opencollective.com/sage/sponsor/4/website" target="_blank"><img src="https://opencollective.com/sage/sponsor/4/avatar.svg"></a>
<a href="https://opencollective.com/sage/sponsor/5/website" target="_blank"><img src="https://opencollective.com/sage/sponsor/5/avatar.svg"></a>
<a href="https://opencollective.com/sage/sponsor/6/website" target="_blank"><img src="https://opencollective.com/sage/sponsor/6/avatar.svg"></a>
<a href="https://opencollective.com/sage/sponsor/7/website" target="_blank"><img src="https://opencollective.com/sage/sponsor/7/avatar.svg"></a>
<a href="https://opencollective.com/sage/sponsor/8/website" target="_blank"><img src="https://opencollective.com/sage/sponsor/8/avatar.svg"></a>
<a href="https://opencollective.com/sage/sponsor/9/website" target="_blank"><img src="https://opencollective.com/sage/sponsor/9/avatar.svg"></a>


