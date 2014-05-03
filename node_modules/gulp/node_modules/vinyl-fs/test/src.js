var vfs = require('../');

var path = require('path');
var fs = require('graceful-fs');

var bufEqual = require('buffer-equal');
var through = require('through2');
var File = require('vinyl');

var should = require('should');
require('mocha');

var dataWrap = function(fn) {
  return function(data, enc, cb) {
    fn(data);
    cb();
  };
};

describe('source stream', function() {

  it('should explode on invalid glob (empty)', function(done) {
    var stream;
    try {
      stream = gulp.src();
    } catch (err) {
      should.exist(err);
      should.not.exist(stream);
      done();
    }
  });

  it('should explode on invalid glob (number)', function(done) {
    var stream;
    try {
      stream = gulp.src(123);
    } catch (err) {
      should.exist(err);
      should.not.exist(stream);
      done();
    }
  });

  it('should explode on invalid glob (empty array)', function(done) {
    var stream;
    try {
      stream = gulp.src([]);
    } catch (err) {
      should.exist(err);
      should.not.exist(stream);
      done();
    }
  });

  it('should pass through writes', function(done) {
    var expectedPath = path.join(__dirname, "./fixtures/test.coffee");
    var expectedContent = fs.readFileSync(expectedPath);

    var expectedFile = new File({
      base: __dirname,
      cwd: __dirname,
      path: expectedPath,
      contents: expectedContent
    });

    var onEnd = function(){
      buffered.length.should.equal(1);
      buffered[0].should.equal(expectedFile);
      bufEqual(buffered[0].contents, expectedContent).should.equal(true);
      done();
    };

    var stream = vfs.src("./fixtures/nothing.coffee");

    var buffered = [];
    bufferStream = through.obj(dataWrap(buffered.push.bind(buffered)), onEnd);
    stream.pipe(bufferStream);
    stream.write(expectedFile);
    stream.end();
  });

  it('should glob a file with default settings', function(done) {
    var expectedPath = path.join(__dirname, "./fixtures/test.coffee");
    var expectedContent = fs.readFileSync(expectedPath);

    var onEnd = function(){
      buffered.length.should.equal(1);
      should.exist(buffered[0].stat);
      buffered[0].path.should.equal(expectedPath);
      buffered[0].isBuffer().should.equal(true);
      bufEqual(buffered[0].contents, expectedContent).should.equal(true);
      done();
    };

    var stream = vfs.src("./fixtures/*.coffee", {cwd: __dirname});

    var buffered = [];
    bufferStream = through.obj(dataWrap(buffered.push.bind(buffered)), onEnd);
    stream.pipe(bufferStream);
  });

  it('should glob a file with default settings and relative cwd', function(done) {
    var expectedPath = path.join(__dirname, "./fixtures/test.coffee");
    var expectedContent = fs.readFileSync(expectedPath);

    var onEnd = function(){
      buffered.length.should.equal(1);
      should.exist(buffered[0].stat);
      buffered[0].path.should.equal(expectedPath);
      buffered[0].isBuffer().should.equal(true);
      bufEqual(buffered[0].contents, expectedContent).should.equal(true);
      done();
    };

    var stream = vfs.src("./fixtures/*.coffee", {cwd: path.relative(process.cwd(), __dirname)});

    var buffered = [];
    bufferStream = through.obj(dataWrap(buffered.push.bind(buffered)), onEnd);
    stream.pipe(bufferStream);
  });

  it('should glob a directory with default settings', function(done) {
    var expectedPath = path.join(__dirname, "./fixtures/wow");

    var onEnd = function(){
      buffered.length.should.equal(1);
      buffered[0].path.should.equal(expectedPath);
      buffered[0].isNull().should.equal(true);
      buffered[0].isDirectory().should.equal(true);
      done();
    };

    var stream = vfs.src("./fixtures/wow/", {cwd: __dirname});

    var buffered = [];
    bufferStream = through.obj(dataWrap(buffered.push.bind(buffered)), onEnd);
    stream.pipe(bufferStream);
  });

  it('should glob a file with with no contents', function(done) {
    var expectedPath = path.join(__dirname, "./fixtures/test.coffee");
    var expectedContent = fs.readFileSync(expectedPath);

    var onEnd = function(){
      buffered.length.should.equal(1);
      buffered[0].path.should.equal(expectedPath);
      buffered[0].isNull().should.equal(true);
      should.not.exist(buffered[0].contents);
      done();
    };

    var stream = vfs.src("./fixtures/*.coffee", {cwd: __dirname, read: false});

    var buffered = [];
    bufferStream = through.obj(dataWrap(buffered.push.bind(buffered)), onEnd);
    stream.pipe(bufferStream);
  });

  it('should glob a file with streaming contents', function(done) {
    var expectedPath = path.join(__dirname, "./fixtures/test.coffee");
    var expectedContent = fs.readFileSync(expectedPath);

    var onEnd = function(){
      buffered.length.should.equal(1);
      should.exist(buffered[0].stat);
      buffered[0].path.should.equal(expectedPath);
      buffered[0].isStream().should.equal(true);

      var contentBuffer = new Buffer([]);
      var contentBufferStream = through(dataWrap(function(data){
        contentBuffer = Buffer.concat([contentBuffer, data]);
      }));
      buffered[0].contents.pipe(contentBufferStream);
      buffered[0].contents.once('end', function(){
        bufEqual(contentBuffer, expectedContent);
        done();
      });
    };

    var stream = vfs.src("./fixtures/*.coffee", {cwd: __dirname, buffer: false});
    
    var buffered = [];
    bufferStream = through.obj(dataWrap(buffered.push.bind(buffered)), onEnd);
    stream.pipe(bufferStream);
  });

});
