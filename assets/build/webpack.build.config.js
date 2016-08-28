/* eslint-disable import/no-extraneous-dependencies */
const webpack = require('webpack');
const path = require('path');
const qs = require('qs');
const autoprefixer = require('autoprefixer');
const Clean = require('clean-webpack-plugin');
const ExtractTextPlugin = require('extract-text-webpack-plugin');

const webpackProduction = require('./webpack.production.config');
const config = require('./config');

const publicPath = `${config.publicPath}/${path.basename(config.paths.dist)}/`;
const assetsFilenames = (config.enabled.cacheBusting) ? config.cacheBusting : '[name]';
const sourceMapQueryStr = (config.enabled.sourceMaps) ? '-sourceMap' : '+sourceMap';

const jsLoader = {
  test: /\.js$/,
  exclude: [/(node_modules|bower_components)(?![/|\\](bootstrap|foundation-sites))/],
  loaders: [`babel?presets[]=${path.resolve('./node_modules/babel-preset-es2015')}&cacheDirectory`],
};

if (config.enabled.watch) {
  jsLoader.loaders.unshift('monkey-hot');
}

const webpackConfig = {
  context: config.paths.assets,
  entry: config.entry,
  output: {
    path: config.paths.dist,
    publicPath,
    filename: `scripts/${assetsFilenames}.js`,
  },
  module: {
    preLoaders: [
      {
        test: /\.js?$/,
        include: config.paths.assets,
        loader: 'eslint',
      },
    ],
    loaders: [
      jsLoader,
      {
        test: /\.css$/,
        include: config.paths.assets,
        loader: ExtractTextPlugin.extract({
          fallbackLoader: 'style',
          loader: [
            `css?${sourceMapQueryStr}`,
            'postcss',
          ],
        }),
      },
      {
        test: /\.scss$/,
        include: config.paths.assets,
        loader: ExtractTextPlugin.extract({
          fallbackLoader: 'style',
          loader: [
            `css?${sourceMapQueryStr}`,
            'postcss',
            `resolve-url?${sourceMapQueryStr}`,
            `sass?${sourceMapQueryStr}`,
          ],
        }),
      },
      {
        test: /\.(png|jpg|jpeg|gif|svg)$/,
        include: config.paths.assets,
        loaders: [
          `file?${qs.stringify({
            name: '[path][name].[ext]',
          })}`,
          `image-webpack?${JSON.stringify({
            bypassOnDebug: true,
            progressive: true,
            optimizationLevel: 7,
            interlaced: true,
            pngquant: {
              quality: '65-90',
              speed: 4,
            },
            svgo: {
              removeUnknownsAndDefaults: false,
              cleanupIDs: false,
            },
          })}`,
        ],
      },
      {
        test: /\.(ttf|eot)$/,
        include: config.paths.assets,
        loader: `file?${qs.stringify({
          name: `[path]${assetsFilenames}.[ext]`,
        })}`,
      },
      {
        test: /\.woff(2)?$/,
        include: config.paths.assets,
        loader: `url?${qs.stringify({
          limit: 10000,
          mimetype: 'application/font-woff',
          name: `[path]${assetsFilenames}.[ext]`,
        })}`,
      },
      // Use file-loader for node_modules/ assets
      {
        test: /\.(ttf|eot|woff(2)?|png|jpg|jpeg|gif|svg)$/,
        include: /node_modules|bower_components/,
        loader: 'file',
        query: {
          name: `vendor/${assetsFilenames}.[ext]`,
        },
      },
    ],
  },
  modules: [
    config.paths.assets,
    'node_modules',
    'bower_components',
  ],
  enforceExtensions: false,
  externals: {
    jquery: 'jQuery',
  },
  plugins: [
    new Clean([config.paths.dist], process.cwd()),
    new ExtractTextPlugin({
      filename: `styles/${assetsFilenames}.css`,
      allChunks: true,
      disable: (config.enabled.watch),
    }),
    new webpack.ProvidePlugin({
      $: 'jquery',
      jQuery: 'jquery',
      'window.jQuery': 'jquery',
      Tether: 'tether',
      'window.Tether': 'tether',
    }),
    new webpack.DefinePlugin({
      WEBPACK_PUBLIC_PATH: (config.enabled.watch)
        ? JSON.stringify(publicPath)
        : false,
    }),
  ],
  postcss: [
    autoprefixer({
      browsers: [
        'last 2 versions',
        'android 4',
        'opera 12',
      ],
    }),
  ],
  eslint: {
    failOnWarning: false,
    failOnError: true,
  },
  stats: {
    colors: true,
  },
};

module.exports = config.env.production ? webpackProduction(webpackConfig) : webpackConfig;
