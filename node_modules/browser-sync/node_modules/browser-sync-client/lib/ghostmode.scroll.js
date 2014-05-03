"use strict";

/**
 * This is the plugin for syncing scroll between devices
 * @type {string}
 */
var EVENT_NAME = "scroll";
var utils;

exports.canEmitEvents = true;

/**
 * @param {BrowserSync} bs
 * @param eventManager
 */
exports.init = function (bs, eventManager) {
    utils = bs.utils;
    eventManager.addEvent(window, EVENT_NAME, exports.browserEvent(bs));
    bs.socket.on(EVENT_NAME, exports.socketEvent(bs));
};

/**
 * @param {BrowserSync} bs
 */
exports.socketEvent = function (bs) {

    return function (data) {

        var scrollSpace = utils.getScrollSpace();

        exports.canEmitEvents = false;

        if (!bs.canSync(data)) {
            return false;
        }

        if (bs.opts && bs.opts.scrollProportionally) {
            return window.scrollTo(0, scrollSpace.y * data.position.proportional); // % of y axis of scroll to px
        } else {
            return window.scrollTo(0, data.position.raw);
        }
    };
};

/**
 * @param socket
 */
exports.browserEvent = function (bs) {

    return function () {

        var canSync = exports.canEmitEvents;

        if (canSync) {
            bs.socket.emit(EVENT_NAME, {
                position: exports.getScrollPosition()
            });
        }

        exports.canEmitEvents = true;
    };
};


/**
 * @returns {{raw: number, proportional: number}}
 */
exports.getScrollPosition = function () {
    var pos = utils.getBrowserScrollPosition();
    return {
        raw: pos, // Get px of y axis of scroll
        proportional: exports.getScrollTopPercentage(pos) // Get % of y axis of scroll
    };
};

/**
 * @param {{x: number, y: number}} scrollSpace
 * @param scrollPosition
 * @returns {{x: number, y: number}}
 */
exports.getScrollPercentage = function (scrollSpace, scrollPosition) {

    var x = scrollPosition.x / scrollSpace.x;
    var y = scrollPosition.y / scrollSpace.y;

    return {
        x: x || 0,
        y: y
    };
};

/**
 * Get just the percentage of Y axis of scroll
 * @returns {number}
 */
exports.getScrollTopPercentage = function (pos) {
    var scrollSpace = utils.getScrollSpace();
    var percentage  = exports.getScrollPercentage(scrollSpace, pos);
    return percentage.y;
};