var fs = require('graceful-fs');

module.exports = function(writePath, file, cb) {
  var opt = {};

  if (file.stat && typeof file.stat.mode !== 'undefined') {
    opt.mode = file.stat.mode;
  }

  fs.writeFile(writePath, file.contents, opt, cb);
};
