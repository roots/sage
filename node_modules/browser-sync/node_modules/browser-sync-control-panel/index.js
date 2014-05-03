"use strict";

var lrSnippet = require("resp-modifier");

/**
 * Return a response modifying middleware.
 * @param snippet
 * @returns {*}
 */
function getMiddleware(snippet) {
    var rules = [{
        match: /<!-- BrowserSync:scripts -->/i,
        fn: function () {
            return snippet;
        }
    }];
    return lrSnippet({rules:rules});
}

/**
 * @returns {Function}
 */
module.exports.plugin = function () {
    return function (options, snippet, bs) {
        return {
            middleware: getMiddleware(snippet),
            baseDir: __dirname + "/lib"
        };
    };
};