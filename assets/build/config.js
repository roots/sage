/* eslint-disable import/no-extraneous-dependencies */

const path = require('path');
const argv = require('minimist')(process.argv.slice(2));
const glob = require('glob-all');
const merge = require('lodash/merge');

const mergeWithConcat = require('./util/mergeWithConcat');
const userConfig = require('../config');

const isProduction = !!((argv.env && argv.env.production) || argv.p);

const config = mergeWithConcat({
  entry: {
    main: [path.join(__dirname, 'public-path.js')],
  },
  copy: ['images/**/*'],
  proxyUrl: 'http://localhost:3000',
  cacheBusting: '[name]_[hash]',
  paths: {
    assets: path.resolve('assets'),
    dist: path.resolve('dist'),
  },
  enabled: {
    sourceMaps: !isProduction,
    minify: isProduction,
    cacheBusting: isProduction,
    watcher: !!argv.watch,
    uglifyJs: !(argv.p || argv.optimizeMinimize),
  },
  watch: [
    'templates/**/*.php',
    'src/**/*.php',
  ],
}, userConfig);

module.exports = mergeWithConcat(config, {
  env: merge({ production: isProduction, development: !isProduction }, argv.env),
  entry: {
    get files() {
      return glob.sync(config.copy, {
        cwd: config.paths.assets,
        mark: true,
      }).filter(file => !((file.slice(-1) === '/') || (!file.indexOf('*') === -1)))
        .map(file => path.join(config.paths.assets, file));
    },
  },
});
