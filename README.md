# [Sage](https://roots.io/sage/)

Sage is a WordPress starter theme based on HTML5 Boilerplate, gulp, Bower, and Bootstrap Sass, that will help you make better themes.

**Note: This is a modified version of sage based on [v8.5.0](https://github.com/roots/sage/tree/8.5.0). The main difference is that the frontend uses [browserify](http://browserify.org/) for JavaScript, which is written using the latest ES6 spec (parsed using [babel](https://babeljs.io/)) and [yarn](https://yarnpkg.com/) for dependency management. I hope to document these changes more fully, but then again I might not.**

* Source: [https://github.com/andyinabox/sage](https://github.com/andyinabox/sage)
* Homepage: [https://github.com/andyinabox/sage](https://github.com/andyinabox/sage)
* Documentation: [https://roots.io/sage/docs/](https://roots.io/sage/docs/)
* Twitter: [@rootswp](https://twitter.com/rootswp)
* Newsletter: [Subscribe](http://roots.io/subscribe/)
* Forum: [https://discourse.roots.io/](https://discourse.roots.io/)

## Requirements

| Prerequisite    | How to check | How to install
| --------------- | ------------ | ------------- |
| PHP >= 5.4.x    | `php -v`     | [php.net](http://php.net/manual/en/install.php) |
| Node.js >= 4.5  | `node -v`    | [nodejs.org](http://nodejs.org/) |
| gulp >= 3.8.10  | `gulp -v`    | `npm install -g gulp` |
| Bower >= 1.3.12 | `bower -v`   | `npm install -g bower` |

For more installation notes, refer to the [Install gulp and Bower](#install-gulp-and-bower) section in this document.

## Features

* [gulp](http://gulpjs.com/) build script that compiles both Sass and Less, checks for JavaScript errors, optimizes images, and concatenates and minifies files
* [BrowserSync](http://www.browsersync.io/) for keeping multiple browsers and devices synchronized while testing, along with injecting updated CSS and JS into your browser while you're developing
* [yarn](https://yarnpkg.com/) for dependancy management
* [browserify](http://browserify.org/) for module management
* [babel](https://babeljs.io/) to parse ES6
* [Theme wrapper](https://roots.io/sage/docs/theme-wrapper/)
* ARIA roles and microformats
* Posts use the [hNews](http://microformats.org/wiki/hnews) microformat
* [Multilingual ready](https://roots.io/wpml/) and over 30 available [community translations](https://github.com/roots/sage-translations)

Install the [Soil](https://github.com/roots/soil) plugin to enable additional features:

* Cleaner output of `wp_head` and enqueued assets
* Cleaner HTML output of navigation menus
* Root relative URLs
* Nice search (`/search/query/`)
* Google CDN jQuery snippet from [HTML5 Boilerplate](http://html5boilerplate.com/)
* Google Analytics snippet from [HTML5 Boilerplate](http://html5boilerplate.com/)

See a complete working example in the [roots-example-project.com repo](https://github.com/roots/roots-example-project.com).

## Theme installation

Install Sage by copying the project into a new folder within your WordPress themes directory.

Make sure [Composer](https://getcomposer.org/download/) has been installed before moving on.

Install Sage using Composer from your WordPress themes directory (replace `your-theme-name` below with the name of your theme):

```shell
# @ example.com/site/web/app/themes/
$ composer create-project roots/sage your-theme-name 8.5.0
```

## Theme setup

Edit `lib/setup.php` to enable or disable theme features, setup navigation menus, post thumbnail sizes, post formats, and sidebars.

## Theme development

Sage uses [gulp](http://gulpjs.com/) as its build system and [Bower](http://bower.io/) to manage front-end packages.

### Install gulp and Bower

Building the theme requires [node.js](http://nodejs.org/download/). We recommend you update to the latest version of npm: `npm install -g npm@latest`.

From the command line:

1. Install [gulp](http://gulpjs.com) and [Bower](http://bower.io/) globally with `npm install -g gulp bower`
2. Navigate to the theme directory, then run `npm install`
3. Run `bower install`

You now have all the necessary dependencies to run the build process.

### Available gulp commands

* `gulp` — Compile and optimize the files in your assets directory
* `gulp watch` — Compile assets when file changes are made
* `gulp --production` — Compile assets for production (no source maps).

### Using BrowserSync

To use BrowserSync during `gulp watch` you need to update `devUrl` at the bottom of `assets/manifest.json` to reflect your local development hostname.

For example, if your local development URL is `http://project-name.dev` you would update the file to read:
```json
...
  "config": {
    "devUrl": "http://project-name.dev"
  }
...
```
If your local development URL looks like `http://localhost:8888/project-name/` you would update the file to read:
```json
...
  "config": {
    "devUrl": "http://localhost:8888/project-name/"
  }
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
