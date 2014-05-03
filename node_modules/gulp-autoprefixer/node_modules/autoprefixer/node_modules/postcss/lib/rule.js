(function() {
  var Container, Declaration, Rule, list,
    __hasProp = {}.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

  Container = require('./container');

  Declaration = require('./declaration');

  list = require('./list');

  Rule = (function(_super) {
    __extends(Rule, _super);

    function Rule() {
      this.type = 'rule';
      Rule.__super__.constructor.apply(this, arguments);
    }

    Rule.prototype.styleType = function() {
      return this.type + (this.decls.length ? '-body' : '-empty');
    };

    Rule.prototype.defaultStyle = function(type) {
      if (type === 'rule-body') {
        return {
          between: ' ',
          after: this.defaultAfter()
        };
      } else {
        return {
          between: ' ',
          after: ''
        };
      }
    };

    Rule.raw('selector');

    Rule.prop('selectors', {
      get: function() {
        return list.comma(this.selector);
      },
      set: function(values) {
        return this.selector = values.join(', ');
      }
    });

    Rule.prototype.stringify = function(builder) {
      return this.stringifyBlock(builder, this._selector + this.style().between + '{');
    };

    return Rule;

  })(Container.WithDecls);

  module.exports = Rule;

}).call(this);
