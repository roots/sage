"use strict";

var messages = require("./messages");

module.exports.getApi = function (ports, options, servers) {

    var snippet = messages.scriptTags(options.host, ports, options);

    return {
        snippet: snippet,
        options: options,
        servers: servers
    };
};