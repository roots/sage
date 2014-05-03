/*global afterEach, beforeEach, describe, it */
'use strict';

var assert = require('assert');
var binCheck = require('bin-check');
var BinWrapper = require('bin-wrapper');
var fs = require('fs');
var path = require('path');
var spawn = require('child_process').spawn;
var rm = require('rimraf');

describe('jpegtran()', function () {
	afterEach(function (cb) {
		rm(path.join(__dirname, 'tmp'), cb);
	});

	beforeEach(function (cb) {
		fs.mkdir(path.join(__dirname, 'tmp'), cb);
	});

	it('should rebuild the jpegtran binaries', function (cb) {
		var bin = new BinWrapper({ bin: 'jpegtran', dest: path.join(__dirname, 'tmp') });
		var bs = './configure --disable-shared ' +
				 '--prefix="' + bin.dest + '" && ' + 'make install';

		bin
			.addSource('http://downloads.sourceforge.net/project/libjpeg-turbo/1.3.0/libjpeg-turbo-1.3.0.tar.gz')
			.build(bs)
			.on('finish', function () {
				cb(assert(true));
			});
	});

	it('should return path to binary and verify that it is working', function (cb) {
		var binPath = require('../').path;

		var args = [
			'-copy', 'none',
			'-optimize',
			'-outfile', path.join(__dirname, 'tmp/test.jpg'),
			path.join(__dirname, 'fixtures/test.jpg')
		];

		binCheck(binPath, args, function (err, works) {
			cb(assert.equal(works, true));
		});
	});

	it('should minify a JPG', function (cb) {
		var binPath = require('../').path;
		var args = [
			'-copy', 'none',
			'-optimize',
			'-outfile', path.join(__dirname, 'tmp/test.jpg'),
			path.join(__dirname, 'fixtures', 'test.jpg')
		];

		spawn(binPath, args).on('close', function () {
			var src = fs.statSync(path.join(__dirname, 'fixtures/test.jpg')).size;
			var dest = fs.statSync(path.join(__dirname, 'tmp/test.jpg')).size;

			cb(assert(dest < src));
		});
	});
});
