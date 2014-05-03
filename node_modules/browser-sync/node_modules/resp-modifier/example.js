var connect = require("connect");
var respMod = require("./index.js");
var http = require("http");

var app = connect()
    .use(respMod({
        rules: [
            {
                match: /<head[^>]*>/,
                fn: function (w) {
                    return w + "Your string";
                }
            }
        ]
    }))
    .use(connect.static(filePath.resolve("./")));

var server = http.createServer(app).listen(8000);