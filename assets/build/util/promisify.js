/**
 * Node-style asynchronous function.
 *
 * @callback nodeAsyncCallback
 * @param {string|null} err
 * @param {*} data
 */
/**
 * Promisify node-style asynchronous functions
 *
 * @param  {nodeAsyncCallback} fn - Function with node-style callback
 * @param  {this} [scope] - Scope to which the function should be bound. Default: fn
 * @returns {Promise} - An instance of Promise
 */
module.exports = (fn, scope) => function callback() {
  const args = [].slice.call(arguments);
  return new Promise((resolve, reject) => {
    args.push((err, data) => (err === null ? resolve(data) : reject(err)));
    return fn.apply(scope || fn, args);
  });
};
