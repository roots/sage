var util = require('../');
var should = require('should');
var path = require('path');
require('mocha');

describe('log()', function(){
  it('should work i guess', function(done){
    var writtenValue;

    // Stub process.stdout.write
    var stdout_write = process.stdout.write;
    process.stdout.write = function(value) {
      writtenValue = value;
    };

    util.log(1, 2, 3, 4, 'five');
    writtenValue.should.eql('['+util.colors.green('gulp')+'] 1 2 3 4 five\n');

    // Restore process.stdout.write
    process.stdout.write = stdout_write;
    done();
  });
});