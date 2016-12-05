'use strict'; // eslint-disable-line

const webpackDevMiddleware = require('webpack-dev-middleware');
const webpackHotMiddleware = require('webpack-hot-middleware');
const browserSync = require('browser-sync');
const url = require('url');
const uniq = require('lodash/uniq');

const mergeWithConcat = require('./util/mergeWithConcat');
const RsyncWatcher = require('./rsync-watcher.js');

module.exports = class {
  constructor(options) {
    this.watcher = null;
    this.compiler = null;
    this.options = mergeWithConcat({
      proxyUrl: 'https://localhost:3000',
      watch: [],
      callback() {},
    }, options);
  }
  apply(compiler) {
    if (this.options.disable) {
      return;
    }
    this.compiler = compiler;
    compiler.plugin('done', () => {
      if (!this.watcher) {
        this.watcher = browserSync.create();
        compiler.plugin('compilation', () => this.watcher.notify('Rebuilding...'));
        this.start();
      }

      (new RsyncWatcher(this.watcher, this.options.rsync)).watch();
    });
  }
  start() {
    const watcherConfig = mergeWithConcat({
      host: url.parse(this.options.proxyUrl).hostname,
      port: url.parse(this.options.proxyUrl).port,
      proxy: {
        target: this.options.target,
        middleware: this.middleware(),
      },
      files: [],
    }, this.options.browserSyncOptions);
    watcherConfig.files = uniq(watcherConfig.files.concat(this.options.watch));
    this.watcher.init(watcherConfig, this.options.callback.bind(this));
  }
  middleware() {
    this.webpackDevMiddleware = webpackDevMiddleware(this.compiler, {
      publicPath: this.options.publicPath,
      stats: false,
      noInfo: true,
    });
    this.webpackHotMiddleware = webpackHotMiddleware(this.compiler, {
      log: this.watcher.notify.bind(this.watcher),
    });
    return [this.webpackDevMiddleware, this.webpackHotMiddleware];
  }
};
