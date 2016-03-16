var webpack = require('webpack'),
    path = require('path'),
    autoprefixer = require('autoprefixer'),
    Clean = require("clean-webpack-plugin"),
    AssetsPlugin = require('assets-webpack-plugin'),
    ExtractTextPlugin = require('extract-text-webpack-plugin'),
    OptimizeCssAssetsPlugin = require('optimize-css-assets-webpack-plugin'),
    cssnano = require('cssnano');

var config = require('./config'),
    webpackConfig;

/**
 * Process AssetsPlugin output
 * and format for Sage: {"[name].[ext]":"[hash].[ext]"}
 * @param  {Object} assets passed by processOutput
 * @return {String}        JSON
 */
var assetsPluginProcessOutput = function (assets) {
  var name,
      ext,
      filename,
      results = {};

  for (name in assets) {
    if (assets.hasOwnProperty(name)) {
      for (ext in assets[name]) {
        if (assets[name].hasOwnProperty(ext)) {
          filename = name + '.' + ext;
          results[filename] = path.basename(assets[name][ext]);
        }
      }
    }
  }
  return JSON.stringify(results);
}

/**
 * Loop through webpack entry
 * and add the hot middleware
 * @param  {Object} entry webpack entry
 * @return {Object}       entry with hot middleware
 */
var addHotMiddleware = function (entry) {
  var name,
      results = {},
      hotMiddlewareScript = 'webpack-hot-middleware/client?reload=true';

  for (name in entry) {
    if (entry.hasOwnProperty(name)) {
      if (entry[name] instanceof Array !== true) {
        results[name] = [entry[name]];
      } else {
        results[name] = entry[name].slice(0);
      };
      results[name].push(hotMiddlewareScript);
    }
  }
  return results;
}

webpackConfig = {
  entry: {
    main: [
      './assets/scripts/main'
    ],
    customizer: [
      './assets/scripts/customizer'
    ]
  },
  output: {
    path: path.join(__dirname, config.output.path),
    publicPath: config.output.publicPath,
  },
  module: {
    preLoaders: [
      {
        test: /\.jsx?$/,
        exclude: /node_modules/,
        loader: 'eslint'
      }
    ],
    loaders: [
      {
        test: /\.js$/,
        exclude: /node_modules/,
        loaders: ['monkey-hot', 'babel']
      },
      {
        test: /\.css$/,
        exclude: /node_modules/,
        loader: ExtractTextPlugin.extract('style', 'css?sourceMap!postcss')
      },
      {
        test: /\.scss$/,
        exclude: /node_modules/,
        loader: ExtractTextPlugin.extract('style', 'css?sourceMap!postcss!sass?sourceMap')
      },
      {
        test: /\.(ttf|eot|svg)$/,
        loader: 'url?limit=10000&name=fonts/[name].[ext]'
      },
      {
        test: /\.woff(2)?(\?v=[0-9]\.[0-9]\.[0-9])?$/,
        loader: 'url?limit=10000&mimetype=application/font-woff&name=fonts/[name].[ext]'
      },
      {
        test: /\.(png|jpg|jpeg|gif)(\?v=[0-9]\.[0-9]\.[0-9])?$/,
        loader: 'file-loader?name=images/[name].[ext]'
      }
    ],
  },
  resolve: { extensions: [ '', '.js', '.json' ] },
  externals: {
    jquery: 'jQuery'
  },
  plugins: [
    new Clean([config.output.path]),
    new webpack.ProvidePlugin({
      $: 'jquery',
      jQuery: 'jquery',
      'window.jQuery': 'jquery',
      'window.Tether': 'tether'
    }),
    new AssetsPlugin({
      path: config.output.path,
      filename: 'assets.json',
      fullPath: false,
      processOutput: assetsPluginProcessOutput,
    })
  ],
  postcss: [ autoprefixer ],
  eslint: {
    failOnWarning: false,
    failOnError: true,
  }
};

if (process.argv.lastIndexOf('-d') !== -1 ||
    process.env.SCRIPT === 'watch') {
  // development
  webpackConfig.output.filename = 'scripts/[name].js';
  webpackConfig.plugins.push(new webpack.optimize.OccurenceOrderPlugin());
  webpackConfig.plugins.push(new webpack.HotModuleReplacementPlugin());
  webpackConfig.plugins.push(new webpack.NoErrorsPlugin());
  webpackConfig.plugins.push(new ExtractTextPlugin('styles/[name].css', {
    // disable if webpack is called from the node.js api or set to false in config file
    disable: (process.env.SCRIPT === 'watch' || config.options.extractStyles === false)
  }));
} else {
  // default or production
  webpackConfig.output.filename = 'scripts/[name].[hash].js';
  webpackConfig.output.sourceMapFilename = '[file].[hash].map';
  webpackConfig.plugins.push(new ExtractTextPlugin('styles/[name].[hash].css'));
  webpackConfig.plugins.push(new webpack.optimize.UglifyJsPlugin());
  webpackConfig.plugins.push(new OptimizeCssAssetsPlugin({
    cssProcessor: cssnano,
    cssProcessorOptions: { discardComments: { removeAll: true } },
    canPrint: true
  }));
}
if (process.env.SCRIPT === 'watch') {
  // development settings when called from the node.js api by the watch script
  webpackConfig.entry = addHotMiddleware(webpackConfig.entry);
  webpackConfig.output.pathinfo = true;
  webpackConfig.debug = true;
  webpackConfig.devtool = 'source-map';
}

module.exports = webpackConfig;
