(function() {
  var AtRule, Container, name, _fn, _i, _len, _ref,
    __hasProp = {}.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

  Container = require('./container');

  AtRule = (function(_super) {
    __extends(AtRule, _super);

    function AtRule() {
      this.type = 'atrule';
      AtRule.__super__.constructor.apply(this, arguments);
    }

    AtRule.prototype.styleType = function() {
      return this.type + ((this.rules != null) || (this.decls != null) ? '-body' : '-bodiless');
    };

    AtRule.prototype.defaultStyle = function(type) {
      if (type === 'atrule-body') {
        return {
          between: ' ',
          after: this.defaultAfter()
        };
      } else {
        return {
          between: ''
        };
      }
    };

    AtRule.prototype.addMixin = function(type) {
      var container, detector, mixin, name, value, _ref;
      mixin = type === 'rules' ? Container.WithRules : Container.WithDecls;
      if (!mixin) {
        return;
      }
      _ref = mixin.prototype;
      for (name in _ref) {
        value = _ref[name];
        if (name === 'constructor') {
          continue;
        }
        container = Container.prototype[name] === value;
        detector = name === 'append' || name === 'prepend';
        if (container && !detector) {
          continue;
        }
        this[name] = value;
      }
      return mixin.apply(this);
    };

    AtRule.raw('params');

    AtRule.prototype.stringify = function(builder, last) {
      var name, params, semicolon, style;
      style = this.style();
      name = '@' + this.name;
      params = this._params ? this._params.toString() : '';
      name += this.afterName != null ? this.afterName : params ? ' ' : '';
      if ((this.rules != null) || (this.decls != null)) {
        return this.stringifyBlock(builder, name + params + style.between + '{');
      } else {
        if (this.before) {
          builder(this.before);
        }
        semicolon = !last || this.semicolon ? ';' : '';
        return builder(name + params + style.between + semicolon, this);
      }
    };

    return AtRule;

  })(Container);

  _ref = ['append', 'prepend'];
  _fn = function(name) {
    return AtRule.prototype[name] = function(child) {
      var mixin;
      mixin = child.type === 'decl' ? 'decls' : 'rules';
      this.addMixin(mixin);
      return this[name](child);
    };
  };
  for (_i = 0, _len = _ref.length; _i < _len; _i++) {
    name = _ref[_i];
    _fn(name);
  }

  module.exports = AtRule;

}).call(this);
