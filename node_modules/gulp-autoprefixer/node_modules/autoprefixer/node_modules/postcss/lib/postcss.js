(function() {
  var AtRule, Comment, Declaration, PostCSS, Root, Rule, postcss,
    __slice = [].slice;

  Declaration = require('./declaration');

  Comment = require('./comment');

  AtRule = require('./at-rule');

  Rule = require('./rule');

  Root = require('./root');

  PostCSS = (function() {
    function PostCSS(processors) {
      this.processors = processors != null ? processors : [];
    }

    PostCSS.prototype.use = function(processor) {
      this.processors.push(processor);
      return this;
    };

    PostCSS.prototype.process = function(css, opts) {
      var parsed, processor, returned, _i, _len, _ref;
      if (opts == null) {
        opts = {};
      }
      parsed = postcss.parse(css, opts);
      _ref = this.processors;
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        processor = _ref[_i];
        returned = processor(parsed);
        if (returned instanceof Root) {
          parsed = returned;
        }
      }
      return parsed.toResult(opts);
    };

    return PostCSS;

  })();

  postcss = function() {
    var processors;
    processors = 1 <= arguments.length ? __slice.call(arguments, 0) : [];
    return new PostCSS(processors);
  };

  postcss.parse = require('./parse');

  postcss.comment = function(defaults) {
    return new Comment(defaults);
  };

  postcss.atRule = function(defaults) {
    return new AtRule(defaults);
  };

  postcss.decl = function(defaults) {
    return new Declaration(defaults);
  };

  postcss.rule = function(defaults) {
    return new Rule(defaults);
  };

  postcss.root = function(defaults) {
    return new Root(defaults);
  };

  module.exports = postcss;

}).call(this);
