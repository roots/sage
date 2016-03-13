var webpack = require('webpack'),
    path = require('path'),
    autoprefixer = require('autoprefixer'),
    Clean = require("clean-webpack-plugin"),
    AssetsPlugin = require('assets-webpack-plugin'),
    ExtractTextPlugin = require('extract-text-webpack-plugin'),
    OptimizeCssAssetsPlugin = require('optimize-css-assets-webpack-plugin'),
    cssnano = require('cssnano');

var SAGE_ENV = process.env.SAGE_ENV || 'development',
    webpackConfig;

var sage = {
  publicPath: '/app/themes/sage/dist/',
  dist: path.join(__dirname, 'dist'),
  manifest: 'assets.json',
  // set to true to extract css in dev mode (prevents "hot" update)
  extractStyles: false
};

// format output for Sage : { "name.ext": "hash.ext" }
var assetsPluginProcessOutput = function (assets) {
  var results = {},
      name,
      ext;

  for (name in assets) {
    if (assets.hasOwnProperty(name)) {
      for (ext in assets[name]) {
        if (assets[name].hasOwnProperty(ext)) {
          results[name + '.' + ext] = assets[name][ext];
        }
      }
    }
  }
  return JSON.stringify(results);
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
    path: sage.dist,
    publicPath: sage.publicPath
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
        loader: 'url?limit=10000'
      },
      {
        test: /\.woff(2)?(\?v=[0-9]\.[0-9]\.[0-9])?$/,
        loader: 'url?limit=10000&mimetype=application/font-woff'
      },
      {
        test: /\.(png|jpg|jpeg|gif)(\?v=[0-9]\.[0-9]\.[0-9])?$/,
        loader: 'file-loader'
      }
    ],
  },
  resolve: { extensions: [ '', '.js', '.json' ] },
  externals: {
    jquery: 'jQuery'
  },
  plugins: [
    new Clean([sage.dist]),
    new webpack.ProvidePlugin({
      $: 'jquery',
      jQuery: 'jquery',
      'window.jQuery': 'jquery',
      'window.Tether': 'tether'
    }),
    new AssetsPlugin({
      path: sage.dist,
      filename: sage.manifest,
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

if ( SAGE_ENV === 'development' ) {
  // development
  webpackConfig.entry.main.push('webpack-hot-middleware/client?reload=true');
  webpackConfig.entry.customizer.push('webpack-hot-middleware/client?reload=true');
  webpackConfig.output.filename = '[name].js';
  webpackConfig.output.sourceMapFilename = '[file].map';
  webpackConfig.output.pathinfo = true;
  webpackConfig.debug = true;
  webpackConfig.devtool = '#cheap-module-source-map';
  webpackConfig.plugins.push(new webpack.optimize.OccurenceOrderPlugin());
  webpackConfig.plugins.push(new webpack.HotModuleReplacementPlugin());
  webpackConfig.plugins.push(new webpack.NoErrorsPlugin());
  webpackConfig.plugins.push(new ExtractTextPlugin('[name].css', { disable: !sage.extractStyles }));
} else {
  // production
  webpackConfig.output.filename = '[name].[hash].js';
  webpackConfig.output.sourceMapFilename = '[file].[hash].map';
  webpackConfig.plugins.push(new ExtractTextPlugin('[name].[hash].css'));
  webpackConfig.plugins.push(new webpack.optimize.UglifyJsPlugin());
  webpackConfig.plugins.push(new OptimizeCssAssetsPlugin({
    cssProcessor: cssnano,
    cssProcessorOptions: { discardComments: { removeAll: true } },
    canPrint: true
  }));
}

module.exports = webpackConfig;
