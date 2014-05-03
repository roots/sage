var util = require('../');
var should = require('should');
var path = require('path');
require('mocha');

describe('replaceExtension()', function() {
  it('should return a valid replaced extension on nested', function(done) {
    var fname = path.join(__dirname, './fixtures/test.coffee');
    var expected = path.join(__dirname, './fixtures/test.js');
    var nu = util.replaceExtension(fname, '.js');
    should.exist(nu);
    nu.should.equal(expected);
    done();
  });

  it('should return a valid replaced extension on flat', function(done) {
    var fname = 'test.coffee';
    var expected = 'test.js';
    var nu = util.replaceExtension(fname, '.js');
    should.exist(nu);
    nu.should.equal(expected);
    done();
  });

  it('should not return a valid replaced extension on empty string', function(done) {
    var fname = '';
    var expected = '';
    var nu = util.replaceExtension(fname, '.js');
    should.exist(nu);
    nu.should.equal(expected);
    done();
  });

});