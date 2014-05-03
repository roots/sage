var express = require("express");
var app = express();
var fs = require("fs");

app.use(express.bodyParser());
app.use(express.methodOverride());

// load liveReload script only in development mode
app.configure("development", function () {
    // live reload script
    var livereload = require("../index.js");

    function buume(w, s) {
        return "\n\n joggeli buume \n\n" + w;
    }

    function pfluume(w, s) {
        return "\n\n het gern pfluume \n\n" + w;
    }

    app.use(livereload({
        rules: [
            {
                match: /<\/body>/,
                fn: buume
            },
            {
                match: /<\/head>/,
                fn: pfluume
            },
            {
                match: new RegExp("0.0.0.0:8000", "g"),
                fn: function () {
                    return "19.16.565.67:3002"
                }
            }
        ]
    }));
});

// load static content before routing takes place
app.use(express["static"](__dirname + "/fixtures"));

// load the routes
app.use(app.router);

app.get("/body", function (req, res) {
    var html = "<!DOCTYPE html><body>fettwanz auf dem tanz</body>";
    res.send(html);
});

app.get("/head", function (req, res) {
    var html = "<head><title>head without body </title></head>";
    res.send(html);
});

app.get("/default", function (req, res) {
    var html = "<html><title>plain html</title></html>";
    res.send(html);
});

app.get("/url", function (req, res) {
    var html = fs.readFileSync(__dirname + "/fixtures/static.html", "UTF-8");
    res.send(html);
});
app.get("/url-large", function (req, res) {
    var html = fs.readFileSync(__dirname + "/fixtures/large-file.html", "UTF-8");
    res.send(html);
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

describe("Rules: ", function () {

    describe("GET /body", function () {
        it("respond with inserted 'buume'", function (done) {
            request(app)
                .get("/body")
                .set("Accept", "text/html")
                .expect(200)
                .end(function (err, res) {
                    assert(res.text.indexOf("buume") > 1);
                    if (err) {
                        return done(err);
                    }
                    done();
                });
        });
    });

    describe("GET /head", function () {
        it("respond with inserted 'pfluume", function (done) {
            request(app)
                .get("/head")
                .set("Accept", "text/html")
                .expect(200)
                .end(function (err, res) {
                    assert(res.text.indexOf("pfluume") > 1);
                    if (err) {
                        return done(err);
                    }
                    done();
                });
        });
    });

    describe("GET /default", function () {
        it("not have any, 'buume' or 'pfluume' inserted", function (done) {
            request(app)
                .get("/default")
                .set("Accept", "text/html")
                .expect(200)
                .end(function (err, res) {
                    assert.equal(res.text.indexOf("pfluume"), -1);
                    assert.equal(res.text.indexOf("buume"), -1);
                    if (err) {
                        return done(err);
                    }
                    done();
                });
        });
    });
    describe("GET /url", function () {
        it("can re-write links", function (done) {
            request(app)
                .get("/url")
                .set("Accept", "text/html")
                .expect(200)
                .end(function (err, res) {
                    assert.equal(true, res.text.indexOf("0.0.0.0:8000") < 0);
                    assert.equal(true, res.text.indexOf("19.16.565.67:3002") >= 0);
                    if (err) {
                        return done(err);
                    }
                    done();
                });
        });
    });
    describe("GET /url-large", function () {
        it("can re-write links", function (done) {
            request(app)
                .get("/url-large")
                .set("Accept", "text/html")
                .expect(200)
                .end(function (err, res) {
                    assert.equal(true, res.text.indexOf("</html>") >= 0);
                    done();
                });
        });
    });
});
