/* eslint-disable import/no-extraneous-dependencies */
const webpack = require('webpack');
const webpackDevMiddleware = require('webpack-dev-middleware');
const webpackHotMiddleware = require('webpack-hot-middleware');
const browserSync = require('browser-sync');
const qs = require('qs');

const webpackConfig = require('./webpack.build.config');
const config = require('../config');
const mergeWithConcat = require('./util/mergeWithConcat');

/**
 * Loop through webpack entry
 * and add the hot middleware
 * @param  {Object} entry webpack entry
 * @return {Object}       entry with hot middleware
 */
const addHotMiddleware = (entry) => {
  const results = {};
  const hotMiddlewareScript = `webpack-hot-middleware/client?${qs.stringify({
    timeout: 20000,
    reload: true,
  })}`;

  entry.forEach(name => {
    if (entry[name] instanceof Array) {
      results[name] = entry[name].slice(0);
    } else {
      results[name] = [entry[name]];
    }
    results[name].push(hotMiddlewareScript);
  });

  return results;
};

const compiler = webpack(mergeWithConcat(webpackConfig, {
  entry: addHotMiddleware(webpackConfig.entry),
  output: { pathinfo: true },
  debug: true,
  devTool: '#cheap-module-source-map',
  plugins: [
    new webpack.HotModuleReplacementPlugin(),
    new webpack.NoErrorsPlugin(),
  ],
}));

browserSync.init({
  proxy: {
    target: config.devUrl,
    middleware: [
      webpackDevMiddleware(compiler, {
        publicPath: webpackConfig.publicPath,
        stats: {
          colors: true,
        },
      }),
      webpackHotMiddleware(compiler, {
        log: browserSync.notify,
      }),
    ],
  },
  files: config.watch,
});
