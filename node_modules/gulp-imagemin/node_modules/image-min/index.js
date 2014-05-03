'use strict';

var fs = require('fs-extra');
var mode = require('stat-mode');
var Ware = require('ware');

/**
 * Initialize Imagemin
 *
 * @api public
 */

function Imagemin() {
    this.ware = new Ware();
}

/**
 * Add a plugin to the middleware stack
 *
 * @param {Function} plugin
 * @api public
 */

Imagemin.prototype.use = function (plugin) {
    this.ware.use(plugin);
    return this;
};

/**
 * Get or set the source file
 *
 * @param {String|Buffer} file
 * @api public
 */

Imagemin.prototype.src = function (file) {
    if (!arguments.length) {
        return this._src;
    }

    this._src = file;
    return this;
};

/**
 * Get or set the destination file
 *
 * @param {String} file
 * @api public
 */

Imagemin.prototype.dest = function (file) {
    if (!arguments.length) {
        return this._dest;
    }

    this._dest = file;
    return this;
};

/**
 * Optimize file
 *
 * @param {Function} cb
 * @api public
 */

Imagemin.prototype.optimize = function (cb) {
    cb = cb || function () {};
    var self = this;

    this.read(function (err, file) {
        var buf = file.contents;

        self.run(file, function (err, file) {
            if (err) {
                return cb(err);
            }

            if (file.contents.length >= buf.length) {
                file.contents = buf;
            }

            self.write(file, function (err) {
                cb(err, file);
            });
        });
    });
};

/**
 * Run a file through the middleware
 *
 * @param {Array} file
 * @param {Function} cb
 * @api public
 */

Imagemin.prototype.run = function (file, cb) {
    this.ware.run(file, this, cb);
};

/**
 * Read the source file
 *
 * @param {Function} cb
 * @api public
 */

Imagemin.prototype.read = function (cb) {
    var file = {};
    var src = this.src();

    if (Buffer.isBuffer(src)) {
        file.contents = src;

        return cb(null, file);
    }

    fs.readFile(src, function (err, buf) {
        if (err) {
            return cb(err);
        }

        fs.stat(src, function (err, stats) {
            if (err) {
                return cb(err);
            }

            file.contents = buf;
            file.mode = mode(stats).toOctal();

            cb(null, file);
        });
    });


};

/**
 * Write file to destination
 *
 * @param {Object} file
 * @param {Function} cb
 * @api public
 */

Imagemin.prototype.write = function (file, cb) {
    var dest = this.dest();

    if (!dest) {
        return cb();
    }

    fs.outputFile(dest, file.contents, function (err) {
        cb(err);
    });
};

/**
 * Module exports
 */

module.exports = Imagemin;
module.exports.gifsicle = require('imagemin-gifsicle');
module.exports.jpegtran = require('imagemin-jpegtran');
module.exports.optipng = require('imagemin-optipng');
module.exports.pngquant = require('imagemin-pngquant');
module.exports.svgo = require('imagemin-svgo');
