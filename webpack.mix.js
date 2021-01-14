const mix = require('laravel-mix');
require('@tinypixelco/laravel-mix-wp-blocks');

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

mix
  .setPublicPath('./public')
  .browserSync('sage.test');

mix
  .sass('resources/css/app.scss', 'css')
  .sass('resources/css/editor.scss', 'css')
  .options({
    processCssUrls: false,
    postCss: [require('tailwindcss')('./tailwind.config.js')],
  });

mix
  .js('resources/js/app.js', 'js')
  .js('resources/js/customizer.js', 'js')
  .blocks('resources/js/editor.js', 'js');

mix
  .copyDirectory('resources/images/**', 'public/images')
  .copyDirectory('resources/fonts/**', 'public/fonts');

mix
  .autoload({ jquery: ['$', 'window.jQuery'] })
  .extract()
  .sourceMaps()
  .version();
