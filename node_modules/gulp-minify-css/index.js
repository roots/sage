var es = require('event-stream'),
  CleanCSS  = require('clean-css'),
  BufferStreams = require('bufferstreams'),
  gutil = require('gulp-util'),
  path = require('path'),
  cache = require('memory-cache');

function objectIsEqual(a, b) {
  return JSON.stringify(a) === JSON.stringify(b);
}

function minify(options, file, buffer) {
  var rawContents = String(buffer);
  var cached;
  if (options.cache &&
      (cached = cache.get(file.path)) &&
      cached.raw === rawContents &&
      objectIsEqual(cached.options, options)) {

      // cache hit
      css = cached.minified;

  } else {
    // cache miss or cache not enabled
    css = new CleanCSS(options).minify(rawContents);

    if (options.cache) {
      cache.put(file.path, {
        raw: rawContents,
        minified: css,
        options: options
      });
    }
  }
  return css;
}

// File level transform function
function minifyCSSTransform(opt, file) {

  // Return a callback function handling the buffered content
  return function(err, buf, cb) {

    // Handle any error
    if(err) cb(gutil.PluginError('minify-css', err));

    // Use the buffered content
    buf = Buffer(minify(opt, file, buf));

    // Bring it back to streams
    cb(null, buf);
  };
}

// Plugin function
function minifyCSSGulp(opt){
  if (!opt) opt = {};

  function modifyContents(file, cb){
    if(file.isNull()) return cb(null, file);

    if(file.isStream()) {

      file.contents = file.contents.pipe(new BufferStreams(minifyCSSTransform(opt, file)));

      return cb(null, file);
    }

    var newFile = file.clone();

    // Image URLs are rebased with the assumption that they are relative to the
    // CSS file they appear in (unless "relativeTo" option is explicitly set by
    // caller)
    var relativeToTmp = opt.relativeTo;
    opt.relativeTo = relativeToTmp || path.resolve(path.dirname(file.path));

    var newContents = minify(opt, file, newFile.contents);

    // Restore original "relativeTo" value
    opt.relativeTo = relativeToTmp;

    newFile.contents = new Buffer(newContents);
    cb(null, newFile);
  }

  return es.map(modifyContents);
}

// Export the file level transform function for other plugins usage
minifyCSSGulp.fileTransform = minifyCSSTransform;

// Export the plugin main function
module.exports = minifyCSSGulp;
