const path = require('path');
const argv = require('minimist')(process.argv.slice(2));
const uniq = require('lodash/uniq');

const mergeWithConcat = require('./util/mergeWithConcat');
const userConfig = require('../config');

const isProduction = !!((argv.env && argv.env.production) || argv.p);
const rootPath = (userConfig.paths && userConfig.paths.root)
  ? userConfig.paths.root
  : process.cwd();

const config = mergeWithConcat({
  copy: 'images/**/*',
  proxyUrl: 'http://localhost:3000',
  cacheBusting: '[name]_[hash]',
  paths: {
    root: rootPath,
    assets: path.join(rootPath, 'assets'),
    dist: path.join(rootPath, 'dist'),
  },
  enabled: {
    sourceMaps: !isProduction,
    minify: isProduction,
    cacheBusting: isProduction,
    watcher: !!argv.watch,
    uglifyJs: !(argv.p || argv.optimizeMinimize),
  },
  watch: [],
}, userConfig);

config.watch.push(config.copy);
config.watch = uniq(config.watch);

Object.keys(config.entry).forEach(id =>
  config.entry[id].unshift(path.join(__dirname, 'public-path.js')));

module.exports = mergeWithConcat(config, {
  env: Object.assign({ production: isProduction, development: !isProduction }, argv.env),
  publicPath: `${config.publicPath}/${path.basename(config.paths.dist)}/`,
  manifest: {},
});

