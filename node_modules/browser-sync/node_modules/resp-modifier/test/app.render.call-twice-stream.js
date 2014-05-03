var express = require("express");
var app = express();
var fs = require("fs");

app.use(express.bodyParser());
app.use(express.methodOverride());

var matcher = "resp-mod-tested";
// load liveReload script only in development mode
app.configure("development", function () {
    // live reload script
    var livereload = require("../index.js");
    app.use(livereload({
        rules: [
            {
                match: /resp-mod/,
                fn: function (w) {
                    return w + "-tested";
                }
            }
        ],
        ignore: [".woff", ".flv"]
    }));

    // live reload script
    var livereload2 = require("../index.js");
    app.use(livereload({
        port: 35731,
        ignore: [".hammel"]
    }));
});

// load static content before routing takes place
app.use(express["static"](__dirname + "/fixtures"));

// load the routes
app.use(app.router);

app.get("/stream", function (req, res) {
    var stream = fs.createReadStream(__dirname + "/fixtures/large-file.html");
    stream.pipe(res);
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

describe("GET /stream", function () {
    it("respond with inserted script", function (done) {
        request(app)
            .get("/stream")
            .set("Accept", "text/html")
            .expect(200)
            .end(function (err, res) {
                assert(res.text.indexOf(matcher) > 1);
                if (err) {
                    return done(err);
                }
                done();
            });
    });
});