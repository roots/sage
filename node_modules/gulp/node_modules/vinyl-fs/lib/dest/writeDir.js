var mkdirp = require('mkdirp');

module.exports = function (writePath, file, cb) {
  // mode needs to be done
  if (file.stat && typeof file.stat.mode !== 'undefined') {
    mkdirp(writePath, file.stat.mode, cb);
    return;
  }

  // no mode
  mkdirp(writePath, cb);
};
