(function() {
  var vendor;

  vendor = {
    prefix: function(prop) {
      var separator;
      if (prop[0] === '-') {
        separator = prop.indexOf('-', 1) + 1;
        return prop.slice(0, separator);
      } else {
        return '';
      }
    },
    unprefixed: function(prop) {
      var separator;
      if (prop[0] === '-') {
        separator = prop.indexOf('-', 1) + 1;
        return prop.slice(separator);
      } else {
        return prop;
      }
    }
  };

  module.exports = vendor;

}).call(this);
