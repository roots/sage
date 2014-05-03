"use strict";

var messages = require("./messages");
var config   = require("./config");

var fs       = require("fs");

module.exports = {
    /**
     * Version info
     * @param {Object} pjson
     * @returns {String}
     */
    getVersion: function (pjson) {
        console.log(pjson.version);
        return pjson.version;
    },
    /**
     * @returns {Object}
     * @private
     */
    getDefaultConfigFile: function () {
        var defaultPath = process.cwd() + config.configFile;
        return this._getConfigFile(defaultPath);
    },
    /**
     * Retrieve the config file
     * @param {String} path
     * @returns {*}
     * @private
     */
    _getConfigFile: function (path) {
        if (fs.existsSync(path)) {
            return require(fs.realpathSync(path));
        }
        return false;
    },
    /**
     * Generate an example Config file.
     */
    makeConfig: function () {
        var file = fs.readFileSync(__dirname + config.configFile);
        var path = process.cwd() + config.configFile;
        fs.writeFile(path, file, this.confirmConfig(path));
    },
    /**
     * @param {String} path
     * @returns {Function}
     */
    confirmConfig: function (path) {
        return function () {
            console.log(messages.config.confirm(path));
        };
    }
};