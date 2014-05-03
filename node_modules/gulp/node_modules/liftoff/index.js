const util = require('util');
const path = require('path');
const EE = require('events').EventEmitter;
const fs = require('fs');
const extend = require('extend');
const resolve = require('resolve');
const fileSearch = require('./lib/file_search');
const parseOptions = require('./lib/parse_options');
const silentRequire = require('./lib/silent_require');
const validExtensions = require('./lib/valid_extensions');

function Liftoff (opts) {
  EE.call(this);
  extend(this, parseOptions(opts));
}
util.inherits(Liftoff, EE);

Liftoff.prototype.requireLocal = function (module, basedir) {
  try {
    var result = require(resolve.sync(module, {basedir: basedir}));
    this.emit('require', module, result);
    return result;
  } catch (e) {
    this.emit('requireFail', module, e);
  }
};

Liftoff.prototype.findCwd = function (argv) {
  argv = argv||{};
  var cwd = argv[this.cwdFlag];
  var configPath = argv[this.configPathFlag];
  // if a path to the desired config was specified but no cwd
  // was provided, use the dir of the config.
  if (typeof configPath === 'string' && !cwd) {
    cwd = path.dirname(path.resolve(configPath));
  }
  if (typeof cwd === 'string') {
    return path.resolve(cwd);
  } else {
    return process.cwd();
  }
};

// TODO: break this into smaller methods.
Liftoff.prototype.buildEnvironment = function (argv) {
  argv = argv||{};

  // attempt preloading modules
  var preload = argv[this.preloadFlag];
  if (preload) {
    if (!Array.isArray(preload)) {
      preload = [preload];
    }
    preload.forEach(function (dep) {
      this.requireLocal(dep, this.findCwd(argv));
    }, this);
  }

  // calculate cwd
  var cwd = this.findCwd(argv);

  // calculate config file name
  var configNameRegex = this.configName;
  var extensions = validExtensions(this.addExtensions);
  if (configNameRegex instanceof RegExp) {
    configNameRegex = configNameRegex.toString();
  } else {
    configNameRegex += '{'+extensions.join(',')+'}';
  }

  // get configPath from cli if provided
  var configPath = argv[this.configPathFlag];
  if (configPath) {
    // null out provided configPath if it doesn't exist
    if (!fs.existsSync(configPath)) {
      configPath = null;
    }
  } else {
    var searchIn = [cwd];
    // if cwd hasn't been set explicitly, use global search paths too
    if (!argv[this.cwdFlag]) {
      searchIn = searchIn.concat(this.searchPaths)
    }
    // if no configPath was provided, go find it
    configPath = fileSearch(configNameRegex, searchIn);
  }

  // if we have a config path, find the directory it resides in
  if (configPath) {
    configPath = path.resolve(configPath);
    var configBase = path.dirname(configPath);
  }

  // locate local module and package in config directory
  var modulePath, modulePackage;
  try {
    modulePath = resolve.sync(this.moduleName, {basedir: configBase || cwd});
    modulePackage = silentRequire(fileSearch('package.json', [modulePath]));
  } catch (e) {}

  // if we have a configuration but we failed to find a local module, maybe
  // we are developing against ourselves?  check the package.json sibling to
  // our config to see if its `name` matches the module we're looking for
  if (!modulePath && configBase) {
    modulePackage = silentRequire(fileSearch('package.json', [configBase]));
    if (modulePackage && modulePackage.name === this.moduleName) {
      modulePath = path.join(configBase, modulePackage.main||'index.js');
      cwd = configBase;
    } else {
      // clear if we just required a package for some other project
      modulePackage = {};
    }
  }

  return {
    argv: argv,
    cwd: cwd,
    preload: preload||[],
    validExtensions: extensions,
    configNameRegex: configNameRegex,
    configPath: configPath,
    configBase: configBase,
    modulePath: modulePath,
    modulePackage: modulePackage||{}
  };
};

Liftoff.prototype.launch = function (fn, argv) {
  if (typeof fn !== 'function') {
    throw new Error('You must provide a callback function.');
  }

  if (!argv) {
    argv = require('minimist')(process.argv.slice(2));
  }

  process.title = this.processTitle;

  var completion = argv[this.completionFlag];
  if (completion && this.completions) {
    return this.completions(completion);
  }

  fn.call(this, this.buildEnvironment(argv));
};

module.exports = Liftoff;
