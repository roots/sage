/* eslint-disable import/no-extraneous-dependencies */

const mergeWith = require('lodash/mergeWith');

module.exports = (...args) => mergeWith(...args, (a, b) => {
  if (Array.isArray(a) && Array.isArray(b)) {
    return a.concat(b);
  }
  return undefined;
});
