"use strict"; // eslint-disable-line

const { default: ImageminPlugin } = require('imagemin-webpack-plugin');
const imageminMozjpeg = require('imagemin-mozjpeg');
const UglifyJsPlugin = require('uglifyjs-webpack-plugin');
const Glob = require('glob-all');
const PurgecssPlugin = require('purgecss-webpack-plugin');
const purgecssWordpress = require('purgecss-with-wordpress');

const config = require('./config');

module.exports = {
  plugins: [
    new ImageminPlugin({
      optipng: { optimizationLevel: 2 },
      gifsicle: { optimizationLevel: 3 },
      pngquant: { quality: '65-90', speed: 4 },
      svgo: {
        plugins: [
          { removeUnknownsAndDefaults: false },
          { cleanupIDs: false },
          { removeViewBox: false },
        ],
      },
      plugins: [imageminMozjpeg({ quality: 75 })],
      disable: config.enabled.watcher,
    }),
    new UglifyJsPlugin({
      uglifyOptions: {
        ecma: 5,
        warnings: true,
        compress: {
          drop_console: true,
        },
      },
    }),
    new PurgecssPlugin({
      paths: Glob.sync([
        'app/**/*.php',
        'resources/views/**/*.php',
        'resources/assets/scripts/**/*.js',
      ]),
      whitelist: [...purgecssWordpress.whitelist],
      whitelistPatterns: [...purgecssWordpress.whitelistPatterns],
    }),
  ],
};
