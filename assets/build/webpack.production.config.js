/* eslint-disable import/no-extraneous-dependencies */
const AssetsPlugin = require('assets-webpack-plugin');
const OptimizeCssAssetsPlugin = require('optimize-css-assets-webpack-plugin');
const cssnano = require('cssnano');
const webpack = require('webpack');
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

module.exports = (webpackConfig) => {
  webpackConfig.plugins.push(new AssetsPlugin({
    path: config.paths.dist,
    filename: 'assets.json',
    fullPath: false,
    processOutput: assetsPluginProcessOutput,
  }));
  webpackConfig.plugins.push(new webpack.optimize.UglifyJsPlugin());
  webpackConfig.plugins.push(new OptimizeCssAssetsPlugin({
    cssProcessor: cssnano,
    cssProcessorOptions: { discardComments: { removeAll: true } },
    canPrint: true,
  }));
  return webpackConfig;
};
