'use strict'; // eslint-disable-line

const WebpackAssetsManifest = require('webpack-assets-manifest');
const OptimizeCssAssetsPlugin = require('optimize-css-assets-webpack-plugin');
const cssnano = require('cssnano');
const path = require('path');

const config = require('./config');

module.exports = {
  plugins: [
    new WebpackAssetsManifest({
      output: 'assets.json',
      space: 2,
      writeToDisk: false,
      assets: config.manifest,
      replacer(key, value) {
        if (typeof value === 'string') {
          return value;
        }
        const manifest = value;
        /**
         * Hack to prepend scripts/ or styles/ to manifest keys
         *
         * This might need to be reworked at some point.
         *
         * Before:
         *   {
         *     "main.js": "scripts/main_abcdef.js"
         *     "main.css": "styles/main_abcdef.css"
         *   }
         * After:
         *   {
         *     "scripts/main.js": "scripts/main_abcdef.js"
         *     "styles/main.css": "styles/main_abcdef.css"
         *   }
         */
        Object.keys(manifest).forEach((src) => {
          const sourcePath = path.basename(path.dirname(src));
          const targetPath = path.basename(path.dirname(manifest[src]));
          if (sourcePath === targetPath) {
            return;
          }
          manifest[`${targetPath}/${src}`] = manifest[src];
          delete manifest[src];
        });
        return manifest;
      },
    }),
    new OptimizeCssAssetsPlugin({
      cssProcessor: cssnano,
      cssProcessorOptions: { discardComments: { removeAll: true } },
      canPrint: true,
    }),
  ],
};
