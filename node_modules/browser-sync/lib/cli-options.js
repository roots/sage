"use strict";

var path = require("path");

module.exports = {
    /**
     * Merge Server Options
     * @param {Object} defaultValue
     * @param {String} arg
     * @param {Object} [argv] - process.argv
     * @returns {{baseDir: string}}
     * @private
     */
    _mergeServerOption: function (defaultValue, arg, args) {

        // Return if object or array given
        if (arg.baseDir) {
            return arg;
        }

        var obj = {
            baseDir: "./"
        };

        if (arg !== true) {
            obj.baseDir = arg;
        }

        if (args) {
            if (args.index) {
                obj.index = args.index;
            }
            if (args.directory) {
                obj.directory = true;
            }
        }

        return obj;
    },
    /**
     * @param defaultValue
     * @param arg
     * @returns {*}
     * @private
     */
    _mergeProxyOption: function (defaultValue, arg) {

        var protocol = "http";
        var host = "localhost";
        var port = 80;
        var segs;
        var startPath = false;
        var returnObj;

        if (typeof arg !== "string") {
            return false;
        }

        var url = arg.replace(/^(https?):\/\//, function (match, solo) {
            protocol = solo;
            return "";
        });

        if (~url.indexOf(":")) {
            segs = url.split(":");
            host = segs[0];
            port = parseInt(segs[1], 10);
        } else {
            host = url;
        }

        if (~host.indexOf("/")) {
            segs = host.split("/");
            host = segs.shift();
            startPath = segs.join("/");
        }

        returnObj = {
            protocol: protocol,
            host: host,
            port: port
        };

        if (startPath) {
            returnObj.startPath = startPath;
        }

        return returnObj;
    },
    /**
     * @param {Object} defaultValue
     * @param {String} arg
     * @returns {String}
     * @private
     */
    _mergeHostOption: function (defaultValue, arg) {
        if (arg && typeof arg === "string") {
            return arg;
        }
        return null;
    },
    /**
     * @param defaultValue
     * @param arg
     * @private
     */
    _mergePortsOption: function (defaultValue, arg) {

        var segs;
        var obj = {};

        if (typeof arg === "string") {

            if (~arg.indexOf(",")) {
                segs = arg.split(",");
                obj.min = parseInt(segs[0], 10);
                obj.max = parseInt(segs[1], 10);
            } else {
                obj.min = parseInt(arg, 10);
                obj.max = null;
            }

            return obj;

        } else {
            return {
                min: arg.min,
                max: arg.max || null
            };
        }
    },
    /**
     * @private
     */
    _mergeGhostModeOption: function (defaultValue, arg) {
        if (!arg || arg === "false") {
            return false;
        }
        return arg;
    },
    /**
     *
     * @param {String|Array} files
     * @param {String|Array} [exclude]
     * @returns {Array}
     * @private
     */
    _mergeFilesOption: function (files, exclude) {

        var merged;
        var split;

        if (files) {
            if (typeof files === "string") {
                merged = [];
                if (~files.indexOf(",")) {
                    split = files.split(",");
                    merged = merged.concat(split.map(function (item) {
                        return item.trim();
                    }));
                } else {
                    merged.push(files);
                }
            } else {
                merged = files;
            }
        }

        if (typeof exclude === "string") {
            merged.push(this._wrapPattern(exclude));
        } else {
            if (Array.isArray(exclude)) {
                exclude.forEach(function (pattern) {
                    merged.push(this._wrapPattern(pattern));
                }, this);
            }
        }

        return merged;
    },
    /**
     * @param pattern
     * @returns {*|string}
     * @private
     */
    _wrapPattern: function (pattern) {
        var prefix = "!";
        var suffix = "/**";
        var lastChar = pattern.charAt(pattern.length - 1);
        var extName = path.extname(pattern);

        // If there's a file ext, don't append any suffix
        if (extName.length) {
            suffix = "";
        } else {

            if (lastChar === "/") {
                suffix = "**";
            }

            if (lastChar === "*") {
                suffix = "";
            }
        }

        return [prefix, pattern, suffix].join("");
    }
};