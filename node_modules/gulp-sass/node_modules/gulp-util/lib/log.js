var colors = require('./colors');

module.exports = function(){
  var sig = '['+colors.green('gulp')+']';
  var args = Array.prototype.slice.call(arguments);
  args.unshift(sig);
  console.log.apply(console, args);
  return this;
};