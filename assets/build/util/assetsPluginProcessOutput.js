
const path = require('path');

/**
 * Process AssetsPlugin output and format it
 * for Sage: {"[name].[ext]":"[name]_[hash].[ext]"}
 * @param  {Object} assets passed by processOutput
 * @return {String}        JSON
 */
module.exports = (assets) => {
  const manifest = {};
  Object.keys(assets).forEach((name) => {
    Object.keys(assets[name]).forEach((ext) => {
      const filename = `${path.dirname(assets[name][ext])}/${path.basename(`${name}.${ext}`)}`;
      manifest[filename] = assets[name][ext];
    });
  });
  return manifest;
};
