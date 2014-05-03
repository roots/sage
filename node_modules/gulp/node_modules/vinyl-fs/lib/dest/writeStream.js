var streamFile = require('../src/streamFile');
var fs = require('graceful-fs');

module.exports = function(writePath, file, cb) {
  var opt = {};

  if (file.stat && typeof file.stat.mode !== 'undefined') {
    opt.mode = file.stat.mode;
  }

  var outStream = fs.createWriteStream(writePath, opt);

  // TODO: can we pass the file along before the stream is unloaded?
  file.contents.once('error', cb);
  file.contents.pipe(outStream).once('finish', function() {
    streamFile(file, cb);
  });

  return outStream;
};
