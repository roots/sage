var util = require('../');
var should = require('should');
var path = require('path');
require('mocha');

describe('colors', function(){
  it('should be a chalk instance', function(done){
    util.colors.should.equal(require('chalk'));
    done();
  });
});