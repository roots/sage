'use strict'; // eslint-disable-line

const { default: ImageminPlugin } = require('imagemin-webpack-plugin');
const imageminMozjpeg = require('imagemin-mozjpeg');

const config = require('./config');

module.exports = {
  plugins: [
    new ImageminPlugin({
      optipng: { optimizationLevel: 7 },
      gifsicle: { optimizationLevel: 3 },
      pngquant: { quality: '65-90', speed: 4 },
      svgo: {
        plugins: [
          { removeUnknownsAndDefaults: false },
          { cleanupIDs: false },
          { removeViewBox: false }
        ],
      },
      plugins: [imageminMozjpeg({ quality: 75 })],
      disable: (config.enabled.watcher),
    }),
  ],
};
