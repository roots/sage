const webpack = require('webpack');
const BrowserSyncPlugin = require('browsersync-webpack-plugin');

const config = require('./config');

module.exports = {
  output: {
    pathinfo: true,
    publicPath: config.proxyUrl + config.publicPath,
  },
  devtool: '#cheap-module-source-map',
  stats: false,
  plugins: [
    new webpack.optimize.OccurrenceOrderPlugin(),
    new webpack.HotModuleReplacementPlugin(),
    new webpack.NoEmitOnErrorsPlugin(),
    new BrowserSyncPlugin({
      target: config.devUrl,
      publicPath: config.publicPath,
      proxyUrl: config.proxyUrl,
      watch: config.watch,
    }),
  ],
};
