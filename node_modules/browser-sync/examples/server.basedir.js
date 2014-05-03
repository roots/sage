/**
 *
 * Install:
 *      npm install browser-sync
 *
 * Run:
 *      node <yourfile.js>
 *
 * This example will create a server & use the `app` directory as the root
 *
 */

var browserSync = require("browser-sync");

browserSync.init(["app/css/*.css"], {
    server: {
        baseDir: "app"
    }
});

