(function() {
  var Raw;

  Raw = (function() {
    Raw.load = function(value, raw) {
      if ((raw != null) && value !== raw) {
        return new Raw(value, raw);
      } else {
        return value;
      }
    };

    function Raw(value, raw) {
      this.value = value;
      this.raw = raw;
    }

    Raw.prototype.toString = function() {
      if (this.changed) {
        return this.value || '';
      } else {
        return this.raw || this.value || '';
      }
    };

    return Raw;

  })();

  module.exports = Raw;

}).call(this);
