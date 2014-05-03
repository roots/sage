var util = require('../');
var should = require('should');
var path = require('path');
var through = require('through2');
require('mocha');

describe('isNull()', function(){
  it('should work on a null', function(done){
    util.isNull(null).should.equal(true);
    done();
  });
  it('should not work on a stream', function(done){
    util.isNull(through()).should.equal(false);
    done();
  });
  it('should not work on undefined', function(done){
    util.isNull(undefined).should.equal(false);
    done();
  });
});
