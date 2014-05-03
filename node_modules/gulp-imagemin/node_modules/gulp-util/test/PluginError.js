var util = require('../');
var should = require('should');
var path = require('path');
require('mocha');

describe('PluginError()', function(){
  it('should print the plugin name in toString', function(done){
    var err = new util.PluginError('test', 'something broke');
    err.toString().indexOf('test').should.not.equal(-1);
    done();
  });

  it('should not include the stack by default in toString', function(done){
    var err = new util.PluginError('test', 'something broke');
    // just check that there are no 'at' lines
    err.toString().indexOf('at').should.equal(-1);
    done();
  });

  it('should include the stack when specified in toString', function(done){
    var err = new util.PluginError('test', 'something broke', {stack: "at huh", showStack: true});
    // just check that there are 'at' lines
    err.toString().indexOf('at').should.not.equal(-1);
    done();
  });

  it('should take arguments as one object', function(done){
    var err = new util.PluginError({
      plugin: 'test',
      message: 'something broke'
    });
    err.plugin.should.equal('test');
    err.message.should.equal('something broke');
    done();
  });

  it('should take arguments as plugin name and one object', function(done){
    var err = new util.PluginError('test', {
      message: 'something broke'
    });
    err.plugin.should.equal('test');
    err.message.should.equal('something broke');
    done();
  });

  it('should take arguments as plugin name and message', function(done){
    var err = new util.PluginError('test', 'something broke');
    err.plugin.should.equal('test');
    err.message.should.equal('something broke');
    done();
  });

  it('should take arguments as plugin name, message, and one object', function(done){
    var err = new util.PluginError('test', 'something broke', {showStack: true});
    err.plugin.should.equal('test');
    err.message.should.equal('something broke');
    err.showStack.should.equal(true);
    done();
  });

  it('should take arguments as plugin name, error, and one object', function(done){
    var realErr = new Error('something broke');
    var err = new util.PluginError('test', realErr, {showStack: true});
    err.plugin.should.equal('test');
    err.message.should.equal('something broke');
    err.showStack.should.equal(true);
    done();
  });

});