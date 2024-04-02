# Wordpress Starter Theme with Sage and Vite

## Overview

WordPress starter theme with block editor support and Vite builder.

## Requirements

- [Acorn](https://roots.io/acorn/docs/installation/) v3
- [PHP](https://secure.php.net/manual/en/install.php) >= 8.0 (with [`php-mbstring`](https://secure.php.net/manual/en/book.mbstring.php) enabled)
- [Composer](https://getcomposer.org/download/)
- [Vite](https://vitejs.dev) >= 3.1.0
- [Node.js](http://nodejs.org/) >= 18.0.0
- [Yarn](https://yarnpkg.com/en/docs/install)

## Theme installation

Install Sage using Composer from your WordPress themes directory (replace `theme-name` below with the name of
your theme):

```shell
# /wp-content/themes/
$ composer create-project onepixnet/sage-vite theme-name
```

To install the latest development version of Sage, add `dev-main` to the end of the command:

```shell
# /wp-content/themes/
$ composer create-project onepixnet/sage-vite theme-name dev-main
```

Then jump to your `theme-name` and install [Acorn](https://roots.io/acorn/docs/installation/)

```shell
# /wp-content/themes/theme-name
$ composer require roots/acorn
```

Then install dependencies and compile assets

```shell
$ yarn
$ yarn build
```

You're ready to go!

To start dev server update proxy url in `bud.config.js:37` (setProxyUrl) and run

```shell
$ yarn dev
```

### Full list of commands

```shell
# /wp-content/themes/
$ composer create-project onepixnet/sage-vite theme-name dev-main

# /wp-content/themes/theme-name
$ composer require roots/acorn
$ yarn
$ yarn build
$ yarn dev
```

## Theme structure

```sh
themes/your-theme-name/   # → Root of your Sage based theme
├── app/                  # → Theme PHP
│   ├── Providers/        # → Service providers
│   ├── View/             # → View models
│   ├── filters.php       # → Theme filters
│   ├── helpers.php       # → Global helpers
│   ├── medias.php        # → Medias helper
│   └── setup.php         # → Theme setup
├── composer.json         # → Autoloading for `app/` files
├── public/               # → Built theme assets (never edit)
├── functions.php         # → Theme bootloader
├── index.php             # → Theme template wrapper
├── node_modules/         # → Node.js packages (never edit)
├── package.json          # → Node.js dependencies and scripts
├── resources/            # → Theme assets and templates
│   ├── fonts/            # → Theme fonts
│   ├── images/           # → Theme images
│   ├── scripts/          # → Theme javascript
│   ├── styles/           # → Theme stylesheets
│   ├── svg/              # → Theme svgs
│   └── views/            # → Theme templates
│       ├── components/   # → Component templates
│       ├── forms/        # → Form templates
│       ├── layouts/      # → Base templates
│       ├── partials/     # → Partial templates
        └── sections/     # → Section templates
├── screenshot.png        # → Theme screenshot for WP admin
├── style.css             # → Theme meta information
├── vendor/               # → Composer packages (never edit)
└── vite.config.ts        # → Vite configuration
```

## Theme development

- Run `yarn` from the theme directory to install dependencies
- Update `vite.config.ts` for bundler fine tuning

### Build commands

- `yarn dev` — Start dev server and hot module replacement
- `yarn build` — Compile assets
- `yarn lint` — Lint stylesheets & javascripts
- `yarn lint:css` — Lint stylesheets
- `yarn lint:js` — Lint javascripts

### Hot Module Replacement

#### Project Side

Add the following variables in your project `.env`

```sh
# Endpoint where the bundler serve your assets
HMR_ENTRYPOINT=http://localhost:5173
```

#### Theme side

For advanced dev server configuration, copy `.env.example` according
to [Vite naming convention and loading order](https://vitejs.dev/guide/env-and-mode.html#env-files) and update variables

#### FYI

Running HMR Mode remove the `public/manifest.json` file, so do not forget to re-run the assets compilation
with `yarn build` if needed.

## Documentation

- [Sage documentation](https://roots.io/sage/docs/)
- [Controller documentation](https://github.com/soberwp/controller#usage)
- [Vite](https://vitejs.dev/guide/)
