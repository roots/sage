const mix = require('laravel-mix');

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

// Settings
mix.setPublicPath('./dist');

// Browsersync
mix.browserSync({
  proxy: 'https://example.test',
  files: [
    'app/**/*.php',
    'config/**/*.php',
    'resources/views/**/*.php',
    'dist/styles/**/*.css',
    'dist/scripts/**/*.js'
  ],
});

// Styles
mix.sass('resources/assets/styles/app.scss', 'styles');

// Javascript
mix.js('resources/assets/scripts/app.js', 'scripts')
   .js('resources/assets/scripts/customizer.js', 'scripts')
   .extract();

// Assets
mix.copyDirectory('resources/assets/images', 'dist/images')
   .copyDirectory('resources/assets/fonts', 'dist/fonts');

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
