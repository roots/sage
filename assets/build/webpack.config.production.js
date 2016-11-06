const AssetsPlugin = require('assets-webpack-plugin');
const OptimizeCssAssetsPlugin = require('optimize-css-assets-webpack-plugin');
const cssnano = require('cssnano');

const processOutput = require('./util/assetsPluginProcessOutput');
const config = require('./config');

module.exports = {
  plugins: [
    new AssetsPlugin({
      path: config.paths.dist,
      filename: 'assets.json',
      fullPath: false,
      processOutput(assets) {
        return JSON.stringify(Object.assign(processOutput(assets), config.manifest));
      },
    }),
    new OptimizeCssAssetsPlugin({
      cssProcessor: cssnano,
      cssProcessorOptions: { discardComments: { removeAll: true } },
      canPrint: true,
    }),
  ],
};
