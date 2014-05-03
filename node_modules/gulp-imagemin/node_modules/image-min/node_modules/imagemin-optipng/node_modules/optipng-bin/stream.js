#!/usr/bin/env node
'use strict';

var concat = require('concat-stream');
var fs = require('fs');
var optipng = require('./').path;
var rm = require('rimraf');
var spawn = process.platform === 'win32' ? require('win-spawn') : require('child_process').spawn;
var tempfile = require('tempfile');

/**
 * Streaming interface for OptiPNG
 */

process.stdin.pipe(concat(function (data) {
    var src = tempfile('.png');
    var dest = tempfile('.png');

    fs.writeFile(src, data, function (err) {
        var args = process.argv.slice(2).concat(['-out', dest, src]);
        var cp = spawn(optipng, args, { stdio: 'inherit' });

        if (err) {
            throw err;
        }

        cp.on('exit', function () {
            rm.sync(src);

            fs.createReadStream(dest).pipe(process.stdout).on('close', function () {
                rm.sync(dest);
            });
        });
    });
}));
