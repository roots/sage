#!/usr/bin/env node
'use strict';

var input = process.argv.slice(2);
var pkg = require('./package.json');
var extname = require('./');

/**
 * Help screen
 */

function help() {
    console.log(pkg.description);
    console.log('');
    console.log('Usage');
    console.log('  $ extname <file>');
    console.log('');
    console.log('Example');
    console.log('  $ extname file.tar.gz');
}

/**
 * Show help
 */

if (input.indexOf('-h') !== -1 || input.indexOf('--help') !== -1) {
    help();
    return;
}

/**
 * Show package version
 */

if (input.indexOf('-v') !== -1 || input.indexOf('--version') !== -1) {
    console.log(pkg.version);
    return;
}

/**
 * Run
 */

console.log(extname(input).ext, extname(input).mime);
