const url = require('url');
const webpack = require('webpack');
const BrowserSyncPlugin = require('browser-sync-webpack-plugin')

const config = require('./config');

const target = process.env.DEVURL || config.devUrl;
const proxyUrlObj = url.parse(config.proxyUrl);

module.exports = {
  output: {
    pathinfo: true,
    publicPath: config.proxyUrl + config.publicPath,
  },
  devtool: 'cheap-module-source-map',
  stats: false,
  plugins: [
    new BrowserSyncPlugin({
      host:  proxyUrlObj.hostname,
      port:  proxyUrlObj.port,

      proxy: target,
      https: (url.parse(target).protocol === 'https:'),

      files: [
        '**/*.php',
        '**/*.css',
        {
           match: '**/*.js',
           options:{
              ignored: 'dist/**/*.js',
           },
        },
     ],

      watch: config.watch,
      open:  config.open,
      reloadDelay: 500,
    },{
      reload: false,
      injectChanges: false,
    }),

    new webpack.HotModuleReplacementPlugin(),
  ],
};
