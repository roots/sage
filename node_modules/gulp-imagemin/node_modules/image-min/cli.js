#!/usr/bin/env node
'use strict';

var fs = require('fs');
var Imagemin = require('./');
var nopt = require('nopt');
var pkg = require('./package.json');
var prettyBytes = require('pretty-bytes');
var stdin = require('get-stdin');

/**
 * Options
 */

var opts = nopt({
    help: Boolean,
    interlaced: Boolean,
    optimizationLevel: String,
    out: String,
    progressive: Boolean,
    version: Boolean
}, {
    h: '--help',
    i: '--interlaced',
    l: '--optimizationLevel',
    o: '--out',
    p: '--progressive',
    v: '--version'
});

/**
 * Help screen
 */

function help() {
    console.log(pkg.description);
    console.log('');
    console.log('Usage');
    console.log('  $ imagemin <file>');
    console.log('  $ cat <file> | imagemin');
    console.log('');
    console.log('Example');
    console.log('  $ imagemin --out foo-optimized.png foo.png');
    console.log('  $ cat foo.png | imagemin --out foo-optimized.png');
    console.log('');
    console.log('Options');
    console.log('  -i, --interlaced                    Extract archive files on download');
    console.log('  -l, --optimizationLevel <number>    Path to download or extract the files to');
    console.log('  -o, --out <file>                    Output file');
    console.log('  -p, --progressive                   Strip path segments from root when extracting');
}

/**
 * Show help
 */

if (opts.help) {
    help();
    return;
}

/**
 * Show package version
 */

if (opts.version) {
    console.log(pkg.version);
    return;
}

/**
 * Run
 */

function run(input) {
    if (!opts.out) {
        return console.log('Specify a outfile');
    }

    var imagemin = new Imagemin()
        .src(input)
        .dest(opts.out)
        .use(Imagemin.gifsicle(opts.interlaced))
        .use(Imagemin.jpegtran(opts.progressive))
        .use(Imagemin.optipng(opts.optimizationLevel))
        .use(Imagemin.svgo());

    imagemin.optimize(function (err, file) {
        if (err) {
            return console.log(err);
        }

        var diff = input.length - file.contents.length;

        console.log(opts.out + ' (saved ' + prettyBytes(diff) + ')');
    });
}

/**
 * Apply arguments
 */

if (process.stdin.isTTY) {
    var input = opts.argv.remain;

    if (input.length > 1) {
        return console.log('Only one input file allowed');
    }

    fs.readFile(input[0], function (err, data) {
        if (err) {
            return console.log(err);
        }

        run(data);
    });
} else {
    stdin(function (data) {
        data = Buffer.isBuffer(data) ? data : new Buffer(data);
        run(data);
    });
}
