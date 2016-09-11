'use strict'; // eslint-disable-line strict

const webpackDevMiddleware = require('webpack-dev-middleware');
const webpackHotMiddleware = require('webpack-hot-middleware');
const browserSync = require('browser-sync');
const url = require('url');

const mergeWithConcat = require('./util/mergeWithConcat');

module.exports = class {
  constructor(options) {
    this.watcher = null;
    this.compiler = null;
    this.options = mergeWithConcat({
      proxyUrl: 'https://localhost:3000',
      callback() {},
    }, options);
  }
  apply(compiler) {
    if (this.options.disable) {
      return;
    }
    this.compiler = compiler;
    compiler.plugin('done', this.doneCompiling);
  }
  doneCompiling() {
    if (!this.watcher) {
      this.watcher = browserSync.create();
      this.compiler.plugin('compilation', () => this.watcher.notify('Rebuilding...'));
      this.start();
    }
    // Optionally add logic for this.watcher.reload()
  }
  start() {
    const watcherConfig = mergeWithConcat({
      host: url.parse(this.options.proxyUrl).hostname,
      port: url.parse(this.options.proxyUrl).port,
      proxy: {
        target: this.options.target,
        middleware: this.middleware(),
      },
    }, this.options.browserSyncOptions);
    this.watcher.init(watcherConfig, this.options.callback.bind(this));
  }
  middleware() {
    this.webpackDevMiddleware = webpackDevMiddleware(this.compiler, {
      publicPath: this.options.publicPath,
      stats: { colors: true },
    });
    this.webpackHotMiddleware = webpackHotMiddleware(this.compiler, {
      log: this.watcher.notify.bind(this.watcher),
    });
    return [this.webpackDevMiddleware, this.webpackHotMiddleware];
  }
};
