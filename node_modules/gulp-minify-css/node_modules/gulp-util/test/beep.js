var util = require('../');
var should = require('should');
var path = require('path');
require('mocha');

describe('beep()', function(){
  it('should send the right code to stdout', function(done){
    var writtenValue;

    // Stub process.stdout.write
    var stdout_write = process.stdout.write;
    process.stdout.write = function(value) {
      writtenValue = value;
    };

    util.beep();
    writtenValue.should.equal('\x07');

    // Restore process.stdout.write
    process.stdout.write = stdout_write;
    done();
  });
});