(function() {
  var Processor, Value, utils, vendor;

  vendor = require('postcss/lib/vendor');

  Value = require('./value');

  utils = require('./utils');

  Processor = (function() {
    function Processor(prefixes) {
      this.prefixes = prefixes;
    }

    Processor.prototype.add = function(css) {
      var prefixer, selector, _i, _len, _ref;
      prefixer = this.prefixes.add['@keyframes'];
      if (prefixer) {
        css.eachAtRule(function(rule) {
          return prefixer.process(rule);
        });
      }
      _ref = this.prefixes.add.selectors;
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        selector = _ref[_i];
        css.eachRule(function(rule) {
          return selector.process(rule);
        });
      }
      css.eachDecl((function(_this) {
        return function(decl) {
          var prefix;
          prefix = _this.prefixes.add[decl.prop];
          if (prefix && prefix.prefixes) {
            return prefix.process(decl);
          }
        };
      })(this));
      return css.eachDecl((function(_this) {
        return function(decl) {
          var unprefixed, value, _j, _len1, _ref1;
          unprefixed = _this.prefixes.unprefixed(decl.prop);
          _ref1 = _this.prefixes.values('add', unprefixed);
          for (_j = 0, _len1 = _ref1.length; _j < _len1; _j++) {
            value = _ref1[_j];
            value.process(decl);
          }
          return Value.save(_this.prefixes, decl);
        };
      })(this));
    };

    Processor.prototype.remove = function(css) {
      var checker, _i, _len, _ref;
      css.eachAtRule((function(_this) {
        return function(rule, i) {
          if (_this.prefixes.remove['@' + rule.name]) {
            return rule.parent.remove(i);
          }
        };
      })(this));
      _ref = this.prefixes.remove.selectors;
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        checker = _ref[_i];
        css.eachRule((function(_this) {
          return function(rule, i) {
            if (checker.check(rule)) {
              return rule.parent.remove(i);
            }
          };
        })(this));
      }
      return css.eachDecl((function(_this) {
        return function(decl, i) {
          var notHack, rule, unprefixed, _j, _len1, _ref1, _ref2;
          rule = decl.parent;
          unprefixed = _this.prefixes.unprefixed(decl.prop);
          if ((_ref1 = _this.prefixes.remove[decl.prop]) != null ? _ref1.remove : void 0) {
            notHack = _this.prefixes.group(decl).down(function(i) {
              return i.prop === unprefixed;
            });
            if (notHack) {
              rule.remove(i);
              return;
            }
          }
          _ref2 = _this.prefixes.values('remove', unprefixed);
          for (_j = 0, _len1 = _ref2.length; _j < _len1; _j++) {
            checker = _ref2[_j];
            if (checker.check(decl.value)) {
              rule.remove(i);
              return;
            }
          }
        };
      })(this));
    };

    return Processor;

  })();

  module.exports = Processor;

}).call(this);
