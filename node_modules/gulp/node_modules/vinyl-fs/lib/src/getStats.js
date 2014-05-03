var map = require('map-stream');
var fs = require('graceful-fs');

module.exports = function(opt) {
  return map(function (file, cb) {
    fs.stat(file.path, function (err, stat) {
      if (stat) {
        file.stat = stat;
      }
      cb(err, file);
    });
  });
};