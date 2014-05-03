(function() {
  var Keyframes, Prefixer,
    __hasProp = {}.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

  Prefixer = require('./prefixer');

  Keyframes = (function(_super) {
    __extends(Keyframes, _super);

    function Keyframes() {
      return Keyframes.__super__.constructor.apply(this, arguments);
    }

    Keyframes.prototype.check = function(atRule) {
      return atRule.name === 'keyframes';
    };

    Keyframes.prototype.add = function(atRule, prefix) {
      var already, cloned, prefixed;
      prefixed = prefix + atRule.name;
      already = atRule.parent.some(function(i) {
        return i.name === prefixed && i.params === atRule.params;
      });
      if (already) {
        return;
      }
      cloned = this.clone(atRule, {
        name: prefixed
      });
      return atRule.parent.insertBefore(atRule, cloned);
    };

    return Keyframes;

  })(Prefixer);

  module.exports = Keyframes;

}).call(this);
