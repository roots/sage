var fs = require('graceful-fs');

module.exports = function (file, cb) {
  fs.readFile(file.path, function (err, data) {
    if (data) {
      file.contents = data;
    }
    cb(err, file);
  });
};