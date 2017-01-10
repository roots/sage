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
    optimize: isProduction,
    cacheBusting: isProduction,
    watcher: !!argv.watch,
  },
  watch: [],
}, userConfig);

config.watch.push(`${path.basename(config.paths.assets)}/${config.copy}`);
config.watch = uniq(config.watch);

module.exports = mergeWithConcat(config, {
  env: Object.assign({ production: isProduction, development: !isProduction }, argv.env),
  publicPath: `${config.publicPath}/${path.basename(config.paths.dist)}/`,
  manifest: {},
});

/**
 * If your publicPath differs between environments, but you know it at compile time,
 * then set SAGE_DIST_PATH as an environment variable before compiling.
 * Example:
 *   SAGE_DIST_PATH=/wp-content/themes/sage/dist yarn build:production
 */
if (process.env.SAGE_DIST_PATH) {
  module.exports.publicPath = process.env.SAGE_DIST_PATH;
}

/**
 * If you don't know your publicPath at compile time, then uncomment the lines
 * below and use WordPress's wp_localize_script() to set SAGE_DIST_PATH global.
 * Example:
 *   wp_localize_script('sage/main.js', 'SAGE_DIST_PATH', get_theme_file_uri('dist/'))
 */
// Object.keys(module.exports.entry).forEach(id =>
//   module.exports.entry[id].unshift(path.join(__dirname, 'public-path.js')));
