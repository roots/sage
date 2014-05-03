var path   = require('path'),
    assert = require('assert'),
    fs     = require('fs'),
    exec   = require('child_process').exec,
    cli    = process.env.NODESASS_COVERAGE ? require('../lib-coverage/cli') : require('../lib/cli'),

    cliPath = path.resolve(__dirname, '../bin/node-sass'),
    sampleFilename = path.resolve(__dirname, 'sample.scss');

var expectedSampleCompressed = '#navbar{width:80%;height:23px;}\
#navbar ul{list-style-type:none;}\
#navbar li{float:left;}\
#navbar li a{font-weight:bold;}';

var expectedSampleNoComments = '#navbar {\n\
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

var expectedSampleCustomImagePath = 'body {\n\
  background-image: url("/path/to/images/image.png"); }\n';

describe('cli', function() {
  it('should print help when run with no arguments', function(done) {
    exec('node ' + cliPath, function(err, stdout, stderr) {
      done(assert(stderr.indexOf('Compile .scss files with node-sass') === 0));
    });
  });

  it('should compile sample.scss as sample.css', function(done) {
    var resultPath = path.join(__dirname, 'sample.css');

    exec('node ' + cliPath + ' ' + sampleFilename, {
      cwd: __dirname
    }, function() {

      fs.exists(resultPath, function(exists) {
        assert(exists);
        fs.unlink(resultPath, done);
      });
    });
  });

  it('should compile sample.scss to ../out.css', function(done) {
    var resultPath = path.resolve(__dirname, '../out.css');

    exec('node ' + cliPath + ' ' + sampleFilename + ' ../out.css', {
      cwd: __dirname
    }, function() {

      fs.exists(resultPath, function(exists) {
        assert(exists);
        fs.unlink(resultPath, done);
      });
    });
  });

  it('should compile with --include-path option', function(done){
    var emitter = cli([
      '--include-path', path.join(__dirname, 'lib'),
      '--include-path', path.join(__dirname, 'functions'),
      path.join(__dirname, 'include_path.scss')
    ]);
    emitter.on('error', done);
    emitter.on('write', function(err, file, css){
      assert.equal(css.trim(), 'body {\n  background: red;\n  color: #0000fe; }');
      fs.unlink(file, done);
    });
  });

  it('should compile with the --output-style', function(done){
    var emitter = cli(['--output-style', 'compressed', path.join(__dirname, 'sample.scss')]);
    emitter.on('error', done);
    emitter.on('write', function(err, file, css){
      assert.equal(css, expectedSampleCompressed);
      fs.unlink(file, done);
    });
  });

  it('should compile with the --source-comments option', function(done){
    var emitter = cli(['--source-comments', 'none', path.join(__dirname, 'sample.scss')]);
    emitter.on('error', done);
    emitter.on('write', function(err, file, css){
      assert.equal(css, expectedSampleNoComments);
      fs.unlink(file, done);
    });
  });

  it('should compile with the --image-path option', function(done){
    var emitter = cli(['--image-path', '/path/to/images', path.join(__dirname, 'image_path.scss')]);
    emitter.on('error', done);
    emitter.on('write', function(err, file, css){
      assert.equal(css, expectedSampleCustomImagePath);
      fs.unlink(file, done);
    });
  });

  it('should write the output to the file specified with the --output option', function(done){
    var resultPath = path.join(__dirname, '../output.css');
    var emitter = cli(['--output', resultPath, path.join(__dirname, 'sample.scss')]);
    emitter.on('error', done);
    emitter.on('write', function(){
      fs.exists(resultPath, function(exists) {
        assert(exists);
        fs.unlink(resultPath, done);
      });
    });
  });

  it('should compile with the --source-map option', function(done){
    var emitter = cli([path.join(__dirname, 'sample.scss'), '--source-map']);
    emitter.on('error', done);
    emitter.on('write-source-map', function(err, file) {
      assert.equal(file, path.join(__dirname, '../sample.css.map'));
      fs.exists(file, function(exists) {
        assert(exists);
      });
    });
    emitter.on('done', function() {
      fs.unlink(path.join(__dirname, '../sample.css.map'), function() {
        fs.unlink(path.join(__dirname, '../sample.css'), function() {
          done();
        });
      });
    });
  });

  it('should compile with the --source-map option with specific filename', function(done){
    var emitter = cli([path.join(__dirname, 'sample.scss'), '--source-map', path.join(__dirname, '../sample.map')]);
    emitter.on('error', done);
    emitter.on('write-source-map', function(err, file) {
      assert.equal(file, path.join(__dirname, '../sample.map'));
      fs.exists(file, function(exists) {
        assert(exists);
      });
    });
    emitter.on('done', function() {
      fs.unlink(path.join(__dirname, '../sample.map'), function() {
        fs.unlink(path.join(__dirname, '../sample.css'), function() {
          done();
        });
      });
    });
  });

  it('should compile a sourceMap if --source-comments="map", but the --source-map option is excluded', function(done){
    var emitter = cli([path.join(__dirname, 'sample.scss'), '--source-comments', 'map']);
    emitter.on('error', done);
    emitter.on('write-source-map', function(err, file) {
      assert.equal(file, path.join(__dirname, '../sample.css.map'));
      fs.exists(file, function(exists) {
        assert(exists);
      });
    });
    emitter.on('done', function() {
      fs.unlink(path.join(__dirname, '../sample.css.map'), function() {
        fs.unlink(path.join(__dirname, '../sample.css'), function() {
          done();
        });
      });
    });
  });

});
