#!/usr/bin/env node
'use strict';
var prettyBytes = require('./pretty-bytes');
var input = process.argv.slice(2);

if (!input || process.argv.indexOf('-h') !== -1 || process.argv.indexOf('--help') !== -1) {
	console.log('');
	console.log('pretty-bytes <number>');
	console.log('');
	console.log('Example:');
	console.log('  $ pretty-bytes 1337');
	console.log('  1.34 kB');
	return;
}

if (process.argv.indexOf('-v') !== -1 || process.argv.indexOf('--version') !== -1) {
	console.log(require('./package').version);
	return;
}

console.log(prettyBytes(Number(input)));
