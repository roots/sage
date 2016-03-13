/* eslint no-console: 0 */

var webpack = require('webpack'),
    webpackDevMiddleware = require('webpack-dev-middleware'),
    webpackHotMiddleware = require('webpack-hot-middleware'),
    browserSync = require('browser-sync');

var devBuildConfig = require('./webpack.config'),
    compiler = webpack(devBuildConfig);

browserSync.init({
  proxy: {
    target: 'http://example.dev', // change to dev server
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
