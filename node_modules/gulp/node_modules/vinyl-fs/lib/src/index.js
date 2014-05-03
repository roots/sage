var map = require('map-stream');
var gs = require('glob-stream');

var File = require('vinyl');
var getContents = require('./getContents');
var getStats = require('./getStats');

var validateGlob = function(glob) {
  var isArr = Array.isArray(glob);
  if (typeof glob !== 'string' && !isArr) return false;
  if (isArr && isArr.length === 0) return false;
  return true;
};

module.exports = function(glob, opt) {
  if (!validateGlob(glob)) throw new Error('Invalid glob pattern');

  if (!opt) opt = {};
  if (typeof opt.read !== 'boolean') opt.read = true;
  if (typeof opt.buffer !== 'boolean') opt.buffer = true;

  var globStream = gs.create(glob, opt);
  var formatStream = map(function(file, cb){
    cb(null, new File(file));
  });

  var stream = globStream
    .pipe(formatStream)
    .pipe(getStats(opt));

  if (!opt.read) return stream; // no reading required

  return stream.pipe(getContents(opt));
};
