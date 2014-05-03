"use strict";

/**
 * @returns {window}
 */
exports.getWindow = function () {
    return window;
};

/**
 *
 * @returns {HTMLDocument}
 */
exports.getDocument = function () {
    return document;
};

/**
 * @type {{getScrollPosition: getScrollPosition, getScrollSpace: getScrollSpace}}
 */
exports.utils = {
    /**
     * Cross-browser scroll position
     * @returns {{x: number, y: number}}
     */
    getBrowserScrollPosition: function () {

        var $window   = exports.getWindow();
        var $document = exports.getDocument();
        var scrollX;
        var scrollY;
        var dElement = $document.documentElement;
        var dBody    = $document.body;

        if ($window.pageYOffset !== undefined) {
            scrollX = $window.pageXOffset;
            scrollY = $window.pageYOffset;
        } else {
            scrollX = dElement.scrollLeft || dBody.scrollLeft || 0;
            scrollY = dElement.scrollTop || dBody.scrollTop || 0;
        }

        return {
            x: scrollX,
            y: scrollY
        };
    },
    /**
     * @returns {{x: number, y: number}}
     */
    getScrollSpace: function () {
        var $document = exports.getDocument();
        var dElement = $document.documentElement;
        var dBody    = $document.body;
        return {
            x: dBody.scrollHeight - dElement.clientWidth,
            y: dBody.scrollHeight - dElement.clientHeight
        };
    },
    /**
     * @param tagName
     * @param elem
     * @returns {*|number}
     */
    getElementIndex: function (tagName, elem) {
        var allElems = document.getElementsByTagName(tagName);
        return Array.prototype.indexOf.call(allElems, elem);
    },
    /**
     * Force Change event on radio & checkboxes (IE)
     */
    forceChange: function (elem) {
        elem.blur();
        elem.focus();
    },
    /**
     * @param elem
     * @returns {{tagName: (elem.tagName|*), index: *}}
     */
    getElementData: function (elem) {
        var tagName = elem.tagName;
        var index   = exports.utils.getElementIndex(tagName, elem);
        return {
            tagName: tagName,
            index: index
        };
    },
    /**
     * @param {string} tagName
     * @param {number} index
     */
    getSingleElement: function (tagName, index) {
        var elems = document.getElementsByTagName(tagName);
        return elems[index];
    },
    /**
     *
     */
    getBody: function () {
        return document.getElementsByTagName("body")[0];
    }
};