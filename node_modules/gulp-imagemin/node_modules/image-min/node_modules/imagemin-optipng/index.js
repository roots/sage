'use strict';

var execFile = require('child_process').execFile;
var fs = require('fs');
var imageType = require('image-type');
var optipng = require('optipng-bin').path;
var tempfile = require('tempfile');
var rm = require('rimraf');

/**
 * optipng image-min plugin
 *
 * @param {Object} opts
 * @api public
 */

module.exports = function (opts) {
    opts = opts || {};

    return function (file, imagemin, cb) {
        if (imageType(file.contents) !== 'png') {
            return cb();
        }

        var args = ['-strip', 'all', '-quiet', '-clobber'];
        var optimizationLevel = opts.optimizationLevel || 3;
        var src = tempfile('.png');
        var dest = tempfile('.png');

        if (typeof optimizationLevel === 'number') {
            args.push('-o', optimizationLevel);
        }

        fs.writeFile(src, file.contents, function (err) {
            if (err) {
                return cb(err);
            }

            execFile(optipng, args.concat(['-out', dest, src]), function (err) {
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
