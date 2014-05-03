/*jshint multistr:true */
var sass = require('../sass');
var assert = require('assert');

var sampleFilename = require('path').resolve(__dirname, 'sample.scss');

var expectedCommentsScssStr = '/* line 1, ' + sampleFilename + ' */\n\
#navbar {\n\
  width: 80%;\n\
  height: 23px; }\n\
\n\
/* line 5, ' + sampleFilename + ' */\n\
#navbar ul {\n\
  list-style-type: none; }\n\
\n\
/* line 8, ' + sampleFilename + ' */\n\
#navbar li {\n\
  float: left; }\n\
  /* line 10, ' + sampleFilename + ' */\n\
  #navbar li a {\n\
    font-weight: bold; }\n';

describe("compile file with source comments", function() {
  it("should compile with render and comment outputs", function(done) {
    sass.render({
      file: sampleFilename,
      source_comments: 'normal',
      success: function (css) {
        done(assert.equal(css, expectedCommentsScssStr));
      },
      error: function (error) {
        done(error);
      }
    });
  });
});
