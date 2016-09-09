const AssetsPlugin = require('assets-webpack-plugin');
const OptimizeCssAssetsPlugin = require('optimize-css-assets-webpack-plugin');
const cssnano = require('cssnano');
const path = require('path');

const config = require('./config');

/**
 * Process AssetsPlugin output and format it
 * for Sage: {"[name].[ext]":"[name]_[hash].[ext]"}
 * @param  {Object} assets passed by processOutput
 * @return {String}        JSON
 */
const assetsPluginProcessOutput = (assets) => {
  const results = {};
  Object.keys(assets).forEach(name => {
    Object.keys(assets[name]).forEach(ext => {
      const filename = `${path.dirname(assets[name][ext])}/${path.basename(`${name}.${ext}`)}`;
      results[filename] = assets[name][ext];
    });
  });
  return JSON.stringify(results);
};

module.exports = {
  plugins: [
    new AssetsPlugin({
      path: config.paths.dist,
      filename: 'assets.json',
      fullPath: false,
      processOutput: assetsPluginProcessOutput,
    }),
    new OptimizeCssAssetsPlugin({
      cssProcessor: cssnano,
      cssProcessorOptions: { discardComments: { removeAll: true } },
      canPrint: true,
    }),
  ],
};
