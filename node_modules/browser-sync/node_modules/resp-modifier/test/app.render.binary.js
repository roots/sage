var express = require("express");
var app = express();
var fs = require("fs");

app.use(express.bodyParser());
app.use(express.methodOverride());

// load liveReload script only in development mode
app.configure("development", function () {
    // live reload script
    var livereload = require("../index.js");
    app.use(livereload({
        ignore: [".woff", ".flv"]
    }));
});
// load the routes
app.use(app.router);

// load static content before routing takes place
app.use(express["static"](__dirname + "/fixtures"));

// start the server
if (!module.parent) {
    var port = settings.webserver.port || 3000;
    app.listen(port);
    console.log("Express app started on port " + port);
}

// run the tests
var request = require("supertest");
var assert = require("assert");

describe("GET /favicon.ico", function () {
    it("respond with inserted script", function (done) {
        request(app)
            .get("/favicon.ico")
            .set("Accept", "text/html")
            .expect(200)
            .parse(function (res) {
                res.chunk = "";
                res.on("data", function (chunk) {
                    res.chunk += chunk;
                });
                res.on("end", function () {
                    assert(res.chunk.length > 0);
                    done();
                });
            })
            .end(function (err) {
                assert(!err);
                if (err) {
                    return done(err);
                }
            });
    });
});

describe("GET /font.ttf", function () {
    it("respond with inserted script", function (done) {
        request(app)
            .get("/font.ttf")
            .set("Accept", "text/html")
            .expect(200)
            .parse(function (res) {
                res.chunk = "";
                res.on("data", function (chunk) {
                    res.chunk += chunk;
                });
                res.on("end", function () {
                    assert(res.chunk.length > 0);
                    done();
                });
            })
            .end(function (err) {
                assert(!err);
                if (err) {
                    return done(err);
                }
            });
    });
});