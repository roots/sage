/**
 * Loop through webpack entry
 * and add the hot middleware
 * @param  {Object} entry webpack entry
 * @return {Object} entry with hot middleware
 */
module.exports = (entry) => {
  const results = {};

  Object.keys(entry).forEach((name) => {
    results[name] = Array.isArray(entry[name]) ? entry[name].slice(0) : [entry[name]];
    results[name].unshift('webpack-hot-middleware/client?timeout=20000&reload=true');
    // Fix HMR resources URLs in external clients (working with BrowserSync)
    results[name].unshift('./scripts/util/fixBSHMR.js');
    // Polyfills for Internet Explorer
    results[name].unshift('eventsource-polyfill');
    results[name].unshift('es6-promise-promise');
  });
  return results;
};
