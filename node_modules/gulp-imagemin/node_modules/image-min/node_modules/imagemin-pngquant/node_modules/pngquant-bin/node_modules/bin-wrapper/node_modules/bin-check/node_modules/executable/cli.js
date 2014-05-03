#!/usr/bin/env node
'use strict';

var executable = require('./');
var input = process.argv.slice(2);
var pkg = require('./package.json');

/**
 * Help screen
 */

function help() {
    console.log(pkg.description);
    console.log('');
    console.log('Usage');
    console.log('  $ executable <file>');
    console.log('');
    console.log('Example');
    console.log('  $ executable optipng');
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
 
console.log(executable.sync(input[0]));
