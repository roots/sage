"use strict";

var lrSnippet = require("resp-modifier");
var path      = require("path");
var _         = require("lodash");

/**
 * Utils for snippet injection
 * @type {{excludeList: string[], bodyExists: bodyExists, isExcluded: isExcluded}}
 */
var utils = {
    /**
     * @param {String} url
     * @param {Array} excludeList
     * @returns {boolean}
     */
    isExcluded: function (url, excludeList) {

        var extension = path.extname(url);

        if (extension) {

            if (~url.indexOf("?")) {
                return true;
            }
            extension = extension.slice(1);
            return _.contains(excludeList, extension);
        }
        return false;
    },
    /**
     * @param {String} snippet
     * @returns {Function}
     */
    appendSnippet: function (snippet) {
        return function (w) {
            return w + snippet;
        };
    },
    /**
     * @param {string} snippet
     * @returns {{match: RegExp, fn: Function}}
     */
    getRegex: function (snippet) {
        return {
            match: /<body[^>]*>/i,
            fn: utils.appendSnippet(snippet)
        };
    },
    /**
     * @param {String} snippet
     * @param {Object} [extraRules]
     * @returns {Function}
     */
    getSnippetMiddleware: function (snippet, extraRules) {

        var rules = [utils.getRegex(snippet)];

        if (extraRules) {
            rules.push(extraRules);
        }

        return lrSnippet({rules:rules});
    },
    /**
     * @param {Object} req
     * @param {Array} [excludeList]
     * @returns {Object}
     */
    isOldIe: function (req, excludeList) {
        var ua = req.headers["user-agent"];
        var match = /MSIE (\d)\.\d/.exec(ua);
        if (match) {
            if (parseInt(match[1], 10) < 9) {
                if (!utils.isExcluded(req.url, excludeList)) {
                    req.headers["accept"] = "text/html";
                }
            }
        }
        return req;
    }
};
module.exports.utils = utils;
