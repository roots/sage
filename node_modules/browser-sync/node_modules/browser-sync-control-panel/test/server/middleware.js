"use strict";

var controlPanel = require("../../index.js")();

var http         = require("http");
var request      = require("supertest");
var connect      = require("connect");
var assert       = require("chai").assert;

var cp = controlPanel();

describe("Using the middleware", function () {

    it("return a function for the middleware", function () {
        assert.isFunction(cp.middleware);
    });

    it("should return the basedir for serving the control panel", function (done) {

        var testApp = connect()
            .use(cp.middleware)
            .use(connect.static(cp.baseDir));

        request(testApp)
            .get("/")
            .expect(200)
            .end(function (err, res, req) {
                if (err) {
                    throw err;
                } else {
                    assert.isTrue(res.text.indexOf("BrowserSync") > -1);
                    done();
                }
            });
    });
});