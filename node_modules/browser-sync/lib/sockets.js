"use strict";

var socket = require("socket.io");

/**
 * Plugin interface
 * @returns {*|function(this:exports)}
 */
module.exports.plugin = function () {
    return function (port, events, options, emitter) {
        return exports.init(port, events, options, emitter);
    };
};

/**
 * @param client
 * @param event
 */
module.exports.clientEvent = function (client, event) {
    client.on(event, function (data) {
        client.broadcast.emit(event, data);
    });
};

/**
 * @param {Array} events
 * @param {Object} options
 * @param {Socket} io
 * @param {EventEmitter} emitter
 */
module.exports.socketConnection = function (events, options, io, emitter) {

    var ua;

    io.sockets.on("connection", function (client) {

        // set ghostmode callbacks
        if (options.ghostMode) {
            events.forEach(function (evt) {
                exports.clientEvent(client, evt);
            });
        }

        client.emit("connection", options);

        ua = client.handshake.headers["user-agent"];

        emitter.emit("client:connected", {ua: ua});
    });
};

/**
 * @param {Number} port
 * @param {Array} events
 * @param {Object} options
 * @param {EventEmitter} emitter
 * @returns {http.Server}
 */
module.exports.init = function (port, events, options, emitter) {
    var io = socket.listen(port, {log: false});
    io.set("log level", 0);
    if (options.minify) {
        io.set("browser client minification", true);
        if (process.platform !== "win32") {
            io.set("browser client gzip", true);
        }
    }
    exports.socketConnection(events, options, io, emitter);
    return io;
};