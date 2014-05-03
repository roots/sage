'use strict';

var ExecBuffer = require('exec-buffer');
var imageType = require('image-type');
var pngquant = require('pngquant-bin').path;

/**
 * pngquant image-min plugin
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

        var exec = new ExecBuffer();

        exec
            .use(pngquant, ['-o', exec.dest(), exec.src()])
            .run(file.contents, function (err, buf) {
                if (err) {
                    return cb(err);
                }

                file.contents = buf;
                cb();
            });
    };
};
