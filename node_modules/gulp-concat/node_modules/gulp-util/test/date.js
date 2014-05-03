var util = require('../');
var should = require('should');
var path = require('path');
require('mocha');

describe('date', function() {
  it('should be a date format instance', function(done) {
    util.date.should.equal(require('dateformat'));
    done();
  });
  it('should return today\'s date', function(done) {
    var time = new Date();
    var dateutil = util.date('HH:MM:ss');
    dateutil.should.equal(('0' + time.getHours()).slice(-2) + ':' + ('0' + time.getMinutes()).slice(-2) + ':' + ('0' + time.getSeconds()).slice(-2));
    done();
  })
});