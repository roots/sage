'use strict'; // eslint-disable-line

const path = require('path');
const utils = require('loader-utils');

/**
 * Generate output name from output pattern
 *
 * @link https://github.com/kevlened/copy-webpack-plugin/blob/323b1d74ef35ed2221637d8028b1bef854deb523/src/writeFile.js#L31-L65
 * @param {string} pattern
 * @param {string} relativeFrom
 * @param {binary} content
 * @return {string}
 */
module.exports = (pattern, relativeFrom, content) => {
  let webpackTo = pattern;
  let resourcePath = relativeFrom;

  /* A hack so .dotted files don't get parsed as extensions */
  const basename = path.basename(resourcePath);
  let dotRemoved = false;
  if (basename[0] === '.') {
    dotRemoved = true;
    resourcePath = path.join(path.dirname(resourcePath), basename.slice(1));
  }

  /**
   * If it doesn't have an extension, remove it from the pattern
   * ie. [name].[ext] or [name][ext] both become [name]
   */
  if (!path.extname(resourcePath)) {
    webpackTo = webpackTo.replace(/\.?\[ext]/g, '');
  }

  /**
   * A hack because loaderUtils.interpolateName doesn't
   * find the right path if no directory is defined
   * ie. [path] applied to 'file.txt' would return 'file'
   */
  if (resourcePath.indexOf('/') < 0) {
    resourcePath = `/${resourcePath}`;
  }

  webpackTo = utils.interpolateName({ resourcePath }, webpackTo, { content });

  if (dotRemoved) {
    webpackTo = path.join(path.dirname(webpackTo), `.${path.basename(webpackTo)}`);
  }
  return webpackTo;
};
