const webpack = require('webpack');
const path = require('path');
const qs = require('qs');
const autoprefixer = require('autoprefixer');
const CleanPlugin = require('clean-webpack-plugin');
const ExtractTextPlugin = require('extract-text-webpack-plugin');
const ImageminPlugin = require('imagemin-webpack-plugin').default;
const imageminMozjpeg = require('imagemin-mozjpeg');

const mergeWithConcat = require('./util/mergeWithConcat');
const addHotMiddleware = require('./util/addHotMiddleware');
const webpackConfigProduction = require('./webpack.config.production');
const webpackConfigWatch = require('./webpack.config.watch');
const config = require('./config');

const assetsFilenames = (config.enabled.cacheBusting) ? config.cacheBusting : '[name]';
const sourceMapQueryStr = (config.enabled.sourceMaps) ? '+sourceMap' : '-sourceMap';

const jsLoader = {
  test: /\.js$/,
  exclude: [/(node_modules|bower_components)(?![/|\\](bootstrap|foundation-sites))/],
  loaders: [{
    loader: 'babel',
    query: {
      presets: [[path.resolve('./node_modules/babel-preset-es2015'), { modules: false }]],
      cacheDirectory: true,
    },
  }],
};

if (config.enabled.watcher) {
  jsLoader.loaders.unshift('monkey-hot?sourceType=module');
}

const webpackConfig = {
  context: config.paths.assets,
  entry: config.entry,
  devtool: (config.enabled.sourceMaps ? '#source-map' : undefined),
  output: {
    path: config.paths.dist,
    publicPath: config.publicPath,
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
        test: /\.(png|jpe?g|gif|svg)$/,
        include: config.paths.assets,
        loaders: [
          `file?${qs.stringify({
            name: '[path][name].[ext]',
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
        test: /\.woff2?$/,
        include: config.paths.assets,
        loader: `url?${qs.stringify({
          limit: 10000,
          mimetype: 'application/font-woff',
          name: `[path]${assetsFilenames}.[ext]`,
        })}`,
      },
      {
        test: /\.(ttf|eot|woff2?|png|jpe?g|gif|svg)$/,
        include: /node_modules|bower_components/,
        loader: 'file',
        query: {
          name: `vendor/${config.cacheBusting}.[ext]`,
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
    new CleanPlugin([config.paths.dist], config.paths.root),
    new ImageminPlugin({
      optipng: {
        optimizationLevel: 7,
      },
      gifsicle: {
        optimizationLevel: 3,
      },
      pngquant: {
        quality: '65-90',
        speed: 4,
      },
      svgo: {
        removeUnknownsAndDefaults: false,
        cleanupIDs: false,
      },
      jpegtran: null,
      plugins: [imageminMozjpeg({
        quality: 75,
      })],
      disable: (config.enabled.watcher),
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
    new webpack.DefinePlugin({
      WEBPACK_PUBLIC_PATH: (config.enabled.watcher)
        ? JSON.stringify(config.publicPath)
        : false,
    }),
    new webpack.LoaderOptionsPlugin({
      minimize: config.enabled.minify,
      debug: config.enabled.watcher,
      stats: { colors: true },
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
    }),
  ],
};

module.exports = webpackConfig;

if (config.env.production) {
  module.exports = mergeWithConcat(webpackConfig, webpackConfigProduction);
}

if (config.enabled.watcher) {
  module.exports = mergeWithConcat(webpackConfig, webpackConfigWatch, {
    entry: addHotMiddleware(webpackConfig.entry),
  });
}

if (config.enabled.uglifyJs) {
  module.exports.plugins.push(
    new webpack.optimize.UglifyJsPlugin({
      sourceMap: config.enabled.sourceMaps,
    })
  );
}
