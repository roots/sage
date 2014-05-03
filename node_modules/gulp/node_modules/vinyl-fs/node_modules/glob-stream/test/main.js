var gs = require('../');
var through = require('through');
var should = require('should');
require('mocha');
var path = require('path');
var join = path.join;
var sep = path.sep;

describe('glob-stream', function() {
  describe('create()', function() {
    it('should return a folder name stream from a glob', function(done) {
      var stream = gs.create("./fixtures/whatsgoingon", {cwd: __dirname});
      should.exist(stream);
      stream.on('error', function(err) {
        throw err;
      });
      stream.on('data', function(file) {
        should.exist(file);
        should.exist(file.path);
        should.exist(file.base);
        should.exist(file.cwd);
        String(file.cwd).should.equal(__dirname);
        String(file.base).should.equal(join(__dirname, "fixtures"+sep));
        String(join(file.path,'')).should.equal(join(__dirname, "./fixtures/whatsgoingon"));
        done();
      });
    });

    it('should return a file name stream from a glob', function(done) {
      var stream = gs.create("./fixtures/*.coffee", {cwd: __dirname});
      should.exist(stream);
      stream.on('error', function(err) {
        throw err;
      });
      stream.on('data', function(file) {
        should.exist(file);
        should.exist(file.path);
        should.exist(file.base);
        should.exist(file.cwd);
        String(file.cwd).should.equal(__dirname);
        String(file.base).should.equal(join(__dirname, "fixtures"+sep));
        String(join(file.path,'')).should.equal(join(__dirname, "./fixtures/test.coffee"));
        done();
      });
    });

    it('should return a file name stream from a glob and respect state', function(done) {
      var stream = gs.create("./fixtures/stuff/*.dmc", {cwd: __dirname});
      var wrapper = stream.pipe(through(function(data){
        this.pause();

        setTimeout(function(){
          this.queue(data);
          this.resume();
        }.bind(this), 500);
      }));

      var count = 0;

      should.exist(stream);
      stream.on('error', function(err) {
        throw err;
      });
      wrapper.on('data', function(file) {
        count++;
      });
      wrapper.on('end', function(){
        count.should.equal(2);
        done();
      });
    });

    it('should return a correctly ordered file name stream for two globs and specified base', function(done) {
      var baseDir = join(__dirname, "./fixtures");
      
      var globArray = [
        "./whatsgoingon/key/isaidhey/whatsgoingon/test.txt",
        "./test.coffee",
        "./whatsgoingon/test.js"
      ];
      var stream = gs.create(globArray, {cwd: baseDir, base: baseDir});

      var files = [];
      stream.on('error', done);
      stream.on('data', function(file) {
        should.exist(file);
        should.exist(file.base);
        file.base.should.equal(baseDir);
      });
      stream.on('end', function() {
        done();
      });
    });

    it('should return a correctly ordered file name stream for two globs and cwdbase', function(done) {
      var baseDir = join(__dirname, "./fixtures");
      
      var globArray = [
        "./whatsgoingon/key/isaidhey/whatsgoingon/test.txt",
        "./test.coffee",
        "./whatsgoingon/test.js"
      ];
      var stream = gs.create(globArray, {cwd: baseDir, cwdbase: true});

      var files = [];
      stream.on('error', done);
      stream.on('data', function(file) {
        should.exist(file);
        should.exist(file.base);
        file.base.should.equal(baseDir);
      });
      stream.on('end', function() {
        done();
      });
    });

    it('should return a file name stream that does not duplicate', function(done) {
      var stream = gs.create(["./fixtures/test.coffee", "./fixtures/test.coffee"], {cwd: __dirname});
      should.exist(stream);
      stream.on('error', function(err) {
        throw err;
      });
      stream.on('data', function(file) {
        should.exist(file);
        should.exist(file.path);
        should.exist(file.base);
        should.exist(file.cwd);
        String(file.cwd).should.equal(__dirname);
        String(file.base).should.equal(join(__dirname, "fixtures"+sep));
        String(file.path).should.equal(join(__dirname, "./fixtures/test.coffee"));
        done();
      });
    });

    it('should return a file name stream that does not duplicate when piped twice', function(done) {
      var stream = gs.create("./fixtures/test.coffee", {cwd: __dirname});
      var stream2 = gs.create("./fixtures/test.coffee", {cwd: __dirname});
      stream2.pipe(stream);

      should.exist(stream);
      stream.on('error', function(err) {
        throw err;
      });
      stream.on('data', function(file) {
        should.exist(file);
        should.exist(file.path);
        should.exist(file.base);
        should.exist(file.cwd);
        String(file.cwd).should.equal(__dirname);
        String(file.base).should.equal(join(__dirname, "fixtures"+sep));
        String(file.path).should.equal(join(__dirname, "./fixtures/test.coffee"));
        done();
      });
    });


    it('should return a file name stream from a direct path', function(done) {
      var stream = gs.create("./fixtures/test.coffee", {cwd: __dirname});
      should.exist(stream);
      stream.on('error', function(err) {
        throw err;
      });
      stream.on('data', function(file) {
        should.exist(file);
        should.exist(file.path);
        should.exist(file.base);
        should.exist(file.cwd);
        String(file.cwd).should.equal(__dirname);
        String(file.base).should.equal(join(__dirname, "fixtures"+sep));
        String(file.path).should.equal(join(__dirname, "./fixtures/test.coffee"));
        done();
      });
    });

    it('should return a file name stream from a direct path and pause/buffer items', function(done) {
      var stream = gs.create("./fixtures/test.coffee", {cwd: __dirname});
      should.exist(stream);
      stream.on('error', function(err) {
        throw err;
      });
      stream.on('data', function(file) {
        should.exist(file);
        should.exist(file.path);
        should.exist(file.base);
        should.exist(file.cwd);
        String(file.cwd).should.equal(__dirname);
        String(file.base).should.equal(join(__dirname, "fixtures"+sep));
        String(file.path).should.equal(join(__dirname, "./fixtures/test.coffee"));
        done();
      });
      stream.pause();
      setTimeout(function(){
        stream.resume();
      }, 1000);
    });

    it('should not fuck up direct paths with no cwd', function(done) {
      var stream = gs.create(join(__dirname, "./fixtures/test.coffee"));
      should.exist(stream);
      stream.on('error', function(err) {
        throw err;
      });
      stream.on('data', function(file) {
        should.exist(file);
        should.exist(file.path);
        should.exist(file.base);
        should.exist(file.cwd);
        String(file.cwd).should.equal(process.cwd());
        String(file.base).should.equal(join(__dirname, "./fixtures/"));
        String(join(file.path,'')).should.equal(join(__dirname, "./fixtures/test.coffee"));
        done();
      });
    });

    it('should return a correctly ordered file name stream for three globs with globstars', function(done) {
      var globArray = [
        join(__dirname, "./fixtures/**/test.txt"),
        join(__dirname, "./fixtures/**/test.coffee"),
        join(__dirname, "./fixtures/**/test.js")
      ];
      var stream = gs.create(globArray, {cwd: __dirname});

      var files = [];
      stream.on('error', done);
      stream.on('data', function(file) {
        should.exist(file);
        should.exist(file.path);
        files.push(file);
      });
      stream.on('end', function() {
        files.length.should.equal(3);
        path.basename(files[0].path).should.equal('test.txt');
        path.basename(files[1].path).should.equal('test.coffee');
        path.basename(files[2].path).should.equal('test.js');
        done();
      });
    });

    it('should return a correctly ordered file name stream for two globs', function(done) {
      var globArray = [
        join(__dirname, "./fixtures/whatsgoingon/hey/isaidhey/whatsgoingon/test.txt"),
        join(__dirname, "./fixtures/test.coffee"),
        join(__dirname, "./fixtures/whatsgoingon/test.js")
      ];
      var stream = gs.create(globArray, {cwd: __dirname});

      var files = [];
      stream.on('error', done);
      stream.on('data', function(file) {
        should.exist(file);
        should.exist(file.path);
        files.push(file);
      });
      stream.on('end', function() {
        files.length.should.equal(3);
        files[0].path.should.equal(globArray[0]);
        files[1].path.should.equal(globArray[1]);
        files[2].path.should.equal(globArray[2]);
        done();
      });
    });
    
    it('should return a correctly ordered file name stream for two globs and custom base', function(done) {
        var baseDir = join(__dirname, "./fixtures");
        
        var globArray = [
          "./whatsgoingon/key/isaidhey/whatsgoingon/test.txt",
          "./test.coffee",
          "./whatsgoingon/test.js"
        ];
        var stream = gs.create(globArray, {cwd: baseDir, cwdbase: true});

        var files = [];
        stream.on('error', done);
        stream.on('data', function(file) {
          should.exist(file);
          should.exist(file.base);
          file.base.should.equal(baseDir);
        });
        stream.on('end', function() {
          done();
        });
      });

    it('should return a input stream for multiple globs, with negation (globbing)', function(done) {
      var expectedPath = join(__dirname, "./fixtures/stuff/run.dmc");
      var globArray = [
        join(__dirname, "./fixtures/stuff/*.dmc"),
        '!' + join(__dirname, "./fixtures/stuff/test.dmc"),
      ];
      var stream = gs.create(globArray);

      var files = [];
      stream.on('error', done);
      stream.on('data', function(file) {
        should.exist(file);
        should.exist(file.path);
        files.push(file);
      });
      stream.on('end', function() {
        files.length.should.equal(1);
        files[0].path.should.equal(expectedPath);
        done();
      });
    });

    it('should return a input stream for multiple globs, with negation (direct)', function(done) {
      var expectedPath = join(__dirname, "./fixtures/stuff/run.dmc");
      var globArray = [
        join(__dirname, "./fixtures/stuff/run.dmc"),
        '!' + join(__dirname, "./fixtures/stuff/test.dmc"),
      ];
      var stream = gs.create(globArray);

      var files = [];
      stream.on('error', done);
      stream.on('data', function(file) {
        should.exist(file);
        should.exist(file.path);
        files.push(file);
      });
      stream.on('end', function() {
        files.length.should.equal(1);
        files[0].path.should.equal(expectedPath);
        done();
      });
    });

    it('should return a input stream that can be piped to other input streams and remove duplicates', function(done) {
      var stream = gs.create(join(__dirname, "./fixtures/stuff/*.dmc"));
      var stream2 = gs.create(join(__dirname, "./fixtures/stuff/*.dmc"));

      stream2.pipe(stream);

      var files = [];
      stream.on('error', done);
      stream.on('data', function(file) {
        should.exist(file);
        should.exist(file.path);
        files.push(file);
      });
      stream.on('end', function() {
        files.length.should.equal(2);
        done();
      });
    });

    it('should return a file name stream with negation from a glob', function(done) {
      var stream = gs.create(["./fixtures/**/*.js", "!./**/test.js"], {cwd: __dirname});
      should.exist(stream);
      stream.on('error', function(err) {
        throw err;
      });
      stream.on('data', function(file) {
        throw new Error("file "+file.path+" should have been negated");
      });
      stream.on('end', function() {
        done();
      });
    });

    it('should return a file name stream from two globs and a negative', function(done) {
      var stream = gs.create(["./fixtures/*.coffee", "./fixtures/whatsgoingon/*.coffee"], {cwd: __dirname});
      should.exist(stream);
      stream.on('error', function(err) {
        throw err;
      });
      stream.on('data', function(file) {
        should.exist(file);
        should.exist(file.path);
        should.exist(file.base);
        should.exist(file.cwd);
        String(file.cwd).should.equal(__dirname);
        String(file.base).should.equal(join(__dirname, "fixtures"+sep));
        String(join(file.path,'')).should.equal(join(__dirname, "./fixtures/test.coffee"));
        done();
      });
    });

  });
});
