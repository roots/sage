"use strict";

var defaultConfig = require("./default-config");
var cliOptions    = require("./cli-options");
var info          = require("./cli-info");

var program       = require("commander");
var _             = require("lodash");

module.exports.allowedOptions = ["host", "server", "proxy"];

/**
 * @param {String} string
 * @returns {string}
 */
function ucfirst(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

/**
 * Template for creating a method name
 * @param {String} key
 * @returns {String}
 */
module.exports.methodName = function (key) {
    return "_merge%sOption".replace("%s", ucfirst(key));
};

/**
 * @param {Object} obj
 * @param {String} key
 * @param {Object} args
 * @returns {Object}
 */
module.exports.transformOption = function (obj, key, args) {
    if (args[key] && typeof obj[key] !== "undefined") {
        obj[key] = cliOptions[exports.methodName(key)](obj[key], args[key], args);
    }
    return obj;
};

/**
 * @param {Object} defaultConfig
 * @param {Object} args
 * @param {Array} allowedOptions
 * @returns {Object}
 */
module.exports.mergeOptions = function (defaultConfig, args, allowedOptions) {
    return allowedOptions
        .reduce(function (obj, key) {
            return exports.transformOption(obj, key, args);
        }, defaultConfig);
};

/**
 * Handle command-line usage with 'start'
 * @param args
 * @param cb
 */
module.exports.startFromCommandLine = function (args, cb) {

    var options = {};
    var userConfig;

    // First look for provided --config option
    if (args.config) {
        userConfig = info._getConfigFile(args.config);
    } else {
        userConfig = info.getDefaultConfigFile();
    }

    if (userConfig) {
        options = _.merge(defaultConfig, userConfig);
        options = exports.mergeOptions(defaultConfig, options, exports.allowedOptions);
    } else {
        options = exports.mergeOptions(defaultConfig, args, exports.allowedOptions);
        options.files = cliOptions._mergeFilesOption(args.files, options.exclude);
    }

    cb(null, {
        files: options.files || [],
        config: options
    });
};

/**
 * @param {String} version
 * @param {Object} args - optimist object
 * @param {Object} argv - process.argv
 * @param {Function} cb
 */
module.exports.parse = function (version, args, argv, cb) {

    program
        .version(version)
        .usage("<command> [options]")
        .option("--files", "File paths to watch")
        .option("--server", "Run a Local server (uses your cwd as the web root)")
        .option("--directory", "Show a directory listing for the server")
        .option("--proxy", "Proxy an existing server")
        .option("--config", "Specify a path to a bs-config.js file")
        .option("--host", "Specify a hostname to use");

    program
        .on("--help", function(){
            console.log("  Server Example:");
            console.log("  ---------------");
            console.log("    Use current directory as root & watch CSS files");
            console.log("");
            console.log("    $ browser-sync start --server --files=\"css/*.css\"");
            console.log("");
            console.log("  Proxy Example:");
            console.log("  --------------");
            console.log("    Proxy `localhost:8080` & watch CSS files");
            console.log("");
            console.log("    $ browser-sync start --proxy=\"localhost:8080\" --files=\"css/*.css\"");
            console.log("");
        });

    program
        .command("init")
        .description("Creates a default config file")
        .action(function() {
            cb(null, {configFile: true});
        });

    program
        .command("start")
        .description("Start Browser Sync")
        .action(exports.startFromCommandLine.bind(null, args, cb));

    program.parse(argv);

    if (!args._.length) {
        program.help();
    }
};

