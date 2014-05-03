'use strict';
var gutil = require('gulp-util');
var through = require('through2');
var chalk = require('chalk');
var prettyBytes = require('pretty-bytes');
var gzipSize = require('gzip-size');

function log(title, what, size, gzip) {
	gutil.log('gulp-size: ' + title + what + ' ' + prettyBytes(size) +
		(gzip ? chalk.gray(' (gzipped)') : ''));
};

module.exports = function (options) {
	options = options || {};

	var totalSize = 0;
	var fileCount = 0;
	var title = options.title ? options.title + ' ' : '';

	return through.obj(function (file, enc, cb) {
		if (file.isNull()) {
			this.push(file);
			return cb();
		}

		if (file.isStream()) {
			this.emit('error', new gutil.PluginError('gulp-size', 'Streaming not supported'));
			return cb();
		}

		var finish = function (err, size) {
			totalSize += size;

			if (options.showFiles === true) {
				log(title, chalk.blue(file.relative), size, options.gzip);
			}

			fileCount++;
			this.push(file);
			cb();
		}.bind(this);

		if (options.gzip) {
			gzipSize(file.contents, finish);
		} else {
			finish(null, file.contents.length);
		}
	}, function (cb) {
		if (fileCount === 1 && options.showFiles === true) {
			return cb();
		}

		log(title, chalk.green('total'), totalSize, options.gzip);
		cb();
	});
};
