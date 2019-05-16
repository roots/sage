const mix = require('laravel-mix');
const project = require('../../../sage.config.js')

// Public path helper
const publicPath = path => `${mix.config.publicPath}/${path}`;

// Source path helper
const src = path => `${mix.config.resourceRoot}/${path}`;

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Sage application. By default, we are compiling the Sass file
 | for your application, as well as bundling up your JS files.
 |
 */

// Public Path
mix.setPublicPath('dist')
   .setResourceRoot(project.entry.root)

// Browsersync
mix.browserSync({
  proxy: project.browsersync.proxy,
  files: project.browsersync.files,
});

// Styles
project.entry.styles.forEach(style => {
  mix.sass(src(style), 'styles');
});

// JavaScript
project.entry.scripts.forEach(script => {
  mix.js(src(script), 'scripts').extract();
});

// Assets
project.entry.dirs.forEach(dir => {
  mix.copyDirectory(src(dir), publicPath(dir));
});

// Autoload
project.autoload.jQuery && mix.autoload({
  jquery: ['$', 'window.jQuery'],
})

// Options
mix.options({
  processCssUrls: false,
});

// Source maps when not in production.
if (!mix.inProduction()) {
  mix.sourceMaps();
}

// Hash and version files in production.
if (mix.inProduction()) {
  mix.version();
}
