"use strict";

var _ = require("lodash");
var q = require("q");
var portScanner = require("portscanner");

/**
 * @param {Array} ports
 * @param {Array} names
 * @returns {Object}
 */
var assignPortNames = function (ports, names) {
    return ports.reduce(function (obj, port, i) {
        if (!names[i]) {
            obj["port" + (i + 1)] = port;
        } else {
            obj[names[i]] = port;
        }
        return obj;
    }, {});
};
module.exports.assignPortNames = assignPortNames;


/**
 * Get port range with default fallbacks
 * @param {Number} minCount
 * @param {Number} [min]
 * @param {Number} [max]
 * @returns {{min: (Number), max: (Number)}
     */
var getPortRange = function (minCount, min, max) {

    if (min && max) {
        if ((max - min + 1) < minCount) {
            return false;
        }
        return {
            min: min,
            max: max
        };
    }

    if (min) {
        max = min + 500;
        return {
            min: min,
            max: max < 10000 ? max : 9999
        };
    }

    return {
        min: 3000,
        max: 4000
    };
};
module.exports.getPortRange = getPortRange;

/**
 * @param {Number} count
 * @param {Number} min
 * @param {Number} max
 * @param {Boolean|Array} names
 * @returns {Q.promise}
 */
var getPorts = function (count, min, max, names) {

    var ports = [];

    var deferred = q.defer();

    var range = getPortRange(count, min, max);

    if (!range) {
        deferred.reject("Invalid port range");
    }

    var lastFound = range.min - 1;

    // get a port (async)
    var getPort = function () {
        portScanner.findAPortNotInUse(lastFound + 1, range.max, "localhost", function (error, port) {
            ports.push(port);
            lastFound = port;
            runAgain();
        });
    };

    // run again if number of ports not reached
    var runAgain = function () {
        if (ports.length < count) {
            getPort();
        } else {
            if (names) {
                ports = assignPortNames(ports, names);
            }
            deferred.resolve(ports);
        }
        return false;
    };

    // Get the first port
    getPort();

    return deferred.promise;

};
module.exports.getPorts = getPorts;