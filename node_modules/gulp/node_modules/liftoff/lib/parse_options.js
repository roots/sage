const extend = require('extend');

module.exports = function (opts) {
  var defaults = {
    cwdFlag: 'cwd',
    preloadFlag: 'require',
    completionFlag: 'completion',
    completions: null,
    addExtensions: [],
    searchPaths: []
  };
  opts = opts||{};
  if (opts.name) {
    if (!opts.processTitle) {
      opts.processTitle = opts.name;
    }
    if (!opts.configName) {
      opts.configName = opts.name+'file';
    }
    if (!opts.moduleName) {
      opts.moduleName = opts.name;
    }
  }
  if (!opts.processTitle) {
    throw new Error('You must specify a processTitle.');
  }
  if (!opts.configName) {
    throw new Error('You must specify a configName.');
  }
  if (!opts.moduleName) {
    throw new Error('You must specify a moduleName.');
  }
  if (!opts.configPathFlag) {
    opts.configPathFlag = opts.configName;
  }
  return extend(defaults, opts);
};
