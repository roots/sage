const BrowserSyncPlugin = require('browser-sync-webpack-plugin');
const url = require('url');

const config = require('./config');

module.exports = {
  output: { pathinfo: true },
  debug: true,
  devTool: 'cheap-module-source-map',
  plugins: [
    new BrowserSyncPlugin({
      host: url.parse(config.proxyUrl).hostname,
      port: url.parse(config.proxyUrl).port,
      proxy: config.devUrl,
      files: [
        'templates/**/*.php',
        'src/**/*.php',
      ],
    }),
  ],
};
