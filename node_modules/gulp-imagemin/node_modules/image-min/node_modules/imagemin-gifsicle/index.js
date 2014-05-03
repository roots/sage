'use strict';

var ExecBuffer = require('exec-buffer');
var gifsicle = require('gifsicle').path;
var imageType = require('image-type');

/**
 * gifsicle image-min plugin
 *
 * @param {Object} opts
 * @api public
 */

module.exports = function (opts) {
    opts = opts || {};

    return function (file, imagemin, cb) {
        if (imageType(file.contents) !== 'gif') {
            return cb();
        }

        var exec = new ExecBuffer();
        var args = ['-w'];

        if (opts.interlaced) {
            args.push('--interlace');
        }

        exec
            .use(gifsicle, args.concat(['-o', exec.dest(), exec.src()]))
            .run(file.contents, function (err, buf) {
                if (err) {
                    return cb(err);
                }

                file.contents = buf;
                cb();
            });
    };
};
