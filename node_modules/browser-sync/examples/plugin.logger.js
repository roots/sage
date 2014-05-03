var browserSync = require("browser-sync");

var files = ["test/fixtures/assets/*", "test/fixtures/*.html"];

var options = {
    open: false
};

// Override the logger with your own implementation
browserSync.use("logger", function () {
    return function (emitter, options) {
        // Listen to events on the emitter
        emitter.on("init", function (data) {
            // Do something awesome
            console.log("BrowserSync Started");
        });
    };
});

browserSync.init(files, options);