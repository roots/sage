(function() {
  var __slice = [].slice;

  module.exports = function(updater) {
    var prefix, prefixes;
    prefixes = {};
    prefix = function() {
      var data, name, names, _i, _j, _len, _results;
      names = 2 <= arguments.length ? __slice.call(arguments, 0, _i = arguments.length - 1) : (_i = 0, []), data = arguments[_i++];
      _results = [];
      for (_j = 0, _len = names.length; _j < _len; _j++) {
        name = names[_j];
        _results.push(prefixes[name] = data);
      }
      return _results;
    };
    this.feature('border-radius', (function(_this) {
      return function(browsers) {
        return prefix('border-radius', 'border-top-left-radius', 'border-top-right-radius', 'border-bottom-right-radius', 'border-bottom-left-radius', {
          mistakes: ['-ms-'],
          browsers: browsers,
          transition: true
        });
      };
    })(this));
    this.feature('css-boxshadow', (function(_this) {
      return function(browsers) {
        return prefix('box-shadow', {
          browsers: browsers,
          transition: true
        });
      };
    })(this));
    this.feature('css-animation', (function(_this) {
      return function(browsers) {
        return prefix('animation', 'animation-name', 'animation-duration', 'animation-delay', 'animation-direction', 'animation-fill-mode', 'animation-iteration-count', 'animation-play-state', 'animation-timing-function', '@keyframes', {
          browsers: browsers
        });
      };
    })(this));
    this.feature('css-transitions', (function(_this) {
      return function(browsers) {
        return prefix('transition', 'transition-property', 'transition-duration', 'transition-delay', 'transition-timing-function', {
          mistakes: ['-ms-'],
          browsers: browsers
        });
      };
    })(this));
    this.feature('transforms2d', (function(_this) {
      return function(browsers) {
        return prefix('transform', 'transform-origin', {
          browsers: browsers,
          transition: true
        });
      };
    })(this));
    this.feature('transforms3d', (function(_this) {
      return function(browsers) {
        prefix('perspective', 'perspective-origin', {
          browsers: browsers,
          transition: true
        });
        return prefix('transform-style', 'backface-visibility', {
          browsers: browsers
        });
      };
    })(this));
    this.feature('css-gradients', (function(_this) {
      return function(browsers) {
        browsers = _this.map(browsers, function(browser, name, version) {
          if (name === 'android' && version < 4 || name === 'safari' && version < 5.1 || name === 'ios' && version < 5) {
            return browser + ' old';
          } else {
            return browser;
          }
        });
        return prefix('linear-gradient', 'repeating-linear-gradient', 'radial-gradient', 'repeating-radial-gradient', {
          props: ['background', 'background-image', 'border-image'],
          mistakes: ['-ms-'],
          browsers: browsers
        });
      };
    })(this));
    this.feature('css3-boxsizing', (function(_this) {
      return function(browsers) {
        return prefix('box-sizing', {
          browsers: browsers
        });
      };
    })(this));
    this.feature('css-filters', (function(_this) {
      return function(browsers) {
        return prefix('filter', {
          browsers: browsers,
          transition: true
        });
      };
    })(this));
    this.feature('multicolumn', (function(_this) {
      return function(browsers) {
        prefix('columns', 'column-width', 'column-gap', 'column-rule', 'column-rule-color', 'column-rule-width', {
          browsers: browsers,
          transition: true
        });
        return prefix('column-count', 'column-rule-style', 'column-span', 'column-fill', 'break-before', 'break-after', 'break-inside', {
          browsers: browsers
        });
      };
    })(this));
    this.feature('user-select-none', (function(_this) {
      return function(browsers) {
        return prefix('user-select', {
          browsers: browsers
        });
      };
    })(this));
    this.feature('flexbox', (function(_this) {
      return function(browsers) {
        browsers = _this.map(browsers, function(browser, name, version) {
          if ((name === 'safari' || name === 'ios') && version < 7) {
            return browser + ' 2009';
          } else if (name === 'chrome' && version < 21) {
            return browser + ' 2009';
          } else {
            return browser;
          }
        });
        prefix('display-flex', 'inline-flex', {
          props: ['display'],
          browsers: browsers
        });
        prefix('flex', 'flex-grow', 'flex-shrink', 'flex-basis', {
          transition: true,
          browsers: browsers
        });
        return prefix('flex-direction', 'flex-wrap', 'flex-flow', 'justify-content', 'order', 'align-items', 'align-self', 'align-content', {
          browsers: browsers
        });
      };
    })(this));
    this.feature('calc', (function(_this) {
      return function(browsers) {
        return prefix('calc', {
          props: ['*'],
          browsers: browsers
        });
      };
    })(this));
    this.feature('background-img-opts', (function(_this) {
      return function(browsers) {
        return prefix('background-clip', 'background-origin', 'background-size', {
          browsers: browsers
        });
      };
    })(this));
    this.feature('font-feature', (function(_this) {
      return function(browsers) {
        return prefix('font-feature-settings', 'font-variant-ligatures', 'font-language-override', 'font-kerning', {
          browsers: browsers
        });
      };
    })(this));
    this.feature('border-image', (function(_this) {
      return function(browsers) {
        return prefix('border-image', {
          browsers: browsers
        });
      };
    })(this));
    this.feature('css-selection', (function(_this) {
      return function(browsers) {
        return prefix('::selection', {
          selector: true,
          browsers: browsers
        });
      };
    })(this));
    this.feature('css-placeholder', (function(_this) {
      return function(browsers) {
        browsers = _this.map(browsers, function(browser, name, version) {
          if (name === 'ff' && version <= 18) {
            return browser + ' old';
          } else {
            return browser;
          }
        });
        return prefix('::placeholder', {
          selector: true,
          browsers: browsers
        });
      };
    })(this));
    this.feature('css-hyphens', (function(_this) {
      return function(browsers) {
        return prefix('hyphens', {
          browsers: browsers
        });
      };
    })(this));
    this.feature('fullscreen', (function(_this) {
      return function(browsers) {
        return prefix(':fullscreen', {
          selector: true,
          browsers: browsers
        });
      };
    })(this));
    this.feature('css3-tabsize', (function(_this) {
      return function(browsers) {
        return prefix('tab-size', {
          browsers: browsers
        });
      };
    })(this));
    this.feature('intrinsic-width', (function(_this) {
      return function(browsers) {
        return prefix('max-content', 'min-content', 'fit-content', 'fill-available', {
          props: ['width', 'min-width', 'max-width', 'height', 'min-height', 'max-height'],
          browsers: browsers
        });
      };
    })(this));
    this.feature('css3-cursors-newer', (function(_this) {
      return function(browsers) {
        prefix('zoom-in', 'zoom-out', {
          props: ['cursor'],
          browsers: browsers.concat(['chrome 3'])
        });
        return prefix('grab', 'grabbing', {
          props: ['cursor'],
          browsers: browsers.concat(['ff 24', 'ff 25', 'ff 26'])
        });
      };
    })(this));
    this.feature('css-sticky', (function(_this) {
      return function(browsers) {
        return prefix('sticky', {
          props: ['position'],
          browsers: browsers
        });
      };
    })(this));
    this.feature('pointer', (function(_this) {
      return function(browsers) {
        return prefix('touch-action', {
          browsers: browsers
        });
      };
    })(this));
    this.feature('text-decoration', (function(_this) {
      return function(browsers) {
        return prefix('text-decoration-style', {
          browsers: browsers
        });
      };
    })(this));
    this.feature('text-decoration', {
      full: true
    }, (function(_this) {
      return function(browsers) {
        prefix('text-decoration-line', 'text-decoration-color', {
          browsers: browsers
        });
        return prefix('wavy', {
          props: ['text-decoration', 'text-decoration-style'],
          browsers: browsers
        });
      };
    })(this));
    return this.done((function(_this) {
      return function() {
        return _this.save('prefixes', prefixes);
      };
    })(this));
  };

}).call(this);
