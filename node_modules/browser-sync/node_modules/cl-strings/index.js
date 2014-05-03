var chalk = require("chalk");
var _ = require("lodash");

_.templateSettings.interpolate = /{:([\s\S]+?):}/g

var compile = function (template, params, prefix) {

    /**
     * @param string
     * @returns {String}
     */
    var modifier = function (string) {

        var split = /{(.+?[^:]):(.+?)(?:})/.exec(string);

        var color = split[1];
        var content = split[2];

        return chalk[color](content);
    };

    /**
     * @param string
     * @returns {String}
     */
    var replacer = function (string) {
        return modifier(string);
    };


    if (params) {
        template = _.template(template)(params);
    }

    if (prefix) {
        template = [prefix, template].join(" ");
    }

    return template.replace(/({.+?[^:]:)(.+?)(?:})/g, replacer);
};

module.exports.compile = compile;

/**
 * Alternative way to retrieve the compiler, this time with a prefix
 * @param {String} prefix
 * @returns {Function}
 */
module.exports.getCompiler = function (prefix) {
    return function (template, params) {

        var output = [];

        if (Array.isArray(template)) {

            template.forEach(function (item) {
                output.push(compile(item, params, prefix));
            });

            return output.join("\n");
        }

        return compile(template, params, prefix);
    };
};

module.exports.clc = chalk;
module.exports.chalk = chalk;
