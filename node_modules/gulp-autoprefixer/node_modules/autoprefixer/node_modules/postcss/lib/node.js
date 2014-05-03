(function() {
  var Node, Raw, clone, keys,
    __hasProp = {}.hasOwnProperty;

  Raw = require('./raw');

  clone = function(obj, parent) {
    var cloned, name, value;
    if (typeof obj !== 'object') {
      return obj;
    }
    cloned = new obj.constructor();
    for (name in obj) {
      if (!__hasProp.call(obj, name)) continue;
      value = obj[name];
      if (name === 'parent' && typeof value === 'object') {
        if (parent) {
          cloned[name] = parent;
        }
      } else if (value instanceof Array) {
        cloned[name] = value.map(function(i) {
          return clone(i, cloned);
        });
      } else {
        cloned[name] = clone(value, cloned);
      }
    }
    return cloned;
  };

  keys = function(obj, keys) {
    var all, key;
    all = {};
    for (key in keys) {
      if (obj[key] != null) {
        all[key] = obj[key];
      } else {
        return false;
      }
    }
    return all;
  };

  Node = (function() {
    function Node(defaults) {
      var name, value;
      if (defaults == null) {
        defaults = {};
      }
      for (name in defaults) {
        value = defaults[name];
        this[name] = value;
      }
    }

    Node.prop = function(name, params) {
      return Object.defineProperty(this.prototype, name, params);
    };

    Node.raw = function(name) {
      var hidden;
      hidden = '_' + name;
      return this.prop(name, {
        get: function() {
          var prop;
          prop = this[hidden];
          if (prop instanceof Raw) {
            return prop.value;
          } else {
            return prop;
          }
        },
        set: function(value) {
          if (value instanceof Raw) {
            return this[hidden] = value;
          } else {
            return this[hidden] = value;
          }
        }
      });
    };

    Node.prototype.removeSelf = function() {
      if (!this.parent) {
        return;
      }
      this.parent.remove(this);
      return this;
    };

    Node.prototype.toString = function() {
      var builder, result;
      result = '';
      builder = function(str) {
        return result += str;
      };
      this.stringify(builder);
      return result;
    };

    Node.prototype.clone = function(overrides) {
      var cloned, name, value;
      if (overrides == null) {
        overrides = {};
      }
      cloned = clone(this);
      for (name in overrides) {
        value = overrides[name];
        cloned[name] = value;
      }
      return cloned;
    };

    Node.prototype.toJSON = function() {
      var fixed, name, value;
      fixed = {};
      for (name in this) {
        if (!__hasProp.call(this, name)) continue;
        value = this[name];
        if (name === 'parent') {
          continue;
        }
        fixed[name] = value instanceof Array ? value.map(function(i) {
          if (typeof i === 'object' && i.toJSON) {
            return i.toJSON();
          } else {
            return i;
          }
        }) : typeof value === 'object' && value.toJSON ? value.toJSON() : value;
      }
      return fixed;
    };

    Node.prototype.defaultStyle = function() {
      return {};
    };

    Node.prototype.styleType = function() {
      return this.type;
    };

    Node.prototype.style = function() {
      var all, defaults, key, merge, root, style, type;
      type = this.styleType();
      defaults = this.defaultStyle(type);
      all = keys(this, defaults);
      if (all) {
        return all;
      }
      style = defaults;
      if (this.parent) {
        root = this;
        while (root.parent) {
          root = root.parent;
        }
        root.styleCache || (root.styleCache = {});
        if (root.styleCache[type]) {
          style = root.styleCache[type];
        } else {
          root.eachInside(function(another) {
            if (another.styleType() !== type) {
              return;
            }
            if (this === another) {
              return;
            }
            all = keys(another, style);
            if (all) {
              style = all;
              return false;
            }
          });
          root.styleCache[type] = style;
        }
      }
      merge = {};
      for (key in style) {
        merge[key] = this[key] != null ? this[key] : style[key];
      }
      return merge;
    };

    return Node;

  })();

  module.exports = Node;

}).call(this);
