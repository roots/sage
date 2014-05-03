"use strict";

/**
 * This is the plugin for syncing clicks between browsers
 * @type {string}
 */
var EVENT_NAME  = "form:submit";
exports.canEmitEvents = true;

/**
 * @param {BrowserSync} bs
 * @param eventManager
 */
exports.init = function (bs, eventManager) {
    var browserEvent = exports.browserEvent(bs);
    eventManager.addEvent(document.body, "submit", browserEvent);
    eventManager.addEvent(document.body, "reset", browserEvent);
    bs.socket.on(EVENT_NAME, exports.socketEvent(bs, eventManager));
};

/**
 * @param {BrowserSync} bs
 * @returns {Function}
 */
exports.browserEvent = function (bs) {

    return function (event) {
        if (exports.canEmitEvents) {
            var elem = event.target || event.srcElement;
            var data = bs.utils.getElementData(elem);
            data.type = event.type;
            bs.socket.emit(EVENT_NAME, data);
        } else {
            exports.canEmitEvents = true;
        }
    };
};

/**
 * @param {BrowserSync} bs
 * @returns {Function}
 */
exports.socketEvent = function (bs) {

    return function (data) {
        if (bs.canSync(data)) {
            var elem = bs.utils.getSingleElement(data.tagName, data.index);
            exports.canEmitEvents = false;
            if (elem && data.type === "submit") {
                elem.submit();
            }
            if (elem && data.type === "reset") {
                elem.reset();
            }
            return false;
        }
        return false;
    };
};