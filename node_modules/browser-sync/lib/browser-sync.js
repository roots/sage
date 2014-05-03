"use strict";

var serverModule    = require("./server");
var services        = require("./services");
var socket          = require("./sockets");
var logger          = require("./logger");
var config          = require("./config");
var utils           = require("./utils").utils;
var fileWatcher     = require("./file-watcher");

var bsControlPanel  = require("browser-sync-control-panel");
var bsClient        = require("browser-sync-client");
var _               = require("lodash");
var filePath        = require("path");
var events          = require("events");

var defaultPlugins = {
    "plugin:client:script": bsClient.middleware,
    "plugin:file:watcher": fileWatcher.plugin,
    "plugin:socket": socket.plugin,
    "plugin:logger": logger.plugin,
    "plugin:controlpanel": bsControlPanel.plugin
};

/**
 * @constructor
 */
var BrowserSync = function () {
    this.cwd = process.cwd();
    this.minPorts = 2;
    this.events = new events.EventEmitter();
    this.events.setMaxListeners(20);
    this.plugins = {};
    this.config = config;
    this.clientEvents = [
        "scroll",
        "input:text",
        "input:toggles",
        "form:submit",
        "form:reset",
        "click"
    ];
};

/**
 * Allow plugins to be registered from outside
 * @param {String} name
 * @param {Function} func
 * @param {Function} [cb]
 */
BrowserSync.prototype.registerPlugin = function (name, func, cb) {
    if ("function" !== typeof func) {
        return typeof cb === "function" ? cb("Plugin must be a function.") : false;
    }
    return this.plugins["plugin:" + name] = func(); // every plugin should be a callable function
};

/**
 * Load default plugins.
 */
BrowserSync.prototype.loadPlugins = function () {

    var required = Object.keys(defaultPlugins);

    required.forEach(function (name) {
        if (typeof this.plugins[name] === "undefined") {
            this.plugins[name] = defaultPlugins[name]();
        }
    }, this);

    return true;
};

/**
 * @param name
 * @returns {*}
 */
BrowserSync.prototype.getPlugin = function (name) {
    return this.plugins["plugin:" + name] || false;
};

/**
 * @param {Array} files
 * @param {Object} options
 * @param {String} version
 * @param {Function} cb
 * @returns {BrowserSync}
 */
BrowserSync.prototype.init = function (files, options, version, cb) {

    var err;
    this.version = options.version = version;
    this.cb = cb;

    this.loadPlugins();
    this.getPlugin("logger")(this.events, options);

    // Die if both server & proxy options provided
    if (options.server && options.proxy) {
        err = "Invalid config. You cannot specify both a server & proxy option.";
        this.callback(err);
        utils.fail(err, options, true);
    }

    var success = function (ports) {
        services.init(this)(ports, files, options);
    }.bind(this);

    var error = function (err) {
        this.callback(err);
        utils.fail(err, options, true);
    }.bind(this);

    utils.getPorts(options, this.minPorts)
        .then(success)
        .catch(error);

    return this;
};

/**
 * Callback helper
 * @param err
 * @param [data]
 */
BrowserSync.prototype.callback = function (err, data) {
    if ("function" === typeof this.cb) {
        this.cb(err, data, this);
    }
};

/**
 * Internal Events
 * @param {Object} options
 */
BrowserSync.prototype.registerInternalEvents = function (options) {

    var events = {
        "file:changed": function (data) {
            this.changeFile(data.path, options);
        },
        "file:reload": function (data) {
            this.io.sockets.emit("file:reload", data);
        },
        "browser:reload": function () {
            this.io.sockets.emit("browser:reload");
        },
        "browser:notify": function (data) {
            this.io.sockets.emit("browser:notify", data);
        }
    };

    _.each(events, function (func, event) {
        this.events.on(event, func.bind(this));
    }, this);
};

/**
 * @param {String} path
 * @param {Object} options
 * @returns {{assetFileName: String}}
 */
BrowserSync.prototype.changeFile = function (path, options) {

    var fileName = filePath.basename(path);
    var fileExtension = utils.getFileExtension(path);

    var data = {
        assetFileName: fileName,
        fileExtension: fileExtension
    };

    var message = "inject";

    // RELOAD page
    if (!_.contains(options.injectFileTypes, fileExtension)) {
        data.url = path;
        message = "reload";
    }

    data.cwd = this.cwd;
    data.path = path;
    data.type = message;

    // emit the event through socket
    this.events.emit("file:reload", data);

    return data;
};

/**
 * Launch the server or proxy
 * @param {String} host
 * @param {Object} ports
 * @param {Object} options
 * @param {Object} io
 * @returns {*|http.Server}
 */
BrowserSync.prototype.initServer = function (host, ports, options, io) {

    var proxy = options.proxy || false;
    var server = options.server || false;
    var baseDir = utils.getBaseDir(server.baseDir || "./");
    var type = false;

    var servers = serverModule.launchServer(host, ports, options, io);

    if (server) {
        if (servers.staticServer) {
            servers.staticServer.listen(ports.server);
        }
        type = "server";
    }

    if (proxy) {
        if (servers.proxyServer) {
            servers.proxyServer.listen(ports.proxy);
        }
        type = "proxy";
    }

    options.url = utils.getUrl(utils._makeUrl(host, ports[type]), options);

    if (type && (server || proxy)) {

        utils.openBrowser(options.url, options);

        this.events.emit("open", {
            type: type,
            baseDir: baseDir || null,
            port: ports[type]
        });
    }

    return servers;
};

module.exports = BrowserSync;