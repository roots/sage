var util = require('../');
var should = require('should');
var path = require('path');
require('mocha');

describe('template()', function(){
  it('should work with just a template', function(done){
    var opt = {
      name:'todd',
      file: {
        path: 'hi.js'
      }
    };
    var expected = 'test todd hi.js';

    var tmpl = util.template('test <%= name %> <%= file.path %>');
    should.exist(tmpl);
    'function'.should.equal(typeof(tmpl));

    // eval it now
    var etmpl = tmpl(opt);
    'string'.should.equal(typeof(etmpl));
    etmpl.should.equal(expected);
    done();
  });

  it('should work with a template and data', function(done){
    var opt = {
      name:'todd',
      file: {
        path: 'hi.js'
      }
    };
    var expected = 'test todd hi.js';
    var tmpl = util.template('test <%= name %> <%= file.path %>', opt);
    should.exist(tmpl);
    'string'.should.equal(typeof(tmpl));
    tmpl.should.equal(expected);
    done();
  });

  it('should throw an error when no file object is passed', function(done){
    var opt = {
      name:'todd'
    };
    try {
      var tmpl = util.template('test <%= name %> <%= file.path %>', opt);
    } catch (err) {
      should.exist(err);
      done();
    }
  });

  it('should ignore modified templateSettings', function(done){
    var templateSettings = require('lodash.templatesettings');
    templateSettings.interpolate = /\{\{([\s\S]+?)\}\}/g;

    var opt = {
      name:'todd',
      file: {
        path: 'hi.js'
      }
    };
    var expected = 'test {{name}} hi.js';

    var tmpl = util.template('test {{name}} <%= file.path %>');
    should.exist(tmpl);
    'function'.should.equal(typeof(tmpl));

    // eval it now
    var etmpl = tmpl(opt);
    'string'.should.equal(typeof(etmpl));
    etmpl.should.equal(expected);

    done();
  });

  it('should allow ES6 delimiters', function(done){
    var opt = {
      name:'todd',
      file: {
        path: 'hi.js'
      }
    };
    var expected = 'test todd hi.js';

    var tmpl = util.template('test ${name} ${file.path}');
    should.exist(tmpl);
    'function'.should.equal(typeof(tmpl));

    // eval it now
    var etmpl = tmpl(opt);
    'string'.should.equal(typeof(etmpl));
    etmpl.should.equal(expected);

    done();
  });

});