'use strict';

describe('EventEmitter', function tests() {
  var EventEmitter = require('../').EventEmitter
    , chai = require('chai')
    , expect = chai.expect;

  chai.Assertion.includeStack = true;

  describe('EventEmitter#emit', function () {
    it('should return false when there are not events to emit', function () {
      var e = new EventEmitter();

      expect(e.emit('foo')).to.equal(false);
      expect(e.emit('bar')).to.equal(false);
    });

    it('emits with context', function (done) {
      var e = new EventEmitter()
        , context = 'bar';

      e.on('foo', function (bar) {
        expect(bar).to.equal('bar');
        expect(this).to.equal(context);

        done();
      }, context).emit('foo', 'bar');
    });

    it('should return true when there are events to emit', function (done) {
      var e = new EventEmitter();

      e.on('foo', function () {
        process.nextTick(done);
      });

      expect(e.emit('foo')).to.equal(true);
      expect(e.emit('foob')).to.equal(false);
    });

    it('receives the emitted events', function (done) {
      var e = new EventEmitter();

      e.on('data', function (a, b, c, d, undef) {
        expect(a).to.equal('foo');
        expect(b).to.equal(e);
        expect(c).to.be.instanceOf(Date);
        expect(undef).to.equal(undefined);
        expect(arguments.length).to.equal(3);

        done();
      });

      e.emit('data', 'foo', e, new Date());
    });

    it('emits to all event listeners', function () {
      var e = new EventEmitter()
        , pattern = [];

      e.on('foo', function () {
        pattern.push('foo1');
      });

      e.on('foo', function () {
        pattern.push('foo2');
      });

      e.emit('foo');
      expect(pattern.join(';')).to.equal('foo1;foo2');
    });
  });

  describe('EventEmitter#listeners', function () {
    it('returns an empty array if no listeners are specified', function () {
      var e = new EventEmitter();

      expect(e.listeners('foo')).to.be.a('array');
      expect(e.listeners('foo').length).to.equal(0);
    });

    it('returns an array of function', function () {
       var e = new EventEmitter();

       function foo() {}

       e.on('foo', foo);
       expect(e.listeners('foo')).to.be.a('array');
       expect(e.listeners('foo').length).to.equal(1);
       expect(e.listeners('foo')).to.deep.equal([foo]);
    });

    it('is not vulnerable to modifications', function () {
      var e = new EventEmitter();

      function foo() {}

      e.on('foo', foo);

      expect(e.listeners('foo')).to.deep.equal([foo]);
      e.listeners('foo').length = 0;
      expect(e.listeners('foo')).to.deep.equal([foo]);
    });
  });

  describe('EventEmitter#once', function () {
    it('only emits it once', function () {
      var e = new EventEmitter()
        , calls = 0;

      e.once('foo', function () {
        calls++;
      });

      e.emit('foo');
      e.emit('foo');
      e.emit('foo');
      e.emit('foo');
      e.emit('foo');

      expect(e.listeners('foo').length).to.equal(0);
      expect(calls).to.equal(1);
    });

    it('only emits once if emits are nested inside the listener', function () {
      var e = new EventEmitter()
        , calls = 0;

      e.once('foo', function () {
        calls++;
        e.emit('foo');
      });

      e.emit('foo');
      expect(e.listeners('foo').length).to.equal(0);
      expect(calls).to.equal(1);
    });

    it('only emits once for multiple events', function () {
      var e = new EventEmitter()
        , multi = 0
        , foo = 0
        , bar = 0;

      e.once('foo', function () {
        foo++;
      });

      e.once('foo', function () {
        bar++;
      });

      e.on('foo', function () {
        multi++;
      });

      e.emit('foo');
      e.emit('foo');
      e.emit('foo');
      e.emit('foo');
      e.emit('foo');

      expect(e.listeners('foo').length).to.equal(1);
      expect(multi).to.equal(5);
      expect(foo).to.equal(1);
      expect(bar).to.equal(1);
    });

    it('only emits once with context', function (done) {
      var e = new EventEmitter()
        , context = 'foo';

      e.once('foo', function (bar) {
        expect(this).to.equal(context);
        expect(bar).to.equal('bar');

        done();
      }, context).emit('foo', 'bar');
    });
  });

  describe('EventEmitter#removeListener', function () {
    it('should only remove the event with the specified function', function () {
      var e = new EventEmitter();

      function bar() {}
      e.on('foo', function () {});
      e.on('bar', function () {});
      e.on('bar', bar);

      expect(e.removeListener('foo', bar)).to.equal(e);
      expect(e.listeners('foo').length).to.equal(1);
      expect(e.listeners('bar').length).to.equal(2);

      expect(e.removeListener('foo')).to.equal(e);
      expect(e.listeners('foo').length).to.equal(0);
      expect(e.listeners('bar').length).to.equal(2);

      expect(e.removeListener('bar', bar)).to.equal(e);
      expect(e.listeners('bar').length).to.equal(1);
      expect(e.removeListener('bar')).to.equal(e);
      expect(e.listeners('bar').length).to.equal(0);
    });
  });

  describe('EventEmitter#removeAllListeners', function () {
    it('removes all events for the specified events', function () {
      var e = new EventEmitter();

      e.on('foo', function () { throw new Error('oops'); });
      e.on('foo', function () { throw new Error('oops'); });
      e.on('bar', function () { throw new Error('oops'); });
      e.on('aaa', function () { throw new Error('oops'); });

      expect(e.removeAllListeners('foo')).to.equal(e);
      expect(e.listeners('foo').length).to.equal(0);
      expect(e.listeners('bar').length).to.equal(1);
      expect(e.listeners('aaa').length).to.equal(1);

      expect(e.removeAllListeners('bar')).to.equal(e);
      expect(e.removeAllListeners('aaa')).to.equal(e);

      expect(e.emit('foo')).to.equal(false);
      expect(e.emit('bar')).to.equal(false);
      expect(e.emit('aaa')).to.equal(false);
    });

    it('just nukes the fuck out of everything', function () {
      var e = new EventEmitter();

      e.on('foo', function () { throw new Error('oops'); });
      e.on('foo', function () { throw new Error('oops'); });
      e.on('bar', function () { throw new Error('oops'); });
      e.on('aaa', function () { throw new Error('oops'); });

      expect(e.removeAllListeners()).to.equal(e);
      expect(e.listeners('foo').length).to.equal(0);
      expect(e.listeners('bar').length).to.equal(0);
      expect(e.listeners('aaa').length).to.equal(0);

      expect(e.emit('foo')).to.equal(false);
      expect(e.emit('bar')).to.equal(false);
      expect(e.emit('aaa')).to.equal(false);
    });
  });

  it('inherits when used with require(util).inherits', function () {
    function Beast() {
      /* rawr, i'm a beast */
    }

    require('util').inherits(Beast, EventEmitter);

    var moop = new Beast()
      , meap = new Beast();

    expect(moop).to.be.instanceOf(Beast);
    expect(moop).to.be.instanceof(EventEmitter);

    moop.on('data', function () {
      throw new Error('I should not emit');
    });

    meap.emit('data', 'rawr');
    meap.removeListener('foo');
    meap.removeAllListeners();
  });
});
