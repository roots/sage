/* jshint node:true */

'use strict';

var prefix          = require('autoprefixer'),
    gutil           = require('gulp-util'),
    transform       = require('stream').Transform,
    bufferstreams   = require('bufferstreams'),

    PLUGIN_NAME     = 'gulp-autoprefixer';

function autoprefixerTransform(browsers, options) {
  // Returns a callback that handles the buffered content
  return function(err, buffer, callback) {
    if (err) {
      callback(gutil.PluginError(PLUGIN_NAME, err));
    }
    var prefixed = prefix(browsers).process(String(buffer), options).css;
    callback(null, new Buffer(prefixed));
  };
}

function gulpautoprefixer() {
  var stream = new transform({ objectMode: true }),
      browsers,
      options;

  if (arguments.length) {
    var args = [].slice.call(arguments, 0);

    if (Array.isArray(args[0])) {
      browsers = args.shift();
    }

    var lastArg = args[args.length-1];
    if ((typeof lastArg === 'object') && (lastArg !== null)) {
      options = args.pop();
    }

    if (!browsers && args.length) {
      browsers = args;
    }
  }

  stream._transform = function(file, unused, done) {
    // Pass through if null
    if (file.isNull()) {
      stream.push(file);
      done();
      return;
    }
    if (file.isStream()) {
      try {
        file.contents = file.contents.pipe(new bufferstreams(autoprefixerTransform(browsers, options)));
      } catch (err) {
        err.fileName = file.path;
        stream.emit('error', new gutil.PluginError('gulp-autoprefixer', err));
      }
      stream.push(file);
      done();
    } else {
      try {
        var prefixed = prefix(browsers).process(String(file.contents), options).css;
        file.contents = new Buffer(prefixed);
      } catch (err) {
        err.fileName = file.path;
        stream.emit('error', new gutil.PluginError('gulp-autoprefixer', err));
      }
      stream.push(file);
      done();
    }
  };
  return stream;
}

gulpautoprefixer.autoprefixerTransform = autoprefixerTransform;
module.exports = gulpautoprefixer;
