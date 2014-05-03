(function() {
  module.exports = function() {
    var minor;
    minor = ['bb', 'android'];
    return this.github('Fyrd/caniuse/master/data.json', (function(_this) {
      return function(data) {
        var agent, browsers, caniuse, internal, intervals, normalize, _ref;
        normalize = function(array) {
          return array.reverse().filter(function(i) {
            return i;
          });
        };
        intervals = function(array) {
          var i, interval, result, splited, sub, _i, _len;
          result = [];
          for (_i = 0, _len = array.length; _i < _len; _i++) {
            interval = array[_i];
            splited = interval.split('-').map(function(i) {
              return parseFloat(i);
            });
            splited = splited.sort().reverse();
            sub = (function() {
              var _j, _len1, _results;
              _results = [];
              for (_j = 0, _len1 = splited.length; _j < _len1; _j++) {
                i = splited[_j];
                _results.push([i, interval, splited.length]);
              }
              return _results;
            })();
            result = result.concat(sub);
          }
          return result;
        };
        agent = function(name) {
          var future, info, result, versions;
          info = data.agents[name];
          future = normalize(info.versions.slice(-3)).map(function(i) {
            return parseFloat(i);
          });
          versions = intervals(normalize(info.versions.slice(0, -3)));
          result = {};
          result.prefix = name === 'opera' ? '-o-' : "-" + info.prefix + "-";
          if (minor.indexOf(name) !== -1) {
            result.minor = true;
          }
          if (future.length) {
            result.future = future;
          }
          result.versions = versions.map(function(i) {
            return i[0];
          });
          result.popularity = versions.map(function(i) {
            return info.usage_global[i[1]] / i[2];
          });
          return result;
        };
        browsers = {};
        _ref = _this.browsers;
        for (caniuse in _ref) {
          internal = _ref[caniuse];
          browsers[internal] = agent(caniuse);
        }
        return _this.save('browsers', browsers);
      };
    })(this));
  };

}).call(this);
