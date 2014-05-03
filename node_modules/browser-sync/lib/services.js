"use strict";

var controlPanel = require("./control-panel");
var messages     = require("./messages");
var api          = require("./api");
var utils        = require("./utils").utils;
var snippetUtils = require("./snippet").utils;

module.exports.init = function (context) {

    return function (ports, files, options) {

        var servers;

        this.io = this.getPlugin("socket")(ports.socket, this.clientEvents, options, this.events);

        // register internal events
        this.registerInternalEvents(options);

        options.host = utils.xip(utils.getHostIp(options), options);

        // Start file watcher
        this.getPlugin("file:watcher")(files, options, this.events);

        // launch the server/proxy
        servers = this.initServer(options.host, ports, options, this.io);

        if (!servers) {
            this.events.emit("snippet", {ports: ports});
        }

        // Always Launch the control panel
        var snippet = messages.scriptTags(options.host, ports, options, "controlPanel");

        var cpPlugin = this.getPlugin("controlpanel");
        var cp;

        if (cpPlugin) {
            cp = cpPlugin(options, snippet, this);
        }

        controlPanel
            .launchControlPanel(options, this.getPlugin("client:script")(options), cp)
            .listen(ports.controlPanel);

        this.options = options;

        // get/emit the api
        this.api = api.getApi(ports, options, servers);

        this.events.emit("init", this);

        this.callback(null, this);

    }.bind(context);
};