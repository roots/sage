
describe('ware', function () {

  var assert = require('assert');
  var noop = function(){};
  var ware = require('..');

  describe('#use', function () {
    it('should be chainable', function () {
      var w = ware();
      assert(w.use(noop) == w);
    });

    it('should add a middleware to fns', function () {
      var w = ware().use(noop);
      assert(1 == w.fns.length);
    });
  });

  describe('#run', function () {
    it('should receive an error', function (done) {
      var error = new Error();
      ware()
        .use(function (next) { next(error); })
        .run(function (err) {
          assert(err == error);
          done();
        });
    });

    it('should receive initial arguments', function (done) {
      ware()
        .use(function (req, res, next) { next(); })
        .run('req', 'res', function (err, req, res) {
          assert(!err);
          assert('req' == req);
          assert('res' == res);
          done();
        });
    });

    it('should take any number of arguments', function (done) {
      ware()
        .use(function (a, b, c, next) { next(); })
        .run('a', 'b', 'c', function (err, a, b, c) {
          assert(!err);
          assert('a' == a);
          assert('b' == b);
          assert('c' == c);
          done();
        });
    });

    it('should let middleware manipulate the same input objects', function (done) {
      ware()
        .use(function (obj, next) {
          obj.value = obj.value * 2;
          next();
        })
        .use(function (obj, next) {
          obj.value = obj.value.toString();
          next();
        })
        .run({ value: 21 }, function (err, obj) {
          assert('42' == obj.value);
          done();
        });
    });

    it('should skip non-error handlers on error', function (done) {
      ware()
        .use(function (next) { next(new Error()); })
        .use(function (next) { assert(false); })
        .run(function (err) {
          assert(err);
          done();
        });
    });

    it('should skip error handlers on no error', function (done) {
      ware()
        .use(function (next) { next(); })
        .use(function (err, next) { assert(false); })
        .run(function (err) {
          assert(!err);
          done();
        });
    });

    it('should call error middleware on error', function (done) {
      var errors = 0;
      ware()
        .use(function (next) { next(new Error()); })
        .use(function (err, next) { errors++; next(err); })
        .use(function (err, next) { errors++; next(err); })
        .run(function (err) {
          assert(err);
          assert(2 == errors);
          done();
        });
    });

    it('should not require a callback', function (done) {
      ware()
        .use(function (obj, next) { assert(obj); next(); })
        .use(function (obj, next) { done(); })
        .run('obj');
    });
  });
});