/* eslint no-console: 0 */

var webpack = require('webpack'),
    webpackDevMiddleware = require('webpack-dev-middleware'),
    webpackHotMiddleware = require('webpack-hot-middleware'),
    browserSync = require('browser-sync');

var devBuildConfig = require('./webpack.config'),
    config = require('./config'),
    compiler = webpack(devBuildConfig);

browserSync.init({
  proxy: {
    target: config.devUrl,
    middleware: [
      webpackDevMiddleware(compiler, {
        publicPath: devBuildConfig.output.publicPath,
        stats: {
          colors: true
        },
      }),
      webpackHotMiddleware(compiler, {
        log: browserSync.notify
      })
    ]
  },
  files: [
    'templates/**/*.php',
    'src/**/*.php'
  ]
});
