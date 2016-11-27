const webpack = require('webpack');
const BrowserSyncPlugin = require('./webpack.plugin.browsersync');

const config = require('./config');

module.exports = {
  output: { pathinfo: true },
  devtool: '#cheap-module-source-map',
  stats: false,
  plugins: [
    new webpack.optimize.OccurrenceOrderPlugin(),
    new webpack.HotModuleReplacementPlugin(),
    new webpack.NoErrorsPlugin(),
    new BrowserSyncPlugin({
      target: config.devUrl,
      publicPath: config.publicPath,
      proxyUrl: config.proxyUrl,
      watch: config.watch,
      rsync: config.rsync,
      browserSyncOptions: {
        reloadDebounce: 800,
        open: false,
      },
    }),
  ],
};
