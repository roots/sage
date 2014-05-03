var util = require('../');
var should = require('should');
var path = require('path');
var es = require('event-stream');
require('mocha');

describe('buffer()', function(){
  it('should buffer stuff and return an array into the callback', function(done){
    var src = [1,2,3];
    var inp = es.readArray(src);
    inp.pipe(util.buffer(function(err, data){
      should.not.exist(err);
      should.exist(data);
      data.should.eql(src);
      done();
    }));
  });
  it('should buffer stuff and emit it as a data event', function(done){
    var src = [1,2,3];
    var inp = es.readArray(src);
    inp.pipe(util.buffer()).on('data', function(data){
      should.exist(data);
      data.should.eql(src);
      done();
    });
  });
  it('should buffer stuff and return a stream with the buffered data', function(done){
    var src = [1,2,3];
    var inp = es.readArray(src);
    inp.pipe(util.buffer()).pipe(es.through(function(data) {
      should.exist(data);
      data.should.eql(src);
      done();
    }));
  });
});