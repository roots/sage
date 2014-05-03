var util = require('util');
var colors = require('./colors');

// wow what a clusterfuck
var parseOptions = function(plugin, message, opt) {
  if (!opt) opt = {};
  if (typeof plugin === 'object') {
    opt = plugin;
  } else if (message instanceof Error) {
    opt.message = message;
    opt.plugin = plugin;
  } else if (typeof message === 'object') {
    opt = message;
    opt.plugin = plugin;
  } else if (typeof opt === 'object') {
    opt.plugin = plugin;
    opt.message = message;
  }
  return opt;
};

function PluginError(plugin, message, opt) {
  if (!(this instanceof PluginError)) throw new Error('Call PluginError using new');

  Error.call(this);

  var options = parseOptions(plugin, message, opt);

  this.plugin = options.plugin;
  this.showStack = options.showStack;

  // if message is an Error grab crap off it
  if (options.message instanceof Error) {
    this.name = options.message.name;
    this.message = options.message.message;
    this.fileName = options.message.fileName;
    this.lineNumber = options.message.lineNumber;
    this.stack = options.message.stack;
  } else { // else check options obj
    this.name = options.name;
    this.message = options.message;
    this.fileName = options.fileName;
    this.lineNumber = options.lineNumber;
    this.stack = options.stack;
  }

  // defaults
  if (!this.name) this.name = 'Error';

  // TODO: figure out why this explodes mocha
  if (!this.stack) Error.captureStackTrace(this, arguments.callee || this.constructor);

  if (!this.plugin) throw new Error('Missing plugin name');
  if (!this.message) throw new Error('Missing error message');
}

util.inherits(PluginError, Error);

PluginError.prototype.toString = function () {
  var sig = '['+colors.green('gulp')+'] '+this.name+' in plugin \''+colors.cyan(this.plugin)+'\'';
  var msg = this.showStack ? (this._stack || this.stack) : this.message;
  return sig+': '+msg;
};

module.exports = PluginError;