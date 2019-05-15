const mix = require('laravel-mix');
const project = require('../../../sage.config.js')

// Public path helper
const publicPath = path => `${mix.config.publicPath}/${path}`;

// Source path helper
const src = path => `${project.entry.root}/${path}`;

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
mix.setPublicPath('dist');

// Browsersync
mix.browserSync({
  proxy: project.browsersync.proxy,
  files: project.browsersync.files,
});

// Styles
for(let style of project.entry.styles) {
  mix.sass(src(style), 'styles');
}

// JavaScript
for(let script of project.entry.scripts) {
  console.log([src(script), 'script'])
  mix.js(src(script), 'scripts')
     .extract();
}

// Assets
for(let dir of project.entry.dirs) {
  mix.copyDirectory(src(dir), publicPath(dir));
}

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
