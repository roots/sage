const webpack = require('webpack');
const BrowserSyncPlugin = require('./webpack.plugin.browsersync');
const mergeWithConcat = require('./util/mergeWithConcat');

const config = require('./config');

module.exports = {
  output: { pathinfo: true },
  debug: true,
  devtool: '#cheap-module-source-map',
  plugins: [
    new webpack.optimize.OccurrenceOrderPlugin(),
    new webpack.HotModuleReplacementPlugin(),
    new webpack.NoErrorsPlugin(),
    new BrowserSyncPlugin({
      target: config.devUrl,
      publicPath: config.publicPath,
      proxyUrl: config.proxyUrl,
      browserSyncOptions: mergeWithConcat({
        files: config.watch,
      }, config.browserSyncOptions),
    }),
  ],
};
