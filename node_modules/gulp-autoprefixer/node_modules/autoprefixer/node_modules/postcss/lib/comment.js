(function() {
  var Comment, Node,
    __hasProp = {}.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

  Node = require('./node');

  Comment = (function(_super) {
    __extends(Comment, _super);

    function Comment() {
      this.type = 'comment';
      Comment.__super__.constructor.apply(this, arguments);
    }

    Comment.prototype.defaultStyle = function() {
      return {
        left: ' ',
        right: ' '
      };
    };

    Comment.prototype.stringify = function(builder) {
      var style;
      if (this.before) {
        builder(this.before);
      }
      style = this.style();
      return builder("/*" + (style.left + this.text + style.right) + "*/", this);
    };

    return Comment;

  })(Node);

  module.exports = Comment;

}).call(this);
