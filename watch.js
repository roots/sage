// External dependencies
var webpack = require('webpack'),
    webpackDevMiddleware = require('webpack-dev-middleware'),
    webpackHotMiddleware = require('webpack-hot-middleware'),
    browserSync = require('browser-sync');

// Internal dependencies
var webpackConfig = require('./webpack.config'),
    config = require('./assets/config');

// Internal variables
var compiler = webpack(webpackConfig);

browserSync.init({
  proxy: {
    target: config.devUrl,
    middleware: [
      webpackDevMiddleware(compiler, {
        publicPath: webpackConfig.output.publicPath,
        stats: {
          colors: true
        }
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
