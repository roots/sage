const mergeWith = require('lodash/mergeWith');

module.exports = function mergeWithConcat() {
  const args = [].slice.call(arguments);
  args.push((a, b) => {
    if (Array.isArray(a) && Array.isArray(b)) {
      return a.concat(b);
    }
    return undefined;
  });
  return mergeWith.apply(this, args);
};
