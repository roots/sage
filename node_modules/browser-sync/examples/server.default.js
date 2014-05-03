/**
 *
 * Install:
 *      npm install browser-sync
 *
 * Run:
 *      node <yourfile.js>
 *
 * This example will create a server in the cwd.
 *
 */

var browserSync = require("browser-sync");

browserSync.init(["app/css/*.css"], {
    server: true
});

