"use strict";

var scroll = require("./ghostmode.scroll");

var styles = [
    "background-color: black",
    "color: white",
    "padding: 10px",
    "display: none",
    "font-family: sans-serif",
    "position: absolute",
    "z-index: 9999",
    "right: 0px",
    "border-bottom-left-radius: 5px"
];

var browserSync;
var elem;
var options;

/**
 * @param {BrowserSync} bs
 * @returns {*}
 */
exports.init = function (bs) {

    browserSync = bs;
    options     = bs.opts;

    var cssStyles = styles;

    if (options.notify.styles) {
        cssStyles = options.notify.styles;
    }

    elem = document.createElement("DIV");
    elem.id = "__bs_notify__";
    elem.style.cssText = cssStyles.join(";");
    document.getElementsByTagName("body")[0].appendChild(elem);

    var flashFn = exports.watchEvent();

    browserSync.emitter.on("notify", flashFn);
    browserSync.socket.on("browser:notify", flashFn);

    return elem;
};

/**
 * @returns {Function}
 */
exports.watchEvent = function() {
    return function (data) {
        exports.flash(data.message);
    };
};

/**
 *
 */
exports.getElem = function () {
    return elem;
};

/**
 * @returns {number|*}
 */
exports.getScrollTop = function () {
    return browserSync.utils.getBrowserScrollPosition().y;
};

/**
 * @param message
 * @param [timeout]
 * @returns {*}
 */
exports.flash = function (message, timeout) {

    var elem = exports.getElem();

    // return if notify was never initialised
    if (!elem) {
        return false;
    }

    var html = document.getElementsByTagName("HTML")[0];
    html.style.position = "relative";

    elem.innerHTML = message;
    elem.style.top = exports.getScrollTop() + "px";
    elem.style.display = "block";

    window.setTimeout(function () {
        elem.style.display = "none";
    }, timeout || 2000);

    return elem;
};