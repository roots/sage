var path = require('path');
var fs = require('fs');

function requireBinding() {
  var v8 = 'v8-' + /[0-9]+\.[0-9]+/.exec(process.versions.v8)[0];

  var candidates = [
    [__dirname, 'build', 'Release', 'obj.target', 'binding.node'],
    [__dirname, 'bin', process.platform + '-' + process.arch + '-' + v8, 'binding.node'],
  ];

  for (var i = 0, l = candidates.length; i < l; i++) {
    var candidate = path.join.apply(path.join, candidates[i]);

    if (fs.existsSync(candidate)) {
      return require(candidate);
    }
  }

  throw new Error('`libsass` bindings not found. Try reinstalling `node-sass`?');
}

var binding = requireBinding();

var SASS_OUTPUT_STYLE = {
    nested: 0,
    expanded: 1,
    compact: 2,
    compressed: 3
  };

var SASS_SOURCE_COMMENTS = {
    none: 0,
    // This is called default in libsass, but is a reserved keyword here
    normal: 1,
    map: 2
  };

var prepareOptions = function(options) {
  var paths, imagePath, style, comments;
  options = typeof options !== 'object' ? {} : options;
  var sourceComments = options.source_comments || options.sourceComments;
  if (options.sourceMap && !sourceComments) {
    sourceComments = 'map';
  }
  paths = options.include_paths || options.includePaths || [];
  imagePath = options.image_path || options.imagePath || '';
  style = SASS_OUTPUT_STYLE[options.output_style || options.outputStyle] || 0;
  comments = SASS_SOURCE_COMMENTS[sourceComments] || 0;

  return {
    paths: paths,
    imagePath: imagePath,
    style: style,
    comments: comments
  };
};

var deprecatedRender = function(css, callback, options) {
  options = prepareOptions(options);
  var errCallback = function(err) {
    callback(err);
  };
  var oldCallback = function(css) {
    callback(null, css);
  };
  return binding.render(css, options.imagePath, oldCallback, errCallback, options.paths.join(path.delimiter), options.style, options.comments);
};

var deprecatedRenderSync = function(css, options) {
  options = prepareOptions(options);
  return binding.renderSync(css, options.imagePath, options.paths.join(path.delimiter), options.style, options.comments);
};

exports.render = function(options) {
  var newOptions;

  if (typeof arguments[0] === 'string') {
    return deprecatedRender.apply(this, arguments);
  }

  newOptions = prepareOptions(options);
  options.error = options.error || function(){};

  if (options.file !== undefined && options.file !== null) {
    return binding.renderFile(options.file, newOptions.imagePath, options.success, options.error, newOptions.paths.join(path.delimiter), newOptions.style, newOptions.comments, options.sourceMap);
  }

  //Assume data is present if file is not. binding/libsass will tell the user otherwise!
  return binding.render(options.data, newOptions.imagePath, options.success, options.error, newOptions.paths.join(path.delimiter), newOptions.style);
};

exports.renderSync = function(options) {
  var newOptions;

  if (typeof arguments[0] === 'string') {
    return deprecatedRenderSync.apply(this, arguments);
  }

  newOptions = prepareOptions(options);

  if (options.file !== undefined && options.file !== null) {
    return binding.renderFileSync(options.file, newOptions.imagePath, newOptions.paths.join(path.delimiter), newOptions.style, newOptions.comments);
  }

  //Assume data is present if file is not. binding/libsass will tell the user otherwise!
  return binding.renderSync(options.data, newOptions.imagePath, newOptions.paths.join(path.delimiter), newOptions.style);
};

/**
  Same as `render()` but with an extra `outFile` property in `options` and writes
  the CSS and sourceMap (if requested) to the filesystem.
  
  `options.sourceMap` can be used to specify that the source map should be saved:
  
  - If falsy the source map will not be saved
  - If `options.sourceMap === true` the source map will be saved to the
  standard location of `options.file + '.map'`
  - Else `options.sourceMap` specifies the path (relative to the `outFile`) 
  where the source map should be saved
 */
exports.renderFile = function(options) {
  var newOptions = {};
  for (var i in options) {
    if (options.hasOwnProperty(i)) {
      newOptions[i] = options[i];
    }
  }
  if (options.sourceMap === true) {
    newOptions.sourceMap = path.basename(options.outFile) + '.map';
  }
  newOptions.success = function(css, sourceMap) {
    fs.writeFile(options.outFile, css, function(err) {
      if (err) {
        return error(err);
      }
      if (options.sourceMap) {
        var dir = path.dirname(options.outFile);
        var sourceMapFile = path.resolve(dir, newOptions.sourceMap);
        fs.writeFile(sourceMapFile, sourceMap, function(err) {
          if (err) {
            return error(err);
          }
          success(options.outFile, sourceMapFile);
        });
      }
      else {
        success(options.outFile);
      }
    });
  };
  function error(err) {
    if (options.error) {
      options.error(err);
    }
  }
  function success(css, sourceMap) {
    if (options.success) {
      options.success(css, sourceMap);
    }
  }
  exports.render(newOptions);
};

exports.middleware = require('./lib/middleware');
