const mix = require('./resources/assets/build/mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for your application, as well as bundling up your JS files.
 |
 */

mix
  .js('resources/assets/scripts/customizer.js', 'scripts')
  .js('resources/assets/scripts/main.js', 'scripts')
  .sass('resources/assets/styles/main.scss', 'styles');
