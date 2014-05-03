(function() {
  var list;

  list = {
    split: function(string, separators, last) {
      var array, current, escape, func, letter, quote, separator, split, _i, _j, _len, _len1;
      array = [];
      current = '';
      split = false;
      func = 0;
      quote = false;
      escape = false;
      for (_i = 0, _len = string.length; _i < _len; _i++) {
        letter = string[_i];
        if (quote) {
          if (escape) {
            escape = false;
          } else if (letter === '\\') {
            escape = true;
          } else if (letter === quote) {
            quote = false;
          }
        } else if (letter === '"' || letter === "'") {
          quote = letter;
        } else if (letter === '(') {
          func += 1;
        } else if (letter === ')') {
          if (func > 0) {
            func -= 1;
          }
        } else if (func === 0) {
          for (_j = 0, _len1 = separators.length; _j < _len1; _j++) {
            separator = separators[_j];
            if (letter === separator) {
              split = true;
            }
          }
        }
        if (split) {
          if (current !== '') {
            array.push(current.trim());
          }
          current = '';
          split = false;
        } else {
          current += letter;
        }
      }
      if (last || current !== '') {
        array.push(current.trim());
      }
      return array;
    },
    space: function(string) {
      return this.split(string, [' ', "\n", "\t"]);
    },
    comma: function(string) {
      return this.split(string, [','], true);
    }
  };

  module.exports = list;

}).call(this);
