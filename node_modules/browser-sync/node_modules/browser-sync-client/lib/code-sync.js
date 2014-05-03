"use strict";

var options = {

    tagNames: {
        "css":  "link",
        "jpg":  "img",
        "jpeg": "img",
        "png":  "img",
        "svg":  "img",
        "gif":  "img",
        "js":   "script"
    },
    attrs: {
        "link":   "href",
        "img":    "src",
        "script": "src"
    }
};

/**
 * @param {BrowserSync} bs
 */
exports.init = function (bs) {
    bs.socket.on("file:reload", exports.reload(bs));
    bs.socket.on("browser:reload", function () {
        exports.reloadBrowser(true);
    });
};

/**
 * @param elem
 * @param attr
 * @param opts
 * @returns {{elem: HTMLElement, timeStamp: number}}
 */
exports.swapFile = function (elem, attr, opts) {

    var currentValue = elem[attr];
    var timeStamp = new Date().getTime();
    var suffix = "?rel=" + timeStamp;

    var justUrl = /^[^\?]+(?=\?)/.exec(currentValue);

    if (justUrl) {
        currentValue = justUrl[0];
    }

    if (opts) {
        if (!opts.timestamps) {
            suffix = "";
        }
    }

    elem[attr] = currentValue + suffix;

    return {
        elem: elem,
        timeStamp: timeStamp
    };
};

/**
 * @param {BrowserSync} bs
 * @returns {*}
 */
exports.reload = function (bs) {

    /**
     * @param data - from socket
     */
    return function (data) {

        var transformedElem;
        var opts    = bs.opts;
        var emitter = bs.emitter;

        if (data.url || !opts.injectChanges) {
            exports.reloadBrowser(true);
        }

        if (data.assetFileName && data.fileExtension) {

            var domData = exports.getElems(data.fileExtension);
            var elems   = exports.getMatches(domData.elems, data.assetFileName, domData.attr);

            if (elems.length && opts.notify) {
                emitter.emit("notify", {message: "Injected: " + data.assetFileName});
            }

            for (var i = 0, n = elems.length; i < n; i += 1) {
                transformedElem = exports.swapFile(elems[i], domData.attr, opts);
            }
        }

        return transformedElem;
    };
};

/**
 * @param fileExtension
 * @returns {*}
 */
exports.getTagName = function (fileExtension) {
    return options.tagNames[fileExtension];
};

/**
 * @param tagName
 * @returns {*}
 */
exports.getAttr = function (tagName) {
    return options.attrs[tagName];
};

/**
 * @param elems
 * @param url
 * @param attr
 * @returns {Array}
 */
exports.getMatches = function (elems, url, attr) {

    var matches = [];

    for (var i = 0, len = elems.length; i < len; i += 1) {
        if (elems[i][attr].indexOf(url) !== -1) {
            matches.push(elems[i]);
        }
    }

    return matches;
};

/**
 * @param fileExtension
 * @returns {{elems: NodeList, attr: *}}
 */
exports.getElems = function(fileExtension) {

    var tagName = exports.getTagName(fileExtension);
    var attr    = exports.getAttr(tagName);

    return {
        elems: document.getElementsByTagName(tagName),
        attr: attr
    };
};

/**
 * @returns {window}
 */
exports.getWindow = function () {
    return window;
};

/**
 * @param confirm
 */
exports.reloadBrowser = function (confirm) {
    var $window = exports.getWindow();
    if (confirm) {
        $window.location.reload(true);
    }
};