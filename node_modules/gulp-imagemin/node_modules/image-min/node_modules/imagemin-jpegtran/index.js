'use strict';

var execFile = require('child_process').execFile;
var fs = require('fs');
var imageType = require('image-type');
var jpegtran = require('jpegtran-bin').path;
var tempfile = require('tempfile');
var rm = require('rimraf');

/**
 * jpegtran image-min plugin
 *
 * @param {Object} opts
 * @api public
 */

module.exports = function (opts) {
    opts = opts || {};

    return function (file, imagemin, cb) {
        if (imageType(file.contents) !== 'jpg') {
            return cb();
        }

        var args = ['-copy', 'none', '-optimize'];
        var src = tempfile('.jpg');
        var dest = tempfile('.jpg');

        if (opts.progressive) {
            args.push('-progressive');
        }

        fs.writeFile(src, file.contents, function (err) {
            if (err) {
                return cb(err);
            }

            execFile(jpegtran, args.concat(['-outfile', dest, src]), function (err) {
                if (err) {
                    return cb(err);
                }

                fs.readFile(dest, function (err, buf) {
                    rm(src, function (err) {
                        if (err) {
                            return cb(err);
                        }

                        rm(dest, function (err) {
                            if (err) {
                                return cb(err);
                            }

                            file.contents = buf;

                            cb();
                        });
                    });
                });
            });
        });
    };
};
