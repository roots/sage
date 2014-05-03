/*jshint multistr:true */
var sass = require('../sass');
var assert = require('assert');

var sampleFilename = require('path').resolve(__dirname, 'sample.scss');

describe("compile source maps", function() {
  it("should compile file with source map URL", function(done) {
    var mapFileName = 'sample.css.map';
    sass.render({
      file: sampleFilename,
      sourceComments: 'map',
      sourceMap: mapFileName,
      success: function (css, map) {
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
