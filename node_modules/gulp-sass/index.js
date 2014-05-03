var fs    = require('fs')
  , map   = require('map-stream')
  , sass  = require('node-sass')
  , path  = require('path')
  , gutil = require('gulp-util')
  , ext   = gutil.replaceExtension
  ;

module.exports = function (options) {
  var opts = options ? options : {};

  function nodeSass (file, cb) {
    var fileDir = path.dirname(file.path);
    var addedLocalDirPath = false;

    if (file.isNull()) {
      return cb(null, file);
    }
    if (path.basename(file.path).indexOf('_') === 0) {
      return cb();
    }

    if (opts.sourceComments === 'map' || opts.sourceComments === 'normal') {
      opts.file = file.path;
    } else {
      opts.data = file.contents.toString();
    }

    if (opts.includePaths && Array.isArray(opts.includePaths)) {
      if (opts.includePaths.indexOf(fileDir) === -1) {
        opts.includePaths.push(fileDir);
        addedLocalDirPath = true;
      }
    } else {
      opts.includePaths = [fileDir];
    }

    opts.success = function (css, map) {
      var sourceMap;
      if (typeof opts.onSuccess === 'function') opts.onSuccess(css, map);

      if (map) {
        map = JSON.parse(map);
        map.sourcesContent = getSourcesContent(map.sources);
        sourceMap = new Buffer(JSON.stringify(map)).toString('base64');
        css = css.replace(/\/\*# sourceMappingURL=.*\*\//,
                          "/*# sourceMappingURL=data:application/json;base64," +
                          sourceMap + "*/");
      }

      file.path      = ext(file.path, '.css');
      file.contents  = new Buffer(css);
      cb(null, file);
    };

    opts.error = function (err) {
      if (opts.errLogToConsole) {
        gutil.log('[gulp-sass] ' + err);
        return cb();
      }

      if (typeof opts.onError === 'function') { 
        opts.onError(err);
        return cb();
      }

      return cb(new gutil.PluginError('gulp-sass', err));
    };

    sass.render(opts);

    if (addedLocalDirPath) opts.includePaths.pop();

  }

  return map(nodeSass);
};

function getSourcesContent (sources) {
  sourcesContent = [];

  for (var i = 0; i < sources.length; i++) {
    sourcesContent[i] = fs.readFileSync(sources[i], { encoding: 'utf8' });
  }

  return sourcesContent;
}
