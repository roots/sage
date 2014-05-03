"use strict";

/**
 * This is the plugin for syncing location
 * @type {string}
 */
var EVENT_NAME = "location";
exports.canEmitEvents = true;

/**
 * @param {BrowserSync} bs
 */
exports.init = function (bs) {
    bs.socket.on(EVENT_NAME, exports.socketEvent());
};

/**
 * Respond to socket event
 */
exports.socketEvent = function () {
    return function (data) {
        window.location = data.url;
    };
};