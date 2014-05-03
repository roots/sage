var express = require("express");
var app = express();

app.use(express.bodyParser());
app.use(express.methodOverride());

var matcher = "<b>thisString</b>";

// load liveReload script only in development mode
app.configure("development", function () {
    // live reload script
    var livereload = require("../index.js");
    app.use(livereload({
        rules: [
            {
                match: /set_length/g,
                fn: function () {
                    return matcher;
                }
            }
        ],
        port: 35729
    }));
});

// load static content before routing takes place
app.use(express["static"](__dirname + "/fixtures"));

// load the routes
app.use(app.router);

app.get("/set_length", function (req, res) {
    var html = "<html><head></head><body><p>set_length</p></body></html>";
    res.writeHead(200, {
        "content-length": html.length,
        "Content-Type": "text/html"
    });
    res.end(html);
});

app.get("/set_length2", function (req, res) {
    var html = "<html><head></head><body><p>set_length2</p></body></html>";
    res.write("<!DOCTYPE html>");
    res.writeHead(200, {
        "Content-Length": html.length,
        "Content-Type": "text/html"
    });
    res.end(html);
});

// start the server
if (!module.parent) {
    var port = settings.webserver.port || 3000;
    app.listen(port);
    console.log("Express app started on port " + port);
}

// run the tests
var request = require("supertest");
var assert = require("assert");

function hasText(html) {
    return (~html.indexOf(matcher));
}

describe("GET /set_length", function () {
    it("should", function (done) {
        request(app)
            .get("/set_length")
            .set("Accept", "text/html")
            .expect(200)
            .end(function (err, res) {
                assert(hasText(res.text));
                if (err) {
                    return done(err);
                }
                done();
            });
    });
});