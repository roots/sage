let mix = require('laravel-mix');

// mix.webpackConfig({
//     devtool: "inline-source-map"
// });

mix.sass(
    'resources/styles/app.scss',
    'public/styles'
).options({
    processCssUrls: false,
    cssNano: { minifyFontValues: false },
    }).js('resources/scripts/app.js', 'public/scripts');
