(function() {
  var AtRule, Comment, Container, Declaration, MapGenerator, Root, Rule,
    __hasProp = {}.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

  MapGenerator = require('./map-generator');

  Declaration = require('./declaration');

  Container = require('./container');

  Comment = require('./comment');

  AtRule = require('./at-rule');

  Rule = require('./rule');

  Root = (function(_super) {
    __extends(Root, _super);

    function Root() {
      this.type = 'root';
      this.rules = [];
      Root.__super__.constructor.apply(this, arguments);
    }

    Root.prototype.normalize = function(child, sample, type) {
      child = Root.__super__.normalize.apply(this, arguments);
      if (type === 'prepend') {
        sample.before = this.rules.length > 1 ? this.rules[1].before : this.after;
      }
      return child;
    };

    Root.prototype.stringify = function(builder) {
      this.stringifyContent(builder);
      if (this.after) {
        return builder(this.after);
      }
    };

    Root.prototype.toResult = function(opts) {
      var map;
      if (opts == null) {
        opts = {};
      }
      map = new MapGenerator(this, opts);
      return map.getResult();
    };

    return Root;

  })(Container.WithRules);

  module.exports = Root;

}).call(this);
