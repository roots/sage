var util = require('../');
var should = require('should');
var path = require('path');
require('mocha');

describe('env', function(){
  it('should exist', function(done){
    should.exist(util.env);
    should.exist(util.env._);
    done();
  });
});