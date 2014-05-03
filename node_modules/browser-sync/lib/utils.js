"use strict";

var devIp       = require("dev-ip");
var open        = require("open");
var filePath    = require("path");
var portScanner = require("portscanner-plus");
var _           = require("lodash");
var UAParser    = require("ua-parser-js");
var parser      = new UAParser();

var utils = {
    /**
     * @param {Object} options
     * @returns {String} - the IP address
     */
    getHostIp: function (options) {

        var fallback = "0.0.0.0";
        var returnValue = devIp.getIp(null);

        if (options) {
            if (options.host) {
                return options.host;
            }
            if (options.detect === false || !devIp) {
                return fallback;
            }
        }

        if (Array.isArray(returnValue)) {
            returnValue = returnValue[0];
        }

        return returnValue || fallback;
    },
    /**
     * Take the path provided in options & transform into CWD for serving files
     * @param {String} [baseDir]
     * @returns {String}
     */
    getBaseDir: function (baseDir) {

        if (Array.isArray(baseDir)) {
            return baseDir;
        }

        var suffix = "";
        var validRoots = ["./", "/", "."];

        if (!baseDir || _.contains(validRoots, baseDir)) {
            return process.cwd();
        }

        if (baseDir.charAt(0) === "/") {
            suffix = baseDir;
        } else {
            if (/^.\//.test(baseDir)) {
                suffix = baseDir.replace(".", "");
            } else {
                suffix = "/" + baseDir;
            }
        }

        return process.cwd() + suffix;
    },
    /**
     * Append a start path if given in options
     * @param url
     * @param options
     * @returns {*}
     */
    getUrl: function (url, options) {

        var prefix = "/";
        var startPath = options.startPath;

        if (options.proxy && options.proxy.startPath) {
            startPath = options.proxy.startPath;
        }

        if (startPath) {
            if (startPath.charAt(0) === "/") {
                prefix = "";
            }
            url = url + prefix + startPath;
        }


        return url;
    },
    /**
     * @param {String} host
     * @param {Number} port
     * @returns {string}
     * @private
     */
    _makeUrl: function (host, port) {
        return "http://" + host + ":" + port;
    },
    /**
     * Get ports
     * @param options
     * @returns {Q.promise}
     * @param minCount
     */
    getPorts: function (options, minCount) {

        var minPorts = (options.server || options.proxy) ? 3 : minCount;
        var minPortRange = options.ports && options.ports.min;
        var maxPortRange = options.ports && options.ports.max;

        var names = ["socket", "controlPanel"];

        if (options.server) {
            names.push("server");
        }

        if (options.proxy) {
            names.push("proxy");
        }

        return portScanner.getPorts(minPorts, minPortRange, maxPortRange, names);
    },
    /**
     * @param {String} ua
     * @returns {Object}
     */
    getUaString: function (ua) {
        return parser.setUA(ua).getBrowser();
    },
    /**
     * @param filepath
     * @param cwd
     * @returns {*|XML|string|void}
     */
    resolveRelativeFilePath: function (filepath, cwd) {
        return filepath.replace(cwd + "/", "");
    },
    /**
     * @param {String} path
     * @returns {String}
     */
    getFileExtension: function (path) {
        return filePath.extname(path).replace(".", "");
    },
    /**
     * Open the page in browser
     * @param {String} url
     * @param {Object} options
     */
    openBrowser: function (url, options) {
        if (options.open) {
            open(url);
        }
    },
    /**
     * Log a message to the console
     * @param {String} msg
     * @param {Object} options
     * @param {boolean} override
     * @returns {boolean}
     */
    log: function (msg, options, override) {
        if (!options.debugInfo && !override) {
            return false;
        }
        return console.log(msg);
    },
    /**
     * @param {String} msg
     * @param {Object} options
     * @param {Boolean} kill
     */
    fail: function (msg, options, kill) {
        this.log(msg, options, false);
        if (kill) {
            process.exit(1);
        }
    },
    /**
     * Add support for xip.io urls
     * @param {String} host
     * @param {Object} options
     * @returns {String}
     */
    xip: function (host, options) {
        if (options.xip) {
            return host + ".xip.io";
        }
        if (options.hostnameSuffix) {
            return host + options.hostnameSuffix;
        }
        return host;
    }
};

module.exports.utils = utils;