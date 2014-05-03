(function() {
  var Declaration, Node, vendor,
    __hasProp = {}.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

  Node = require('./node');

  vendor = require('./vendor');

  Declaration = (function(_super) {
    __extends(Declaration, _super);

    function Declaration() {
      this.type = 'decl';
      Declaration.__super__.constructor.apply(this, arguments);
    }

    Declaration.prototype.defaultStyle = function() {
      return {
        before: "\n    ",
        between: ': '
      };
    };

    Declaration.raw('value');

    Declaration.prop('important', {
      get: function() {
        return !!this._important;
      },
      set: function(value) {
        if (typeof value === 'string' && value !== '') {
          return this._important = value;
        } else if (value) {
          return this._important = ' !important';
        } else {
          return this._important = false;
        }
      }
    });

    Declaration.prototype.stringify = function(builder, semicolon) {
      var string, style;
      style = this.style();
      if (style.before) {
        builder(style.before);
      }
      string = this.prop + style.between + this._value.toString();
      string += this._important || '';
      if (semicolon) {
        string += ';';
      }
      return builder(string, this);
    };

    Declaration.prototype.clone = function(obj) {
      var cloned;
      cloned = Declaration.__super__.clone.apply(this, arguments);
      delete cloned.before;
      return cloned;
    };

    return Declaration;

  })(Node);

  module.exports = Declaration;

}).call(this);
