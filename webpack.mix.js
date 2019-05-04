const mix = require('laravel-mix');

// Public path helper
const public = path => `${mix.config.publicPath}/${path}`;

// Source path helper
const src = path => `resources/assets/${path}`;

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
mix.setPublicPath('./storage/theme/assets');

// Browsersync
mix.browserSync({
  proxy: 'https://example.test',
  files: [
    'app/**/*.php',
    'config/**/*.php',
    'resources/views/**/*.php',
    public('styles/**/*.css'),
    public('scripts/**/*.js'),
  ],
});

// Styles
mix.sass(src('styles/app.scss'), 'styles');

// JavaScript
mix.js(src('scripts/app.js'), 'scripts')
   .js(src('scripts/customizer.js'), 'scripts')
   .extract();

// Assets
mix.copyDirectory(src('images'), public('images'))
   .copyDirectory(src('fonts'), public('fonts'));

// Autoload
mix.autoload({
  jquery: ['$', 'window.jQuery'],
});

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
