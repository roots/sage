var gulp = require('gulp'),
  expect = require('chai').expect,
  minifyCSS = require('../'),
  CleanCSS = require('clean-css'),
  es = require('event-stream'),
  Stream = require('stream'),
  path = require('path'),
  fs = require('fs');

require('mocha');

describe('gulp-minify-css minification', function() {
  var opts = {
    keepSpecialComments: 1,
    keepBreaks: true
  };
  
  describe('with buffers', function() {
    var filename = path.join(__dirname, './fixture/index.css');
    it('should minify my files', function(done) {
      gulp.src(filename)
      .pipe(minifyCSS(opts))
      .pipe(es.map(function(file){
        var source = fs.readFileSync(filename),
          expected = new CleanCSS(opts).minify(source.toString());
        expect(expected).to.be.equal(file.contents.toString());
        done();
      }));
    });

    it('should return file.contents as a buffer', function(done) {
      gulp.src(filename)
      .pipe(minifyCSS())
      .pipe(es.map(function(file) {
        expect(file.contents).to.be.an.instanceof(Buffer);
        done();
      }));
    });
  });
  describe('with streams', function() {
    var filename = path.join(__dirname, './fixture/index.css');
    it('should minify my files', function(done) {
      gulp.src(filename, {buffer: false})
      .pipe(minifyCSS(opts))
      .pipe(es.map(function(file){
        var source = fs.readFileSync(filename),
          expected = new CleanCSS(opts).minify(source.toString());
        file.contents.pipe(es.wait(function(err, data) {
          expect(expected).to.be.equal(data);
          done();
        }));
      }));
    });

    it('should return file.contents as a stream', function(done) {
      gulp.src(filename, {buffer: false})
      .pipe(minifyCSS(opts))
      .pipe(es.map(function(file) {
        expect(file.contents).to.be.an.instanceof(Stream);
        done();
      }));
    });
  });
});
