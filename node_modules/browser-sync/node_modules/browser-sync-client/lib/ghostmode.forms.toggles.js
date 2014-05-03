"use strict";

/**
 * This is the plugin for syncing clicks between browsers
 * @type {string}
 */
var EVENT_NAME  = "input:toggles";
exports.canEmitEvents = true;

/**
 * @param {BrowserSync} bs
 * @param eventManager
 */
exports.init = function (bs, eventManager) {
    var browserEvent = exports.browserEvent(bs);
    exports.addEvents(eventManager, browserEvent);
    bs.socket.on(EVENT_NAME, exports.socketEvent(bs, eventManager));
};

/**
 * @param eventManager
 * @param event
 */
exports.addEvents = function (eventManager, event) {

    var elems   = document.getElementsByTagName("select");
    var inputs  = document.getElementsByTagName("input");

    addEvents(elems);
    addEvents(inputs);

    function addEvents(domElems) {
        for (var i = 0, n = domElems.length; i < n; i += 1) {
            eventManager.addEvent(domElems[i], "change", event);
        }
    }
};

/**
 * @param {BrowserSync} bs
 * @returns {Function}
 */
exports.browserEvent = function (bs) {

    return function (event) {

        if (exports.canEmitEvents) {
            var elem = event.target || event.srcElement;
            var data;
            if (elem.type === "radio" || elem.type === "checkbox" || elem.tagName === "SELECT") {
                data = bs.utils.getElementData(elem);
                data.type    = elem.type;
                data.value   = elem.value;
                data.checked = elem.checked;
                bs.socket.emit(EVENT_NAME, data);
            }
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

            exports.canEmitEvents = false;

            var elem = bs.utils.getSingleElement(data.tagName, data.index);

            if (elem) {
                if (data.type === "radio") {
                    elem.checked = true;
                }
                if (data.type === "checkbox") {
                    elem.checked = data.checked;
                }
                if (data.tagName === "SELECT") {
                    elem.value = data.value;
                }
                return elem;
            }
            return false;
        }

        return false;
    };
};