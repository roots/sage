"use strict";

var portScannerPlus = require("../lib/index");
var assert = require("chai").assert;
var sinon = require("sinon");
var ps = require("portscanner");
require("mocha-as-promised")();

describe("Getting ports module", function () {

    var psStub;
    before(function () {
        psStub = sinon.stub(ps, "findAPortNotInUse").yields(null, null);
        psStub.onFirstCall().yields(null, 3000);
        psStub.onSecondCall().yields(null, 3001);
    });
    afterEach(function () {
        psStub.reset();
    });
    after(function () {
        psStub.restore();
    });

    describe("the getPorts method", function () {

        it("should have a getPorts method", function () {
            assert.isFunction(portScannerPlus.getPorts);
        });
        it("should return a promise", function () {
            var func = portScannerPlus.getPorts();
            assert.isDefined(func.then);
        });
        it("should return a resolved promise with 1 port", function (done) {
            psStub.yields(null, 3000);
            var expected = 3000;
            return portScannerPlus.getPorts(1, 3000, 4000).then(function (result) {
                return assert.equal(result[0], 3000);
            });
        });
        it("should return a resolved promise with 2 ports", function () {
            var expected1 = 3000;
            var expected2 = 3001;
            return portScannerPlus.getPorts(2, 3000, 4000).then(function (result) {
                return assert.equal(result[1], 3001);
            });
        });
        it("should return a resolved promise with 2 ports (1)", function () {

            var names = ["port1", "port2"];
            return portScannerPlus.getPorts(2, 3000, 4000, names).then(function (result) {
                return assert.equal(result.port1, 3000);
            });
        });
        it("should return a resolved promise with 2 ports (2)", function () {
            var names = ["port1", "port2"];
            return portScannerPlus.getPorts(2, 3000, 4000, names).then(function (result) {
                return assert.equal(result.port2, 3001);
            });
        });
        it("should return a resolved promise with 2 ports & 1 with no name", function () {
            var names = ["port1"];
            return portScannerPlus.getPorts(2, 3000, 4000, names).then(function (result) {
                return assert.equal(result.port2, 3001);
            });
        });
        it("should return a resolved promise with 2 ports & names set to true", function () {
            var names = true;
            return portScannerPlus.getPorts(1, 3000, 4000, names).then(function (result) {
                return assert.equal(result.port1, 3000);
            });
        });
        it("should return a resolved promise with 2 ports & names set to true", function () {
            var names = true;
            return portScannerPlus.getPorts(5, 3000, 3001, names).then(function (result) {
                // should never end here
            }, function (error) {
                return assert.equal(error, "Invalid port range");
            });
        });
    });

    describe("Getting a port range", function () {

        var options;

        beforeEach(function () {
            options = {};
        });

        it("should return the default range if not given in options", function () {
            var actual = portScannerPlus.getPortRange(3);
            assert.equal(actual.min, 3000);
            assert.equal(actual.max, 4000);
        });
        it("should return the correct range when given in options", function () {
            var min = 5000;
            var max = 5100;
            var actual = portScannerPlus.getPortRange(3, min, max);
            assert.equal(actual.min, 5000);
            assert.equal(actual.max, 5100);
        });
        it("should not return false if range is not too small", function () {
            options = {
                ports: {
                    min: 5000,
                    max: 5002
                }
            };
            var actual = portScannerPlus.getPortRange(3, options.ports.min, options.ports.max);
            assert.equal(actual.min, 5000);
            assert.equal(actual.max, 5002);
        });
        it("should return false if range is too small", function () {
            options = {
                ports: {
                    min: 5000,
                    max: 5001
                }
            };
            var actual = portScannerPlus.getPortRange(3, options.ports.min, options.ports.max);
            assert.equal(actual, false);
        });
        it("should use a default for MAX if only min given", function () {
            options = {
                ports: {
                    min: 5000
                }
            };
            var actual = portScannerPlus.getPortRange(3, options.ports.min);
            assert.equal(actual.min, 5000);
            assert.equal(actual.max, 5500);
        });
        it("should MAX out at 9999 (1)", function () {
            options = {
                ports: {
                    min: 9980
                }
            };
            var actual = portScannerPlus.getPortRange(3, options.ports.min);
            assert.equal(actual.min, 9980);
            assert.equal(actual.max, 9999);
        });
        it("should MAX out at 9999 (2)", function () {
            options = {
                ports: {
                    min: 9600
                }
            };
            var actual = portScannerPlus.getPortRange(3, options.ports.min);
            assert.equal(actual.min, 9600);
            assert.equal(actual.max, 9999);
        });
    });

    describe("naming the ports", function () {
        it("can assign names to the 2 required ports", function () {
            var ports = [3000,3001];
            var names = ["socket", "controlPanel"];
            var named = portScannerPlus.assignPortNames(ports, names);
            assert.equal(named.socket, 3000);
            assert.equal(named.controlPanel, 3001);
        });
        it("can assign names to the 2 required ports + client server", function () {
            var ports = [3000,3001,3002];
            var names = ["socket", "controlPanel", "server"];
            var named = portScannerPlus.assignPortNames(ports, names);
            assert.equal(named.socket, 3000);
            assert.equal(named.controlPanel, 3001);
            assert.equal(named.server, 3002);
        });
        it("can assign names to the 2 required ports + client proxy", function () {
            var ports = [3000,3001,3002];
            var names = ["socket", "controlPanel", "proxy"];
            var named = portScannerPlus.assignPortNames(ports, names);
            assert.equal(named.socket, 3000);
            assert.equal(named.controlPanel, 3001);
            assert.equal(named.proxy, 3002);
        });
        it("can assign names to just the ports that have names (1)", function () {
            var ports = [3000,3001,3002];
            var names = ["socket", "controlPanel"];
            var named = portScannerPlus.assignPortNames(ports, names);
            assert.equal(named.port3, 3002);
        });
        it("can assign names to just the ports that have names (2)", function () {
            var ports = [3000,3001,3002];
            var names = ["socket"];
            var named = portScannerPlus.assignPortNames(ports, names);
            assert.equal(named.port2, 3001);
        });
        it("can assign names to just the ports that have names (2)", function () {
            var ports = [3000,3001,3002];
            var names = true;
            var named = portScannerPlus.assignPortNames(ports, names);
            assert.equal(named.port1, 3000);
            assert.equal(named.port2, 3001);
            assert.equal(named.port3, 3002);
        });
    });
});