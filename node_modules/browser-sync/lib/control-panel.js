"use strict";

var messages     = require("./messages");
var config       = require("./config");

var connect      = require("connect");
var http         = require("http");

/**
 * Launch the server for serving the client JS plus static files
 * @param {Object} options
 * @param {Function} scripts
 * @param {{middleware: Function, baseDir: String}} controlPanel
 * @returns {http.Server}
 */
module.exports.launchControlPanel = function (options, scripts, controlPanel) {

    var clientScripts = messages.clientScript(options, true);

    var app =
        connect()
            .use(clientScripts.versioned, scripts)
            .use(clientScripts.path, scripts);

            if (controlPanel) {
                app.use(controlPanel.middleware);
                app.use(connect.static(controlPanel.baseDir));
            }

    return http.createServer(app);
};
