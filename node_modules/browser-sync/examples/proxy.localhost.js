/**
 *
 * Install:
 *      npm install browser-sync
 *
 * Run:
 *      node <yourfile.js>
 *
 * This example will wrap your existing server in a proxy url.
 * Use the new Proxy url to access your site.
 *
 */

var browserSync = require("browser-sync");

browserSync.init(["app/css/*.css"], {
    proxy: "localhost:8000"
});

