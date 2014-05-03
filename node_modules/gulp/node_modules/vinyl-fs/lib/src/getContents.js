var map = require('map-stream');

var readDir = require('./readDir');
var bufferFile = require('./bufferFile');
var streamFile = require('./streamFile');

module.exports = function(opt) {
  return map(function (file, cb) {
    // don't fail to read a directory
    if (file.stat && file.stat.isDirectory()) {
      return readDir(file, cb);
    }

    // read and pass full contents
    if (opt.buffer) {
      return bufferFile(file, cb);
    }

    // dont buffer anything - just pass streams
    return streamFile(file, cb);
  });
};