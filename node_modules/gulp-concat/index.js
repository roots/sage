var through = require('through');
var os = require('os');
var path = require('path');
var gutil = require('gulp-util');
var PluginError = gutil.PluginError;
var File = gutil.File;
var Buffer = require('buffer').Buffer;

module.exports = function(fileName, opt){
  if (!fileName) throw new PluginError('gulp-concat', 'Missing fileName option for gulp-concat');
  if (!opt) opt = {};
  // to preserve existing |undefined| behaviour and to introduce |newLine: ""| for binaries
  if (typeof opt.newLine !== 'string') opt.newLine = gutil.linefeed;

  var buffer = [];
  var firstFile = null;
  var newLineBuffer = opt.newLine ? new Buffer(opt.newLine) : null;

  function bufferContents(file){
    if (file.isNull()) return; // ignore
    if (file.isStream()) return this.emit('error', new PluginError('gulp-concat',  'Streaming not supported'));

    if (firstFile && newLineBuffer) buffer.push(newLineBuffer);
    if (!firstFile) firstFile = file;

    buffer.push(file.contents);
  }

  function endStream(){
    if (buffer.length === 0) return this.emit('end');

    var joinedContents = Buffer.concat(buffer);

    var joinedPath = path.join(firstFile.base, fileName);

    var joinedFile = new File({
      cwd: firstFile.cwd,
      base: firstFile.base,
      path: joinedPath,
      contents: joinedContents
    });

    this.emit('data', joinedFile);
    this.emit('end');
  }

  return through(bufferContents, endStream);
};
