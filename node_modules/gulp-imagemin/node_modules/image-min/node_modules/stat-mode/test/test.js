
var Mode = require('../');
var assert = require('assert');

describe('stat-mode', function () {

  it('should export the `Mode` constructor', function () {
    assert.equal('function', typeof Mode);
    assert.equal('Mode', Mode.name);
  });

  describe('Mode', function () {

    it('should return a `Mode` instance with `new`', function () {
      var m = new Mode({});
      assert(m instanceof Mode);
    });

    it('should return a `Mode` instance without `new`', function () {
      var m = Mode({});
      assert(m instanceof Mode);
    });

    it('should throw an Error if no `stat` object is passed in', function () {
      try {
        new Mode();
        assert(false, 'unreachable');
      } catch (e) {
        assert.equal('must pass in a "stat" object', e.message);
      }
    });

    describe('#toString', function () {
      it('should convert a mode to a unix string', function () {
        var m = new Mode({ mode: 33188 });
        assert.equal(m.toString(), '-rw-r--r--');
      });
    });

    describe('#toOctal', function () {
      it('should convert a mode to an octal string', function () {
        var m = new Mode({ mode: 33188 });
        assert.equal(m.toOctal(), '0644');
      });
    });

  });

});
