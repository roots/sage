'use strict'; // eslint-disable-line

const path = require('path');
const webpack = require('webpack');
const { merge, mergeWithCustomize, customizeArray } = require('webpack-merge');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const StyleLintPlugin = require('stylelint-webpack-plugin');
const CopyPlugin = require('copy-webpack-plugin');
const ESLintPlugin = require('eslint-webpack-plugin');

const desire = require('./util/desire');
const config = require('./config');

const assetsFilenames = (config.enabled.cacheBusting) ? config.cacheBusting : '[name]';

let webpackConfig = {
  context: config.paths.assets,
  entry: config.entry,
  devtool: (config.enabled.sourceMaps ? 'source-map' : undefined),
  output: {
    path: config.paths.dist,
    publicPath: config.publicPath,
    filename: `scripts/${assetsFilenames}.js`,
  },
  stats: {
    hash: false,
    version: false,
    timings: false,
    children: false,
    errors: true,
    errorDetails: true,
    warnings: true,
    chunks: false,
    modules: false,
    reasons: false,
    source: false,
    publicPath: false,
  },
  performance: {
    maxEntrypointSize: 512000,
    maxAssetSize: 	   512000,
  },
  module: {
    rules: [
      {
        enforce: 'pre',
        test: /\.(js|s?[ca]ss)$/,
        include: config.paths.assets,
        loader: 'import-glob',
      },
      {
        test: /\.js$/,
        exclude: [/node_modules(?![/|\\](bootstrap|foundation-sites))/],
        use: [
          { loader: 'buble-loader', options: { objectAssign: 'Object.assign' } },
        ],
      },
      {
        test: /\.css$/,
        include: config.paths.assets,
        use: [
          MiniCssExtractPlugin.loader,
          { loader: 'css-loader', options: { sourceMap: config.enabled.sourceMaps } },
          {
            loader: 'postcss-loader', options: {
              postcssOptions: {
                config: path.join( __dirname, 'postcss.config.js' ),
                ctx: config,
              },
                sourceMap: config.enabled.sourceMaps,
            },
          },
        ],
      },
      {
        test: /\.scss$/,
        use: [
          MiniCssExtractPlugin.loader,
          { loader: 'css-loader', options: { sourceMap: config.enabled.sourceMaps } },
          {
            loader: 'postcss-loader', options: {
              postcssOptions: {
                config: path.join( __dirname, 'postcss.config.js' ),
                ctx: config,
              },
              sourceMap: config.enabled.sourceMaps,
            },
          },
          { loader: 'resolve-url-loader', options: { sourceMap: config.enabled.sourceMaps } },
          {
            loader: 'sass-loader', options: {
              sassOptions: {
                sourceComments: true,
              },
              sourceMap: true, //config.enabled.sourceMaps, // false causes a resolve issue
            },
          },
        ],
      },
      {
        test: /\.(ttf|otf|eot|woff2?|png|jpe?g|gif|svg|ico)$/,
        include: config.paths.assets,
        loader: 'url-loader',
        options: {
          limit: 4096,
          name: `[path]${assetsFilenames}.[ext]`,
        },
      },
      {
        test: /\.(ttf|otf|eot|woff2?|png|jpe?g|gif|svg|ico)$/,
        include: /node_modules/,
        loader: 'url-loader',
        options: {
          limit: 4096,
          outputPath: 'vendor/',
          name: `[path]${assetsFilenames}.[ext]`,
        },
      },
    ],
  },
  resolve: {
    modules: [
      config.paths.assets,
      'node_modules',
    ],
    enforceExtension: false,
  },
  externals: {
    jquery: 'jQuery',
  },
  plugins: [
    new ESLintPlugin({
      failOnWarning: false,
      failOnError:   true,
    }),
    new CleanWebpackPlugin({
      cleanOnceBeforeBuildPatterns: [config.paths.dist],
      verbose: false,
    }),
    new CopyPlugin({
      patterns: [
        {
          from: config.copy,
		  noErrorOnMissing: true,
          to: `[path]${assetsFilenames}[ext]`, // Note: since 8.0.0 no dot in placeholder needed
        },
      ],
    }),
    new webpack.ProvidePlugin({
      $: 'jquery',
      jQuery: 'jquery',
      'window.jQuery': 'jquery',
      Popper: 'popper.js/dist/umd/popper.js',
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
      },
    }),
	new MiniCssExtractPlugin({
      filename: `styles/${assetsFilenames}.css`,
    }),
    new StyleLintPlugin({
      failOnError: !config.enabled.watcher,
      syntax: 'scss',
    }),
  ],
};


/* eslint-disable global-require */ /** Let's only load dependencies as needed */

if (config.enabled.optimize) {
  webpackConfig = merge(webpackConfig, require('./webpack.config.optimize'));
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
      contextRelativeKeys: true,
    })
  );
}

if (config.enabled.watcher) {
  webpackConfig.entry = require('./util/addHotMiddleware')(webpackConfig.entry);
  webpackConfig = merge(webpackConfig, require('./webpack.config.watch'));
}

/**
 * During installation via sage-installer (i.e. composer create-project) some
 * presets may generate a preset specific config (webpack.config.preset.js) to
 * override some of the default options set here. We use webpack-merge to merge
 * them in. If you need to modify Sage's default webpack config, we recommend
 * that you modify this file directly, instead of creating your own preset
 * file, as there are limitations to using webpack-merge which can hinder your
 * ability to change certain options.
 */

module.exports = mergeWithCustomize({
  customizeArray: customizeArray({
    'module.rules': 'replace',
  }),
})(webpackConfig, desire(`${__dirname}/webpack.config.preset`) ? desire(`${__dirname}/webpack.config.preset`) : {} )
