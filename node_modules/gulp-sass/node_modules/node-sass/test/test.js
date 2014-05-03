/* global beforeEach, afterEach */
/*jshint multistr:true */
var sass = process.env.NODESASS_COVERAGE ? require('../sass-coverage') : require('../sass');
var assert = require('assert');
var path = require('path');
var fs = require('fs');
var sinon = require('sinon');
var badSampleFilename = 'sample.scss';
var sampleFilename = path.resolve(__dirname, 'sample.scss');

var scssStr = '#navbar {\
  width: 80%;\
  height: 23px; }\
  #navbar ul {\
    list-style-type: none; }\
  #navbar li {\
    float: left;\
    a {\
      font-weight: bold; }}\
  @mixin keyAnimation($name, $attr, $value) {\
    @-webkit-keyframes #{$name} {\
      0%   { #{$attr}: $value; }\
    }\
  }';

// Note that the bad
var badInput = '#navbar \n\
  width: 80%';

var expectedRender = '#navbar {\n\
  width: 80%;\n\
  height: 23px; }\n\
\n\
#navbar ul {\n\
  list-style-type: none; }\n\
\n\
#navbar li {\n\
  float: left; }\n\
  #navbar li a {\n\
    font-weight: bold; }\n';

describe("DEPRECATED: compile scss", function() {
  it("should compile with render", function(done) {
    sass.render(scssStr, function(err) {
      done(err);
    });
  });

  it("should compile with renderSync", function(done) {
    done(assert.ok(sass.renderSync(scssStr)));
  });

  it("should match compiled string with render", function(done) {
    sass.render(scssStr, function(err, css) {
      if (!err) {
        done(assert.equal(css, expectedRender));
      } else {
        done(err);
      }
    });
  });

  it("should match compiled string with renderSync", function(done) {
    done(assert.equal(sass.renderSync(scssStr), expectedRender));
  });

  it("should throw an exception for bad input", function(done) {
    done(assert.throws(function() {
      sass.renderSync(badInput);
    }));
  });
});

describe("compile scss", function() {
  it("should compile with render", function(done) {
    sass.render({
      data: scssStr,
      success: function(css) {
        done(assert.ok(css));
      }
    });
  });

  it("should compile with renderSync", function(done) {
    done(assert.ok(sass.renderSync({data: scssStr})));
  });

  it("should match compiled string with render", function(done) {
    sass.render({
      data: scssStr,
      success: function(css) {
        done(assert.equal(css, expectedRender));
      },
      error: function(error) {
        done(error);
      }
    });
  });

  it("should have a error status of 1 for bad css", function(done) {
    sass.render({
      data: '{zzz}',
      success: function(css) {
        console.log(css);
      },
      error: function(error, status) {
        assert.equal(status, 1);
        done();
      }
    });
  });

  it("should match compiled string with renderSync", function(done) {
    done(assert.equal(sass.renderSync({data: scssStr}), expectedRender));
  });

  it("should throw an exception for bad input", function(done) {
    done(assert.throws(function() {
      sass.renderSync({data: badInput});
    }));
  });
});

describe("compile file with include paths", function(){
  it("should compile with render", function(done) {
    sass.render({
      file: path.resolve(__dirname, "include_path.scss"),
      includePaths: [path.resolve(__dirname, "lib"), path.resolve(__dirname, "functions")],
      success: function (css) {
        done(assert.equal(css, "body {\n  background: red;\n  color: #0000fe; }\n"));
      },
      error: function (error) {
        done(error);
      }
    });
  });
});

describe("compile file with image path", function(){
  it("should compile with render", function(done) {
    sass.render({
      file: path.resolve(__dirname, "image_path.scss"),
      imagePath: '/path/to/images',
      success: function (css) {
        done(assert.equal(css, "body {\n  background-image: url(\"/path/to/images/image.png\"); }\n"));
      },
      error: function (error) {
        done(error);
      }
    });
  });
});

describe("compile file", function() {
  it("should compile with render", function(done) {
    sass.render({
      file: sampleFilename,
      success: function (css) {
        done(assert.equal(css, expectedRender));
      },
      error: function (error) {
        done(error);
      }
    });
  });

  it("should compile with renderSync", function(done) {
    done(assert.ok(sass.renderSync({file: sampleFilename})));
  });

  it("should match compiled string with render", function(done) {
    sass.render({
      file: sampleFilename,
      success: function(css) {
        done(assert.equal(css, expectedRender));
      },
      error: function(error) {
        done(error);
      }
    });
  });

  it("should match compiled string with renderSync", function(done) {
    done(assert.equal(sass.renderSync({file: sampleFilename}), expectedRender));
  });

  it("should throw an exception for bad input", function(done) {
    done(assert.throws(function() {
      sass.renderSync({file: badSampleFilename});
    }));
  });
});

describe("render to file", function() {
  var outFile = path.resolve(__dirname, 'out.css'),
      filesWritten;

  beforeEach(function() {
    filesWritten = {};
    sinon.stub(fs, 'writeFile', function(path, contents, cb) {
      filesWritten[path] = contents;
      cb();
    });
  });
  afterEach(function() {
    fs.writeFile.restore();
  });
  it("should compile with renderFile", function(done) {
    sass.renderFile({
      file: sampleFilename,
      outFile: outFile,
      success: function () {
        var contents = filesWritten[outFile];
        done(assert.equal(contents, expectedRender));
      },
      error: function (error) {
        done(error);
      }
    });
  });

  it("should raise an error for bad input", function(done) {
    sass.renderFile({
      file: badSampleFilename,
      outFile: outFile,
      success: function() {
        assert(false, "success() should not be called");
        done();
      },
      error: function() {
        assert(true, "error() called");
        done();
      }
    });
  });

  it("should save the sourceMap to the default file name", function(done) {
    sass.renderFile({
      file: sampleFilename,
      outFile: outFile,
      sourceMap: true,
      success: function (cssFile, sourceMapFile) {
        var css = filesWritten[cssFile];
        var map = filesWritten[sourceMapFile];
        var mapFileName = 'out.css.map';
        assert.equal(path.basename(sourceMapFile), mapFileName);
        assert.ok(css.indexOf('sourceMappingURL=' + mapFileName) !== -1);
        assert.ok(map.indexOf('sample.scss') !== -1);
        done();
      },
      error: function (error) {
        done(error);
      }
    });
  });

  it("should save the sourceMap to a specified file name", function(done) {
    var mapFileName = 'foo.css.map';
    sass.renderFile({
      file: sampleFilename,
      outFile: outFile,
      sourceMap: mapFileName,
      success: function (cssFile, sourceMapFile) {
        var css = filesWritten[cssFile];
        var map = filesWritten[sourceMapFile];
        assert.equal(path.basename(sourceMapFile), mapFileName);
        assert.ok(css.indexOf('sourceMappingURL=' + mapFileName) !== -1);
        assert.ok(map.indexOf('sample.scss') !== -1);
        done();
      },
      error: function (error) {
        done(error);
      }
    });
  });

});
