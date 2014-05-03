var glob2base = require('../');
var glob = require('glob');
var path = require('path');
var should = require('should');
require('mocha');

describe('glob2base', function() {
  it('should get a base name', function() {
    var globber = new glob.Glob("js/*.js", {cwd: __dirname});
    glob2base(globber).should.equal("js/");
  });

  it('should get a base name from a nested glob', function() {
    var globber = new glob.Glob("js/**/test/*.js", {cwd: __dirname});
    glob2base(globber).should.equal("js/");
  });

  it('should get a base name from a flat file', function() {
    var globber = new glob.Glob("js/test/wow.js", {cwd: __dirname});
    glob2base(globber).should.equal("js/test/");
  });

  it('should get a base name from character class pattern', function() {
    var globber = new glob.Glob("js/t[a-z]st}/*.js", {cwd: __dirname});
    glob2base(globber).should.equal("js/");
  });

  it('should get a base name from brace , expansion', function() {
    var globber = new glob.Glob("js/{src,test}/*.js", {cwd: __dirname});
    glob2base(globber).should.equal("js/");
  });

  it('should get a base name from brace .. expansion', function() {
    var globber = new glob.Glob("js/test{0..9}/*.js", {cwd: __dirname});
    glob2base(globber).should.equal("js/");
  });

  it('should get a base name from extglob', function() {
    var globber = new glob.Glob("js/t+(wo|est)/*.js", {cwd: __dirname});
    glob2base(globber).should.equal("js/");
  });

  it('should get a base name from a complex brace glob', function() {
    var globber = new glob.Glob("lib/{components,pages}/**/{test,another}/*.txt", {cwd: __dirname});
    glob2base(globber).should.equal("lib/components/");

    var globber2 = new glob.Glob('js/test/**/{images,components}/*.js', {cwd: __dirname});
    glob2base(globber2).should.equal("js/test/");
  });
});
