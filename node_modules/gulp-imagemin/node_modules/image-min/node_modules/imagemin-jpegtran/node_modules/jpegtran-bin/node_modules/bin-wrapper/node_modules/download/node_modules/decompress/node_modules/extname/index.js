'use strict';

var endsWith = require('underscore.string').endsWith;
var extList = require('ext-list');
var map = require('map-key');
var path = require('path');

/**
 * Get the file extension and MIME type from a file
 *
 * @param {String} str
 * @api public
 */

module.exports = function (str) {
    var obj = {};
    var key = Object.keys(extList).sort(function (a, b) {
        return b.length - a.length;
    });

    for (var i = 0; i < Object.keys(extList).length; i++) {
        obj[key[i]] = extList[key[i]];
    }

    var mime = map(obj, str);
    var ext = Object.keys(obj).filter(function (key) {
        return endsWith(str, key);
    })[0] || path.extname(str);

    return mime ? { ext: ext, mime: mime } : { ext: ext };
};
