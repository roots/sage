var util = require('../');
var should = require('should');
var path = require('path');
require('mocha');

describe('File()', function() {
  it('should return a valid file', function(done) {
    var fname = path.join(__dirname, './fixtures/test.coffee');
    var base = path.join(__dirname, './fixtures/');
    var file = new util.File({
      base: base,
      cwd: __dirname,
      path: fname
    });
    should.exist(file, 'root');
    should.exist(file.relative, 'relative');
    should.exist(file.path, 'path');
    should.exist(file.cwd, 'cwd');
    should.exist(file.base, 'base');
    file.path.should.equal(fname);
    file.cwd.should.equal(__dirname);
    file.base.should.equal(base);
    file.relative.should.equal('test.coffee');
    done();
  });

  it('should return a valid file 2', function(done) {
    var fname = path.join(__dirname, './fixtures/test.coffee');
    var base = __dirname;
    var file = new util.File({
      base: base,
      cwd: __dirname,
      path: fname
    });
    should.exist(file, 'root');
    should.exist(file.relative, 'relative');
    should.exist(file.path, 'path');
    should.exist(file.cwd, 'cwd');
    should.exist(file.base, 'base');
    file.path.should.equal(fname);
    file.cwd.should.equal(__dirname);
    file.base.should.equal(base);
    file.relative.should.equal('fixtures/test.coffee');
    done();
  });
});