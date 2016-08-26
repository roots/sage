// External dependencies
var webpack = require('webpack'),
    path = require('path'),
    argv = require('minimist')(process.argv.slice(2)),
    qs = require('qs'),
    autoprefixer = require('autoprefixer'),
    Clean = require("clean-webpack-plugin"),
    AssetsPlugin = require('assets-webpack-plugin'),
    ExtractTextPlugin = require('extract-text-webpack-plugin'),
    OptimizeCssAssetsPlugin = require('optimize-css-assets-webpack-plugin'),
    cssnano = require('cssnano');

// Internal dependencies
var config = require('./assets/config');

// Internal variables
var scriptsFilename = (argv.release) ? 'scripts/[name]_[hash].js' : 'scripts/[name].js',
    stylesFilename = (argv.release) ? 'styles/[name]_[hash].css' : 'styles/[name].css',
    sourceMapQueryStr = (argv.release) ? '-sourceMap' : '+sourceMap',
    assets = 'assets/',
    dist = 'dist/',
    jsLoader,
    webpackConfig;

jsLoader = {
  test: /\.js$/,
  exclude: [ /(node_modules|bower_components)(?![/|\\](bootstrap|foundation-sites))/ ],
  loaders: [ 'babel?presets[]='+path.resolve('./node_modules/babel-preset-es2015')+'&cacheDirectory' ]
};

if (argv.watch) { // '--watch' to add monkey-hot
  jsLoader.loaders.unshift('monkey-hot');
}

/**
 * Process AssetsPlugin output and format it
 * for Sage: {"[name].[ext]":"[name]_[hash].[ext]"}
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
};

/**
 * Loop through webpack entry
 * and add the hot middleware
 * @param  {Object} entry webpack entry
 * @return {Object}       entry with hot middleware
 */
var addHotMiddleware = function (entry) {
  var name,
      results = {},
      hotMiddlewareScript = 'webpack-hot-middleware/client?' + qs.stringify({
        timeout: 20000,
        reload: true
      });

  for (name in entry) {
    if (entry.hasOwnProperty(name)) {
      if (entry[name] instanceof Array !== true) {
        results[name] = [entry[name]];
      } else {
        results[name] = entry[name].slice(0);
      }
      results[name].push(hotMiddlewareScript);
    }
  }
  return results;
};

webpackConfig = {
  context: path.resolve(assets),
  entry: config.entry,
  output: {
    path: path.join(__dirname, dist),
    publicPath: path.join(config.publicPath, dist),
    filename: scriptsFilename
  },
  module: {
    preLoaders: [
      {
        test: /\.js?$/,
        include: path.resolve(assets),
        loader: 'eslint'
      }
    ],
    loaders: [
      jsLoader,
      {
        test: /\.css$/,
        include: path.resolve(assets),
        loader: ExtractTextPlugin.extract('style', [
          'css?' + sourceMapQueryStr,
          'postcss'
        ])
      },
      {
        test: /\.scss$/,
        include: path.resolve(assets),
        loader: ExtractTextPlugin.extract('style', [
          'css?' + sourceMapQueryStr,
          'postcss',
          'resolve-url?' + sourceMapQueryStr,
          'sass?' + sourceMapQueryStr
        ])
      },
      {
        test: /\.(png|jpg|jpeg|gif|svg)(\?.*)?$/,
        include: path.resolve(assets),
        loaders: [
          'file?' + qs.stringify({
            name: '[path][name].[ext]'
          }),
          'image-webpack?' + JSON.stringify({
            bypassOnDebug: true,
            progressive: true,
            optimizationLevel: 7,
            interlaced: true,
            pngquant: {
              quality: "65-90",
              speed: 4
            },
            svgo: {
              removeUnknownsAndDefaults: false,
              cleanupIDs: false
            }
          })
        ]
      },
      {
        test: /\.(ttf|eot)(\?.*)?$/,
        include: path.resolve(assets),
        loader: 'file?' + qs.stringify({
          name: '[path][name]_[md5:hash:hex:8].[ext]'
        })
      },
      {
        test: /\.woff(2)?(\?.*)?$/,
        include: path.resolve(assets),
        loader: 'url?' + qs.stringify({
          limit: 10000,
          mimetype: "application/font-woff",
          name: "[path][name]_[md5:hash:hex:8].[ext]"
        })
      },
      // Use file-loader for node_modules/ assets
      {
        test: /\.(ttf|eot|woff(2)?|png|jpg|jpeg|gif|svg)(\?.*)?$/,
        include: /node_modules/,
        loader: 'file?' + qs.stringify({
          name: 'vendor/[name]_[md5:hash:hex:8].[ext]'
        })
      }
    ]
  },
  resolve: {
    extensions: [ '', '.js', '.json' ],
    modulesDirectories: [
      'node_modules',
      'bower_components'
    ]
  },
  externals: {
    jquery: 'jQuery'
  },
  plugins: [
    new Clean([dist]),
    new ExtractTextPlugin(stylesFilename, {
      allChunks: true,
      disable: (argv.watch === true) // '--watch' disable ExtractTextPlugin
    }),
    new webpack.ProvidePlugin({
      $: 'jquery',
      jQuery: 'jquery',
      'window.jQuery': 'jquery',
      'Tether': 'tether',
      'window.Tether': 'tether'
    }),
    new webpack.DefinePlugin({
      WEBPACK_PUBLIC_PATH: (argv.watch === true) ? JSON.stringify(config.output.publicPath) : false
    })
  ],
  postcss: [
    autoprefixer({
      browsers: [
        'last 2 versions',
        'android 4',
        'opera 12'
      ]
    })
  ],
  eslint: {
    failOnWarning: false,
    failOnError: true
  },
  stats: {
    colors: true
  }
};

// '--watch' to push additional plugins to webpackConfig
if (argv.watch) {
  webpackConfig.entry = addHotMiddleware(webpackConfig.entry);
  webpackConfig.output.pathinfo = true;
  webpackConfig.debug = true;
  webpackConfig.devtool = '#cheap-module-source-map';
  webpackConfig.plugins.push(new webpack.optimize.OccurenceOrderPlugin());
  webpackConfig.plugins.push(new webpack.HotModuleReplacementPlugin());
  webpackConfig.plugins.push(new webpack.NoErrorsPlugin());
}

// '--release' to push additional plugins to webpackConfig
if (argv.release) {
  webpackConfig.plugins.push(new AssetsPlugin({
    path: path.join(__dirname, dist),
    filename: 'assets.json',
    fullPath: false,
    processOutput: assetsPluginProcessOutput
  }));
  webpackConfig.plugins.push(new webpack.optimize.UglifyJsPlugin({
    compress: {
      'drop_debugger': true
    }
  }));
  webpackConfig.plugins.push(new OptimizeCssAssetsPlugin({
    cssProcessor: cssnano,
    cssProcessorOptions: { discardComments: { removeAll: true } },
    canPrint: true
  }));
}

module.exports = webpackConfig;
