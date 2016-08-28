/* eslint-disable import/no-extraneous-dependencies */

const path = require('path');
const argv = require('minimist')(process.argv.slice(2));
const userConfig = require('../config');
const merge = require('lodash/merge');
const mergeWithConcat = require('./util/mergeWithConcat');

const isProduction = !!(argv.env && argv.env.production);

module.exports = mergeWithConcat({
  entry: {
    main: [path.join(__dirname, 'public-path.js')],
  },
  paths: {
    assets: path.resolve('assets'),
    dist: path.resolve('dist'),
  },
  env: merge({ production: isProduction }, argv.env),
  enabled: {
    sourceMaps: !isProduction,
    uglify: isProduction,
    cacheBusting: isProduction,
    watcher: !!argv.watch,
  },
  watch: [
    'templates/**/*.php',
    'src/**/*.php',
  ],
}, userConfig);
