var dateFormat = require('./lib/dateformat.js');
var now = new Date();

// Basic usage
console.log(dateFormat(now, "dddd, mmmm dS, yyyy, h:MM:ss TT"));
// Saturday, June 9th, 2007, 5:46:21 PM

// You can use one of several named masks
console.log(dateFormat(now, "isoDateTime"));
// 2007-06-09T17:46:21

// ...Or add your own
dateFormat.masks.hammerTime = 'HH:MM! "Can\'t touch this!"';
console.log(dateFormat(now, "hammerTime"));
// 17:46! Can't touch this!

// When using the standalone dateFormat function,
// you can also provide the date as a string
console.log(dateFormat("Jun 9 2007", "fullDate"));
// Saturday, June 9, 2007

// Note that if you don't include the mask argument,
// dateFormat.masks.default is used
console.log(dateFormat(now));
// Sat Jun 09 2007 17:46:21

// And if you don't include the date argument,
// the current date and time is used
console.log(dateFormat());
// Sat Jun 09 2007 17:46:22

// You can also skip the date argument (as long as your mask doesn't
// contain any numbers), in which case the current date/time is used
console.log(dateFormat("longTime"));
// 5:46:22 PM EST

// And finally, you can convert local time to UTC time. Simply pass in
// true as an additional argument (no argument skipping allowed in this case):
console.log(dateFormat(now, "longTime", true));
// 10:46:21 PM UTC

// ...Or add the prefix "UTC:" to your mask.
console.log(dateFormat(now, "UTC:h:MM:ss TT Z"));
// 10:46:21 PM UTC

