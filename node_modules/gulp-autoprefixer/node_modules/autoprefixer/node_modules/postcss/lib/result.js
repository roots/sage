(function() {
  var Result;

  Result = (function() {
    function Result(css, map) {
      this.css = css;
      if (map) {
        this.map = map;
      }
    }

    Result.prototype.toString = function() {
      return this.css;
    };

    return Result;

  })();

  module.exports = Result;

}).call(this);
