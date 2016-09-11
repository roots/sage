const path = require('path');

/**
 * Process AssetsPlugin output and format it
 * for Sage: {"[name].[ext]":"[name]_[hash].[ext]"}
 * @param  {Object} assets passed by processOutput
 * @return {String}        JSON
 */
module.exports = (assets) => {
  const results = {};
  Object.keys(assets).forEach(name => {
    Object.keys(assets[name]).forEach(ext => {
      const filename = `${path.dirname(assets[name][ext])}/${path.basename(`${name}.${ext}`)}`;
      results[filename] = assets[name][ext];
    });
  });
  return JSON.stringify(results);
};
