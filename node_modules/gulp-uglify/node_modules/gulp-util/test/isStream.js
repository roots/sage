var util = require('../');
var should = require('should');
var path = require('path');
var through = require('through2');
require('mocha');

describe('isStream()', function(){
  it('should work on a stream', function(done){
    util.isStream(through()).should.equal(true);
    done();
  });
  it('should not work on a buffer', function(done){
    util.isStream(new Buffer('huh')).should.equal(false);
    done();
  });
});