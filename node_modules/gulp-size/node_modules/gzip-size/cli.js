#!/usr/bin/env node
'use strict';
var fs = require('fs');
var zlib = require('zlib');
var concat = require('concat-stream');
var input = process.argv[2];

function help() {
	console.log('gzip-size <input-file>');
	console.log('or');
	console.log('cat <input-file> | gzip-size');
	console.log('');
	console.log('Get the gzipped size of a file');
}

function report(data) {
	console.log(data.length);
}

if (process.argv.indexOf('-h') !== -1 || process.argv.indexOf('--help') !== -1) {
	help();
	return;
}

if (process.argv.indexOf('-v') !== -1 || process.argv.indexOf('--version') !== -1) {
	console.log(require('./package').version);
	return;
}

if (process.stdin.isTTY) {
	if (!input) {
		return help();
	}

	fs.createReadStream(input).pipe(zlib.createGzip()).pipe(concat(report));
} else {
	process.stdin.pipe(zlib.createGzip()).pipe(concat(report));
}
