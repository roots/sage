const url = require('url');
const browserSync = require('browser-sync');
const webpack = require('webpack');
const merge = require('webpack-merge');
const middleware = require('webpack-dev-middleware');
const hmrMiddleware = require('webpack-hot-middleware');

const desire = require('./util/desire');
const WriteFilePlugin = desire('write-file-webpack-plugin');

const config = require('./config');
const webpackConfig = require('./webpack.config');
const addHmrClient = require('./util/addHotMiddleware');

const target = process.env.DEVURL || config.devUrl;

/**
 * Enable injection over self-signed SSL certification
 */
const https = url.parse(target).protocol === 'https:';
if (https) {
  process.env.NODE_TLS_REJECT_UNAUTHORIZED = 0;
}

const publicPath = config.proxyUrl + config.publicPath;

/**
 * webpack.config.js overrides
 */
const watchConfig = merge(webpackConfig, {
  stats: false,
  output: { publicPath, pathinfo: true },
  plugins: [new webpack.HotModuleReplacementPlugin()],
});

if (!WriteFilePlugin) {
  console.warn('Add the module write-file-webpack-plugin if you need files in your dist folder to be written to disk.');
} else {
  watchConfig.plugins.push(new WriteFilePlugin());
}

/**
 * Ensure HMR client is present in all entries
 */
watchConfig.entry = addHmrClient(watchConfig.entry);

/**
 * webpack instance
 */
const compiler = webpack(watchConfig);

/**
 * browserSync instance
 */
const app = browserSync.create();

/**
 * start browserSync server
 */
app.init({
  open: config.open,
  host: url.parse(config.proxyUrl).hostname,
  port: url.parse(config.proxyUrl).port,
  files: config.copy,
  refresh: true,
  proxy: { target },
  /**
   * add html injection
   */
  plugins: [
    {
      module: 'bs-html-injector',
      options: { files: config.patterns.html },
    },
  ],
  /**
   * add hmr middlewares
   */
  middleware: [
    middleware(compiler, { publicPath, logLevel: 'silent' }),
    hmrMiddleware(compiler, { log: app.notify.bind(app) }),
  ],
  /**
   * inject browsersync client after page load
   * also inject on error pages by looking for <pre> tag
   */
  snippetOptions: {
    rule: {
      match: /(<\/body>|<\/pre>)/i,
      fn: function(snippet, match) {
        return snippet + match;
      },
    },
  },
});
