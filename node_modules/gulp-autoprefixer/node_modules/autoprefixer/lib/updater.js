(function() {
  var coffee, fs, https;

  coffee = require('coffee-script');

  https = require('https');

  fs = require('fs');

  module.exports = {
    browsers: {
      firefox: 'ff',
      chrome: 'chrome',
      safari: 'safari',
      ios_saf: 'ios',
      opera: 'opera',
      ie: 'ie',
      bb: 'bb',
      android: 'android'
    },
    run: function() {
      var i, updaters, _i, _len, _ref, _results;
      updaters = __dirname + '/../updaters/';
      _ref = fs.readdirSync(updaters).sort();
      _results = [];
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        i = _ref[_i];
        if (!i.match(/\.(coffee|js)$/)) {
          continue;
        }
        _results.push(require(updaters + i).apply(this));
      }
      return _results;
    },
    requests: 0,
    doneCallbacks: [],
    requestCallbacks: [],
    done: function(callback) {
      this.doneCallbacks || (this.doneCallbacks = []);
      return this.doneCallbacks.push(callback);
    },
    error: function(message) {
      process.stderr.write("\n" + message + "\n");
      return process.exit(1);
    },
    request: function(callback) {
      this.requestCallbacks || (this.requestCallbacks = []);
      return this.requestCallbacks.push(callback);
    },
    github: function(path, callback) {
      var url;
      this.requests += 1;
      url = "https://raw.githubusercontent.com/" + path;
      return https.get(url, (function(_this) {
        return function(res) {
          var data;
          data = '';
          res.on('data', function(chunk) {
            return data += chunk;
          });
          return res.on('end', function() {
            var e, func, title, _i, _j, _len, _len1, _ref, _ref1, _results;
            try {
              callback(JSON.parse(data));
            } catch (_error) {
              e = _error;
              title = data.match(/<title>([^<]+)<\/title>/);
              if (title) {
                _this.error("" + title[1] + " on " + url);
              } else {
                _this.error("Parsing error in " + url + ":\n" + e.message);
              }
            }
            _this.requests -= 1;
            _ref = _this.requestCallbacks;
            for (_i = 0, _len = _ref.length; _i < _len; _i++) {
              func = _ref[_i];
              func();
            }
            if (_this.requests === 0) {
              _ref1 = _this.doneCallbacks.reverse();
              _results = [];
              for (_j = 0, _len1 = _ref1.length; _j < _len1; _j++) {
                func = _ref1[_j];
                _results.push(func());
              }
              return _results;
            }
          });
        };
      })(this));
    },
    sort: function(browsers) {
      return browsers.sort(function(a, b) {
        a = a.split(' ');
        b = b.split(' ');
        if (a[0] > b[0]) {
          return 1;
        } else if (a[0] < b[0]) {
          return -1;
        } else {
          return parseFloat(a[1]) - parseFloat(b[1]);
        }
      });
    },
    parse: function(data, opts) {
      var browser, interval, match, need, support, version, versions, _i, _len, _ref, _ref1;
      match = opts.full ? /y\sx($|\s)/ : /\sx($|\s)/;
      need = [];
      _ref = data.stats;
      for (browser in _ref) {
        versions = _ref[browser];
        for (interval in versions) {
          support = versions[interval];
          _ref1 = interval.split('-');
          for (_i = 0, _len = _ref1.length; _i < _len; _i++) {
            version = _ref1[_i];
            if (this.browsers[browser] && support.match(match)) {
              version = version.replace(/\.0$/, '');
              need.push(this.browsers[browser] + ' ' + version);
            }
          }
        }
      }
      return this.sort(need);
    },
    feature: function(file, opts, callback) {
      var url, _ref;
      if (!callback) {
        _ref = [opts, {}], callback = _ref[0], opts = _ref[1];
      }
      url = "Fyrd/caniuse/master/features-json/" + file + ".json";
      return this.github(url, (function(_this) {
        return function(data) {
          return callback(_this.parse(data, opts));
        };
      })(this));
    },
    fork: function(fork, file, opts, callback) {
      var branch, url, user, _ref, _ref1;
      if (!callback) {
        _ref = [opts, {}], callback = _ref[0], opts = _ref[1];
      }
      _ref1 = fork.split(':'), user = _ref1[0], branch = _ref1[1];
      branch || (branch = 'master');
      url = "" + user + "/caniuse/" + branch + "/features-json/" + file + ".json";
      return this.github(url, (function(_this) {
        return function(data) {
          return callback(_this.parse(data, opts));
        };
      })(this));
    },
    all: function(callback) {
      var browsers, data, list, name, version, _i, _len, _ref;
      browsers = require('../data/browsers');
      list = [];
      for (name in browsers) {
        data = browsers[name];
        _ref = data.versions;
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
          version = _ref[_i];
          list.push(name + ' ' + version);
        }
      }
      return callback(this.sort(list));
    },
    map: function(browsers, callback) {
      var browser, name, version, _i, _len, _ref, _results;
      _results = [];
      for (_i = 0, _len = browsers.length; _i < _len; _i++) {
        browser = browsers[_i];
        _ref = browser.split(' '), name = _ref[0], version = _ref[1];
        version = parseFloat(version);
        _results.push(callback(browser, name, version));
      }
      return _results;
    },
    stringify: function(obj, indent) {
      var key, local, processed, value;
      if (indent == null) {
        indent = '';
      }
      if (obj instanceof Array) {
        local = indent + '  ';
        return ("[\n" + local) + obj.map((function(_this) {
          return function(i) {
            return _this.stringify(i, local);
          };
        })(this)).join("\n" + local) + ("\n" + indent + "]");
      } else if (typeof obj === 'object') {
        local = indent + '  ';
        processed = [];
        for (key in obj) {
          value = obj[key];
          if (key.match(/'|-|@|:/)) {
            key = "\"" + key + "\"";
          }
          value = this.stringify(value, local);
          if (value[0] !== "\n") {
            value = ' ' + value;
          }
          processed.push(key + ':' + value);
        }
        return "\n" + local + processed.join("\n" + local) + "\n";
      } else {
        return JSON.stringify(obj);
      }
    },
    changed: [],
    save: function(name, json) {
      var content, file, key, sorted, _i, _len, _ref;
      sorted = {};
      _ref = Object.keys(json).sort();
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        key = _ref[_i];
        sorted[key] = json[key];
      }
      file = __dirname + ("/../data/" + name);
      content = "# Don't edit this files, because it's autogenerated.\n" + "# See updaters/ dir for generator. " + "Run `bin/autoprefixer --update` to update." + "\n\n";
      content += "module.exports =" + this.stringify(sorted) + ";\n";
      if (fs.existsSync(file + '.js')) {
        file += '.js';
        content = coffee.compile(content);
      } else {
        file += '.coffee';
      }
      if (fs.readFileSync(file).toString() !== content) {
        this.changed.push(name);
        return fs.writeFileSync(file, content);
      }
    }
  };

}).call(this);
