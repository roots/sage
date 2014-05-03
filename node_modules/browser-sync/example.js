
var browserSync = require("./lib/index");

console.time("init");

var files = ["test/fixtures/assets/*", "test/fixtures/*.html"];

var options = {
    server: {
        baseDir: "test/fixtures"
    },
    ghostMode: {
        forms: {
            submit: false
        }
    },
    open: true,
    logConnections: false,
    minify: false,
    ports: {
        min: 2000
    },
    notify: true
};

//var clientScript = require("/Users/shakyshane/Sites/browser-sync-modules/browser-sync-client/index");
//
//browserSync.use("client:script", clientScript.middleware, function (err) {
//    console.log(err);
//});

var bs = browserSync.init(files, options, function (err, bs) {
    setTimeout(function () {
        browserSync.notify("5 Seconds have passed!");
    }, 5000);
});
