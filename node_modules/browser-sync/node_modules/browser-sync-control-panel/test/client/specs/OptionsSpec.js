"use strict";

describe("Main Controller", function () {

    beforeEach(module("BrowserSync"));

    var mainCtrl;
    var scope;
    var options;
    var spy;

    before(function () {
//        spy = sinon.spy();
    });

    beforeEach(inject(function ($rootScope, $controller, $injector) {
        scope = $rootScope.$new();
        options = $injector.get("Options");
    }));

    afterEach(function () {
//        spy.reset();
    });

});