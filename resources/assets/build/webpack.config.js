'use strict'; // eslint-disable-line

const webpack = require('webpack');
const merge = require('webpack-merge');
const autoprefixer = require('autoprefixer');
const CleanPlugin = require('clean-webpack-plugin');
const ExtractTextPlugin = require('extract-text-webpack-plugin');
const StyleLintPlugin = require('stylelint-webpack-plugin');

const CopyGlobsPlugin = require('copy-globs-webpack-plugin');
const config = require('./config');

const assetsFilenames = (config.enabled.cacheBusting) ? config.cacheBusting : '[name]';
const sourceMapQueryStr = (config.enabled.sourceMaps) ? '+sourceMap' : '-sourceMap';

let webpackConfig = {
  context: config.paths.assets,
  entry: config.entry,
  devtool: (config.enabled.sourceMaps ? '#source-map' : undefined),
  output: {
    path: config.paths.dist,
    publicPath: config.publicPath,
    filename: `scripts/${assetsFilenames}.js`,
  },
  module: {
    rules: [
      {
        enforce: 'pre',
        test: /\.js?$/,
        include: config.paths.assets,
        use: 'eslint',
      },
      {
        test: /\.js$/,
        exclude: [/(node_modules|bower_components)(?![/|\\](bootstrap|foundation-sites))/],
        loader: 'buble',
        options: { objectAssign: 'Object.assign' },
      },
      {
        test: /\.css$/,
        include: config.paths.assets,
        use: ExtractTextPlugin.extract({
          fallback: 'style',
          publicPath: '../',
          use: [
            `css?${sourceMapQueryStr}`,
            'postcss',
          ],
        }),
      },
      {
        test: /\.scss$/,
        include: config.paths.assets,
        use: ExtractTextPlugin.extract({
          fallback: 'style',
          publicPath: '../',
          use: [
            `css?${sourceMapQueryStr}`,
            'postcss',
            `resolve-url?${sourceMapQueryStr}`,
            `sass?${sourceMapQueryStr}`,
          ],
        }),
      },
      {
        test: /\.(ttf|eot|png|jpe?g|gif|svg|ico)$/,
        include: config.paths.assets,
        loader: 'file',
        options: {
          name: `[path]${assetsFilenames}.[ext]`,
        },
      },
      {
        test: /\.woff2?$/,
        include: config.paths.assets,
        loader: 'url',
        options: {
          limit: 10000,
          mimetype: 'application/font-woff',
          name: `[path]${assetsFilenames}.[ext]`,
        },
      },
      {
        test: /\.(ttf|eot|woff2?|png|jpe?g|gif|svg)$/,
        include: /node_modules|bower_components/,
        loader: 'file',
        options: {
          name: `vendor/${config.cacheBusting}.[ext]`,
        },
      },
    ],
  },
  resolve: {
    modules: [
      config.paths.assets,
      'node_modules',
      'bower_components',
    ],
    enforceExtension: false,
  },
  resolveLoader: {
    moduleExtensions: ['-loader'],
  },
  externals: {
    jquery: 'jQuery',
  },
  plugins: [
    new CleanPlugin([config.paths.dist], {
      root: config.paths.root,
      verbose: false,
    }),
    /**
     * It would be nice to switch to copy-webpack-plugin, but
     * unfortunately it doesn't provide a reliable way of
     * tracking the before/after file names
     */
    new CopyGlobsPlugin({
      pattern: config.copy,
      output: `[path]${assetsFilenames}.[ext]`,
      manifest: config.manifest,
    }),
    new ExtractTextPlugin({
      filename: `styles/${assetsFilenames}.css`,
      allChunks: true,
      disable: (config.enabled.watcher),
    }),
    new webpack.ProvidePlugin({
      $: 'jquery',
      jQuery: 'jquery',
      'window.jQuery': 'jquery',
      Tether: 'tether',
      'window.Tether': 'tether',
    }),
    new webpack.LoaderOptionsPlugin({
      minimize: config.enabled.optimize,
      debug: config.enabled.watcher,
      stats: { colors: true },
    }),
    new webpack.LoaderOptionsPlugin({
      test: /\.s?css$/,
      options: {
        output: { path: config.paths.dist },
        context: config.paths.assets,
        postcss: [
          autoprefixer({ browsers: config.browsers }),
        ],
      },
    }),
    new webpack.LoaderOptionsPlugin({
      test: /\.js$/,
      options: {
        eslint: { failOnWarning: false, failOnError: true },
      },
    }),
    new StyleLintPlugin({
      failOnError: ! config.enabled.watcher,
      syntax: 'scss',
    }),
  ],
};

/* eslint-disable global-require */ /** Let's only load dependencies as needed */

if (config.enabled.optimize) {
  webpackConfig = merge(webpackConfig, require('./webpack.config.optimize'));
}

if (config.env.production) {
  webpackConfig.plugins.push(new webpack.NoEmitOnErrorsPlugin());
}

if (config.enabled.cacheBusting) {
  const WebpackAssetsManifest = require('webpack-assets-manifest');

  webpackConfig.plugins.push(
    new WebpackAssetsManifest({
      output: 'assets.json',
      space: 2,
      writeToDisk: false,
      assets: config.manifest,
      replacer: require('./util/assetManifestsFormatter'),
    })
  );
}

if (config.enabled.watcher) {
  webpackConfig.entry = require('./util/addHotMiddleware')(webpackConfig.entry);
  webpackConfig = merge(webpackConfig, require('./webpack.config.watch'));
}

module.exports = webpackConfig;
