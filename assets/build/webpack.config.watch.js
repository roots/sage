const webpack = require('webpack');
const BrowserSyncPlugin = require('./webpack.plugin.browsersync');

const config = require('./config');

module.exports = {
  output: { pathinfo: true },
  debug: true,
  devtool: '#cheap-module-source-map',
  plugins: [
    new webpack.optimize.OccurrenceOrderPlugin(),
    new webpack.HotModuleReplacementPlugin(),
    new webpack.NoErrorsPlugin(),
    new webpack.DefinePlugin({
      WEBPACK_PUBLIC_PATH: JSON.stringify(config.publicPath),
    }),
    new BrowserSyncPlugin({
      target: config.devUrl,
      publicPath: config.publicPath,
      proxyUrl: config.proxyUrl,
      browserSyncOptions: { files: config.watch },
    }),
  ],
};
