'use strict';
var gzip = require('zlib').gzip;
var gzipSync = require('zlib-browserify').gzipSync;

module.exports = function (str, cb) {
	if (!str) {
		return cb(err, 0);
	}

	gzip(str, function (err, data) {
		if (err) {
			return cb(err, 0);
		}

		cb(err, data.length);
	});
};

module.exports.sync = function (str) {
	return gzipSync(str).length;
};
