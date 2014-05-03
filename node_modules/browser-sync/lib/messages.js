"use strict";

var prefixed = require("cl-strings").getCompiler("{green:[}BS{green:]}");
var compile  = require("cl-strings").compile;
var path     = require("path");
var config   = require("./config");

module.exports = {
    /**
     * @param {String} hostIp
     * @param {Object} ports
     * @param {Object} options
     * @returns {String}
     */
    init: function (hostIp, ports, options) {

        var template = "{green:Copy the following snippet into your website, just before the closing} </body> {green:tag}\n\n{:tags:}";
        var params = {
            tags: this.scriptTags(hostIp, ports, options)
        };

        return prefixed(template, params);
    },
    server: {
        /**
         * Conflicting options
         * @returns {String}
         */
        withProxy: function () {
            var template = "{red:Invalid config. You cannot specify both a server & proxy option.}";
            return prefixed(template);
        }
    },
    /**
     * @param {String} host
     * @param {String|Number} port
     * @param {String} baseDir
     * @returns {String}
     */
    initServer: function (host, port, baseDir) {

        var output = "";
        var base = baseDir;

        var template = "{green:Server running. Use this URL:} {magenta:{: url :}}\n";
        output += prefixed(template, {
            url: this._makeUrl(host, port, "http:")
        });

        template = "{green:Serving files from:} {magenta:{: baseDir :}}";

        if (Array.isArray(baseDir)) {
            baseDir.forEach(function (item, i) {
                var prefix  = (i ? "\n" : "");
                output += prefix + prefixed(template, {baseDir: item});
            });
        } else {
            output += prefixed(template, {baseDir: baseDir});
        }

        return output;
    },
    /**
     * @param {String} host
     * @param {String|Number} port
     * @returns {String}
     */
    initProxy: function (host, port) {

        var template = "{green:Proxy running. Use this URL: }{magenta:{: url :}}";

        var params = {
            url: this._makeUrl(host, port, "http:")
        };

        return prefixed(template, params);
    },
    plugin: {
        error: function (name) {
            return "Your plugin must be a function";
        }
    },
    /**
     * Helper for creating a URL
     * @param {String} host
     * @param {String|Number} port
     * @param {String} [protocol]
     * @returns {String}
     */
    _makeUrl: function (host, port, protocol) {

        var string = "//{: host :}:{: port :}";

        if (protocol) {
            string = protocol + string;
        }

        return compile(string, {host: host, port: port});
    },
    /**
     * @param {String} hostIp
     * @param {Object} ports
     * @param {Object} options
     * @param {String} [env]
     * @returns {String}
     */
    scriptTags: function (hostIp, ports, options, env) {

        var template = [
            "<script src='{: socket :}'></script>",
            "<script>{: connector :}</script>",
            "<script src='{: custom :}'></script>"
        ];

        var socket = this._makeUrl(hostIp, ports.socket) + config.socketIoScript;
        var custom = this._makeUrl(hostIp, ports.controlPanel) + this.clientScript(options);

        if (env === "controlPanel") {
            template.pop();
        }

        var params = {
            socket: socket,
            custom: custom,
            connector: this.socketConnector(hostIp, ports.socket)
        };

        return compile(template.join("\n") + "\n", params);
    },
    host: {
        /**
         * Warn about possible problems with multi hosts
         * @returns {String}
         */
        multiple: function () {

            var template = [];
            template.push("Warning: Multiple External IP addresses found");
            template.push("If you have problems, you may need to manually set the 'host' option");

            return prefixed(template);
        }
    },
    /**
     * @returns {String}
     */
    invalidBaseDir: function () {

        var template = "{cyan:Invalid Base Directory path for server. Should be like this ( baseDir: 'path/to/app' )}";

        return prefixed(template);
    },
    ports: {
        /**
         * @param {Number} minCount
         * @returns {String}
         */
        invalid: function (minCount) {
            var template = "{red:Invalid port range!} - At least {:minCount:} required!";
            var params   = {
                minCount: minCount
            };

            return prefixed(template, params);
        }
    },
    config: {
        /**
         * @param {String} filePath
         * @returns {String}
         */
        confirm: function (filePath) {
            var template1  = "Config file created ({cyan:{:path:}})";
            var template2  = "To use it, in the same directory run: {green:browser-sync}";

            var params = {
                "path": path.basename(filePath)
            };

            return prefixed([template1, template2], params);
        }
    },
    files: {
        /**
         * @param {Array} [patterns]
         * @returns {String}
         */
        watching: function (patterns) {

            var string;
            if (Array.isArray(patterns) && patterns.length) {
                string = prefixed("{green:Watching files...}");
            } else {
                string = prefixed("{red:Not watching any files...}");
            }
            return string;
        },
        /**
         * @param {String} fileName
         * @returns {String}
         */
        changed: function (fileName) {
            var string = "{green:File Changed: }{magenta:{: path :}}";
            return prefixed(string, {
                path: path.basename(fileName)
            });
        }
    },
    browser: {
        /**
         * @returns {String}
         */
        reload: function () {
            var string = "{cyan:Reloading all connected browsers...}";
            return prefixed(string);
        },
        /**
         * @returns {String}
         */
        inject: function () {
            var string = "{cyan:Injecting file into all connected browsers...}";
            return prefixed(string);
        },
        /**
         * @param {Object} browser
         * @returns {String}
         */
        connection: function (browser) {

            var template = "{cyan:Browser Connected! ({:name:}, version: {:version:})}";
            var params = {
                name: browser.name,
                version: browser.version
            };

            return prefixed(template, params);
        }
    },
    /**
     * @param {String} url
     * @returns {*}
     */
    location: function (url) {
        var template = "{green:Link clicked! Redirecting all browsers to }{magenta:{: url :}}";
        var params = {
            url: url
        };
        return prefixed(template, params);
    },
    /**
     * @param {String} host
     * @param {String|Number} port
     * @returns {String}
     */
    socketConnector: function (host, port) {
        var string = "var ___socket___ = io.connect('{: url :}');";

        var params = {
            url: this._makeUrl(host, port, "http:")
        };

        return compile(string, params);
    },
    /**
     * @param {Object} [options]
     * @param {Boolean} [both]
     */
    clientScript: function (options, both) {

        var script = "/client/browser-sync-client.js";
        var template = "/client/browser-sync-client.{:version:}.js";

        if (!options || !options.version) {
            return script;
        }

        var params = {
            version: options.version
        };

        var compiled = compile(template, params);

        if (both) {
            return {
                path: script,
                versioned: compiled
            };
        }

        return compiled;
    }
};