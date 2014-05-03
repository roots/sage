/**
 *
 * Install:
 *      npm install browser-sync
 *
 * Run:
 *      node <yourfile.js>
 *
 * This example shows how you can access information about BrowserSync when it's running
 *
 */

var browserSync = require("browser-sync");

var files   = ["app/css/*.css"];
var config = {
    proxy: "localhost:8000"
};

browserSync.init(files, config, function (err, bs) {
    // Full access to BrowserSync object here
    console.log(bs.api.snippet);
    console.log(bs.options.url);
});

