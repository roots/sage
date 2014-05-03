var assert = require('assert');
var gsass = require('../');
var gutil = require('gulp-util');
var fs = require('fs');
var path = require('path');
var test = require('tape');

function createVinyl(sassFileName, contents) {
  var base = path.join(__dirname, 'scss');
  var filePath = path.join(base, sassFileName);

  return new gutil.File({
    cwd: __dirname,
    base: base,
    path: filePath,
    contents: contents || fs.readFileSync(filePath)
  });
}

test('pass file when isNull()', function (t) {
  var stream = gsass();
  var emptyFile = {
    isNull: function () { return true; }
  };
  stream.on('data', function (data) {
    t.equal(data, emptyFile);
    t.end();
  });
  stream.write(emptyFile);
});

// test('emit error when file isStream()', function (t) {
//   var stream = gsass();
//   var streamFile = {
//     isNull: function () { return false; },
//     isStream: function () { return true; }
//   };
//   stream.on()
// });

test('compile a single sass file', function (t) {
  var sassFile = createVinyl('mixins.scss');

  var stream = gsass();
  stream.on('data', function (cssFile) {
    t.ok(cssFile, 'cssFile should exist');
    t.ok(cssFile.path, 'cssFile.path should exist');
    t.ok(cssFile.relative, 'cssFile.relative should exist');
    t.ok(cssFile.contents, 'cssFile.contents should exist');
    t.equal(cssFile.path, path.join(__dirname, 'scss', 'mixins.css'));
    t.equal(
      fs.readFileSync(path.join(__dirname, 'ref/mixins.css'), 'utf8'),
      cssFile.contents.toString(),
      'file compiles correctly to css'
    );
    t.end();
  })
  stream.write(sassFile);
});

test('compile multiple sass files', function (t) {
  var files = [
    createVinyl('inheritance.scss'),
    createVinyl('mixins.scss'),
    createVinyl('nesting.scss'),
    createVinyl('variables.scss')
  ];

  t.plan(files.length * 4);
  var stream = gsass();

  stream.on('data', function (cssFile) {
    t.ok(cssFile, 'cssFile exists');
    t.ok(cssFile.path, 'cssFile.path exists');
    t.ok(cssFile.relative, 'cssFile.relative exists');
    t.ok(cssFile.contents, 'cssFile.contents exists');
  });

  files.forEach(function (file) {
    stream.write(file);
  });
});

test('emit error on sass errors', function (t) {
  var stream = gsass();
  var errorFile = createVinyl('somefile.sass',
    new Buffer('body { font \'Comic Sans\'; }'));
  stream.on('error', function (err) {
    t.equal(err.message,
            'source string:1: error: property "font" must be followed by a \':\'\n'
    );
    t.end();
  });
  stream.write(errorFile);
});

test('call custom error callback when opts.onError is given', function (t) {
  var stream = gsass({ onError: function (err) {
    t.equal(err,
            'source string:1: error: property "font" must be followed by a \':\'\n'
    );
    t.end();
  }});

  var errorFile = createVinyl('somefile.sass',
    new Buffer('body { font \'Comic Sans\'; }'));

  stream.write(errorFile);
});