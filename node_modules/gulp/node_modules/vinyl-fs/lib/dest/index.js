var map = require('map-stream');
var path = require('path');
var mkdirp = require('mkdirp');

var writeContents = require('./writeContents');

var defaultMode = 0777 & (~process.umask());

module.exports = function(outFolder, opt) {
  if (typeof outFolder !== 'string') throw new Error('Invalid output folder');

  if (!opt) opt = {};
  if (!opt.cwd) opt.cwd = process.cwd();
  if (typeof opt.mode === 'string') opt.mode = parseInt(opt.mode, 8);

  var cwd = path.resolve(opt.cwd);
  var basePath = path.resolve(cwd, outFolder);
  var folderMode = (opt.mode || defaultMode);

  function saveFile (file, cb) {
    var writePath = path.resolve(basePath, file.relative);
    var writeFolder = path.dirname(writePath);

    if (typeof opt.mode !== 'undefined') {
      if (!file.stat) file.stat = {};
      file.stat.mode = opt.mode;
    }

    file.cwd = cwd;
    file.base = basePath;
    file.path = writePath;

    // mkdirp the folder the file is going in
    // then write to it
    mkdirp(writeFolder, folderMode, function(err){
      if (err) return cb(err);
      writeContents(writePath, file, cb);
    });
  }
  var stream = map(saveFile);
  return stream;
};
