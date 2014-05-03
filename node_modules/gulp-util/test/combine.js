var util = require('../');
var should = require('should');
var path = require('path');
var es = require('event-stream');
var Stream = require('stream');
require('mocha');

describe('combine()', function(){
  it('should return a function', function(done){
    var src = [1,2,3];
    var inp = es.readArray(src);
    var factory = util.combine(inp);
    factory.should.be.type('function');
    done();
  });
  it('should return a function that returns a stream combination', function(done){
    var src = [1,2,3];
    var inp = es.readArray(src);
    var inp2 = es.writeArray(function(err, data){
      should.not.exist(err);
      data.should.eql(src);
      done();
    });
    var factory = util.combine(inp, inp2);
    factory().should.be.instanceof(Stream);
  });
  it('should return a function that returns a stream combination when given an array', function(done){
    var src = [1,2,3];
    var inp = es.readArray(src);
    var inp2 = es.writeArray(function(err, data){
      should.not.exist(err);
      data.should.eql(src);
      done();
    });
    var factory = util.combine([inp, inp2]);
    factory().should.be.instanceof(Stream);
  });
});