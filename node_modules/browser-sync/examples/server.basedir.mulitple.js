/**
 *
 * Install:
 *      npm install browser-sync
 *
 * Run:
 *      node <yourfile.js>
 *
 * This example will create a server & use the `app` & `dist` directories for serving files
 *
 */

var browserSync = require("browser-sync");

browserSync.init(["app/css/*.css"], {
    server: {
        baseDir: ["app", "dist"]
    }
});

