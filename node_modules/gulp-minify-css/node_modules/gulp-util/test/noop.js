var util = require('../');
var should = require('should');
var path = require('path');
var es = require('event-stream');
require('mocha');

describe('noop()', function(){
  it('should return a stream', function(done){
    util.isStream(util.noop()).should.equal(true);
    done();
  });
  it('should return a stream that passes through all data', function(done){
    var inp = [1,2,3,4,5,6,7,8,9];
    var stream = util.noop();
    es.readArray(inp)
      .pipe(stream)
      .pipe(es.writeArray(function(err, arr){
        should.not.exist(err);
        should.exist(arr);
        arr.should.eql(inp);
        done();
    }));
  });
});