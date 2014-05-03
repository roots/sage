"use strict";

var messages     = require("./messages");
var snippetUtils = require("./snippet").utils;

var httpProxy    = require("http-proxy");

var utils = {
    /**
     * @param userServer
     * @param proxyUrl
     * @returns {{match: RegExp, fn: Function}}
     */
    rewriteLinks: function (userServer, proxyUrl) {

        var string = "";
        var host = userServer.host;
        var port = userServer.port;

        if (host && port) {
            string = host;
            if (parseInt(port, 10) !== 80) {
                string = host + ":" + port;
            }
        } else {
            string = host;
        }

        return {
            match: new RegExp("['\"]([htps:/]+)?"+string+".*?(?='|\")", "g"),
            fn: function (match) {
                return match.replace(new RegExp(string), proxyUrl);
            }
        };
    },
    /**
     * Remove Headers from a response
     * @param {Object} headers
     * @param {Array} items
     */
    removeHeaders: function (headers, items) {
        items.forEach(function (item) {
            if (headers.hasOwnProperty(item)) {
                delete headers[item];
            }
        });
    },
    /**
     * Get the proxy host with optional port
     */
    getProxyHost: function (opts) {
        if (opts.port && opts.port !== 80) {
            return opts.host + ":" + opts.port;
        }
        return opts.host;
    },
    /**
     * @param opts
     * @returns {string}
     */
    getProxyUrl: function (opts) {
        return opts.protocol + "://" + utils.getProxyHost(opts);
    },
    /**
     * Handle redirect urls
     * @param {String} url
     * @param {Object} opts
     * @param {String} host
     * @param {Number} port
     * @returns {String}
     */
    handleRedirect: function (url, opts, host, port) {

        var fullHost  = opts.host + ":" + opts.port;
        var proxyHost = host + ":" + port;

        if (~url.indexOf(fullHost)) {
            return url.replace(fullHost, proxyHost);
        } else {
            return url.replace(opts.host, proxyHost);
        }
    }
};
module.exports.utils = utils;

/**
 * @param {String} host
 * @param {Object} ports
 * @param {Object} options
 * @param {Function} [reqCallback]
 */
module.exports.createProxy = function (host, ports, options, reqCallback) {

    var proxyOptions = options.proxy;
    var proxyUrl = host + ":" + ports.proxy;
    var rewriteLinks = utils.rewriteLinks(proxyOptions, proxyUrl);
    var scriptTags = messages.scriptTags(host, ports, options);
    var proxy = httpProxy.createProxyServer({});
    var snippetMw = snippetUtils.getSnippetMiddleware(scriptTags, rewriteLinks);

    var server = require("http").createServer(function(req, res) {

        req.headers["accept-encoding"] = "identity";

        var next = function () {
            proxy.web(req, res, {
                target: utils.getProxyUrl(proxyOptions),
                headers: {
                    host: proxyOptions.host
                }
            });
        };

        req = snippetUtils.isOldIe(req);

        if (reqCallback) {
            reqCallback(req, res);
        }
        snippetMw(req, res, next);
    });

    //
    // Remove content-length to allow snippets to inserted to any body length
    //
    proxy.on("proxyRes", function (res) {
        if (res.statusCode === 302) {
            res.headers.location = utils.handleRedirect(res.headers.location, options.proxy, host, ports.proxy);
        }
        utils.removeHeaders(res.headers, ["content-length", "content-encoding"]);
    });

    return server;
};
