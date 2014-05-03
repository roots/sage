"use strict";


var browser1 = {
    id: "2323",
    name: "Chrome",
    version: "32.0.1700.107",
    width: 100,
    height: 200
};
var browser2 = {
    id: "23aa",
    name: "Firefox",
    version: "12.322",
    width: 800,
    height: 212
};
var cpBrowser = {
    id: "0000",
    name: "Firefox",
    version: "12.322",
    width: 800,
    height: 212
};

describe("Main Controller", function () {

    beforeEach(module("BrowserSync"));

    var mainCtrl;
    var scope;
    var socket;
    var spy;

    before(function () {
//        spy = sinon.spy();
    });

    beforeEach(inject(function ($rootScope, $controller, $injector) {
        scope = $rootScope.$new();

        socket = $injector.get("Socket");
        spy = sinon.spy(socket, "addEvent");
        mainCtrl = $controller("MainCtrl", {
            $scope: scope
        });

    }));

    afterEach(function () {
//        spy.reset();
    });

    it("should be available", function () {
        assert.isDefined(mainCtrl);
    });
    it("should have an empty options object", function () {
        assert.isDefined(scope.options);
    });
    it("should have an empty browsers object", function () {
        assert.isDefined(scope.browsers);
    });
    it("should have a socket id property", function () {
        assert.isDefined(scope.socketId);
    });
    it("should have a socketEvents object on the scope", function () {
        assert.isDefined(scope.socketEvents);
    });
    it("should have a socketEvents.connection callback", function () {
        assert.isDefined(scope.socketEvents.connection);
    });
    it("should add the connection event", function () {
        sinon.assert.calledWithExactly(spy, "connection", scope.socketEvents.connection);
    });

    // EVENTS
    it("should add a single browser to the scope", function () {
        scope.socketEvents.addBrowsers([browser1, browser2]);
        assert.equal(scope.browsers.length, 2);
        assert.equal(scope.browsers[0].name, "Chrome");
    });
    it("should not add the control panel to the list of devices", function () {
        scope.socketId = "0000";
        scope.socketEvents.addBrowsers([browser1, browser2, cpBrowser]);
        assert.equal(scope.browsers.length, 2);
    });

    it("should set options on the scope", function () {
        var options  = { name: "kittens" };
        scope.socketEvents.connection(options);
        var actual = scope.options.name;
        var expected = "kittens";
        assert.equal(actual, expected);
    });

    // Toggle Snippet
    it("should initially have the snippet hidden", function () {
        assert.equal(scope.ui.snippet, false);
    });
    it("should the snippet", function () {
        scope.toggleSnippet();
        assert.equal(scope.ui.snippet, true);
    });
});