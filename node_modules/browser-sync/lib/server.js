"use strict";

var proxyModule  = require("./proxy");
var messages     = require("./messages");
var snippetUtils = require("./snippet").utils;

var connect      = require("connect");
var http         = require("http");
var filePath     = require("path");

var utils = {
    /**
     * The middleware that can emit location change events.
     * @param {Object} io
     * @param {Object} options
     * @returns {Function}
     */
    navigateCallback: function (io, options) {

        var disabled = false;
        var navigating = false;
        var canNavigate = this.canNavigate;

        return function (req, res, next) {

            if (canNavigate(req, options, io)) {

                var clients = io.sockets.clients();

                if (clients.length && !disabled && !navigating) {

                    navigating = true;
                    disabled = true;

                    io.sockets.emit("location", {
                        url: req.url
                    });

                    var timer = setTimeout(function () {

                        disabled = false;
                        navigating = false;
                        clearInterval(timer);

                    }, 300);
                }
            }
            if (typeof next === "function") {
                next();
            }
        };
    },
    /**
     * All the conditions that determine if we should emit
     * a location:change event
     * @param {Object} req
     * @param {Object} options
     * @returns {Boolean}
     */
    canNavigate: function (req, options) {

        var headers = req.headers || {};

        if (req.method !== "GET") {
            return false;
        }

        if (headers["x-requested-with"] !== undefined && headers["x-requested-with"] === "XMLHttpRequest") {
            return false;
        }

        if (!options || !options.ghostMode || !options.ghostMode.location) {
            return false;
        }

        if (snippetUtils.isExcluded(req.url, options.excludedFileTypes)) {
            return false;
        }

        return true;
    },
    /**
     * @param app
     * @param middleware
     * @returns {*}
     */
    addMiddleware: function (app, middleware) {

        if (Array.isArray(middleware)) {
            middleware.forEach(function (item) {
                app.use(item);
            });
        } else if (typeof middleware === "function") {
            app.use(middleware);
        }

        return app;
    },
    /**
     * @param app
     * @param base
     * @param index
     */
    addBaseDir: function (app, base, index) {
        if (Array.isArray(base)) {
            base.forEach(function (item) {
                app.use(connect.static(filePath.resolve(item)));
            });
        } else {
            app.use(connect.static(filePath.resolve(base), { index: index }));
        }
    },
    /**
     * @param app
     * @param base
     */
    addDirectory: function (app, base) {
        app.use(connect.directory(base, {icons:true}));
    }
};
module.exports.utils = utils;

/**
 * Launch the server for serving the client JS plus static files
 * @param {String} host
 * @param {{socket: (Number), controlPanel: (Number)}} ports
 * @param {Object} options
 * @param {socket} io
 * @returns {{staticServer: (http.Server), proxyServer: (http.Server)}|Boolean}
 */
module.exports.launchServer = function (host, ports, options, io) {

    var app;
    var proxy       = options.proxy || false;
    var server      = options.server || false;
    var staticServer;
    var proxyServer;
    var navCallback = utils.navigateCallback(io, options);
    var scriptTags  = options.snippet = messages.scriptTags(host, ports, options);

    if (proxy) {
        proxyServer = proxyModule.createProxy(host, ports, options, navCallback);
    }

    if (server) {

        var baseDir     = server.baseDir;
        var index       = server.index || "index.html";
        var directory   = server.directory;

        app = connect();

        if (server.middleware) {
            utils.addMiddleware(app, server.middleware);
        }

        app.use(function (req, res, next) {
                req = snippetUtils.isOldIe(req);
                return next();
            })
            .use(navCallback)
            .use(snippetUtils.getSnippetMiddleware(scriptTags));


        utils.addBaseDir(app, baseDir, index);

        if (directory) {
            utils.addDirectory(app, baseDir);
        }

        staticServer = http.createServer(app);
    }

    if (!staticServer && !proxyServer) {
        return false;
    } else {
        return {
            staticServer: staticServer,
            proxyServer: proxyServer
        };
    }
};
