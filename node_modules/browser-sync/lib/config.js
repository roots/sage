var path = require("path");
/**
 * @type {{controlPanel: {jsFile: string, baseDir: *}, socketIoScript: string, configFile: string, client: {shims: string}}}
 */
module.exports = {
    controlPanel: {
        jsFile: "/js/app.js",
        baseDir: path.resolve(__dirname + "/control-panel")
    },
    socketIoScript: "/socket.io/socket.io.js",
    configFile: "/bs-config.js",
    client: {
        shims: "/client/client-shims.js"
    }
};