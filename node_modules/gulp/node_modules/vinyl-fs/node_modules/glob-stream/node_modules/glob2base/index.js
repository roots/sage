var path = require('path');

var flattenGlob = function(arr){
  var out = [];
  var flat = true;
  for(var i = 0; i < arr.length; i++) {
    if (typeof arr[i] !== 'string') {
      flat = false;
      break;
    }
    out.push(arr[i]);
  }
  if (flat) out.pop(); // last one is a file or specific dir
  return out;
};

var flattenExpansion = function(set) {
  // dirty trick
  // use the first two items in the set to figure out
  // where the expansion starts
  return findCommon(set[0], set[1]);
};

// algorithm assumes both arrays have the same length
// because this is how globs work
var findCommon = function(a1, a2) {
  var len = a1.length;
  for (var i = 0; i < len; i++) {
    if (a1[i] !== a2[i]) {
      if(typeof a1[i - 1] == 'string') return a1.slice(0, i);
      return a1.slice(0, i - 1); // fix for double bracket expansion
    }
  }
  return a1; // identical
};

var setToBase = function(set) {
  // normal something/*.js
  if (set.length <= 1) {
    return flattenGlob(set[0]);
  }
  // has expansion
  return flattenExpansion(set);
};

module.exports = function(glob) {
  var cwd = (glob.options && glob.options.cwd) ? glob.options.cwd : process.cwd();
  var set = glob.minimatch.set;
  var basePath = path.normalize(setToBase(set).join(path.sep))+path.sep;
  return basePath;
};
