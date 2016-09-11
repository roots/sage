const path = require('path');
const argv = require('minimist')(process.argv.slice(2));
const glob = require('glob-all');
const merge = require('lodash/merge');

const mergeWithConcat = require('./util/mergeWithConcat');
const userConfig = require('../config');

const isProduction = !!((argv.env && argv.env.production) || argv.p);
const rootPath = (userConfig.paths && userConfig.paths.root)
  ? userConfig.paths.root
  : process.cwd();

const config = mergeWithConcat({
  copy: ['images/**/*'],
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
  watch: [
    'templates/**/*.php',
    'src/**/*.php',
  ],
}, userConfig);

Object.keys(config.entry).forEach(id =>
  config.entry[id].unshift(path.join(__dirname, 'public-path.js')));

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
  publicPath: `${config.publicPath}/${path.basename(config.paths.dist)}/`,
});
