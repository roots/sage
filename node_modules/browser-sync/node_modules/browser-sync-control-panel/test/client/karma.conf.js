// Karma configuration
// Generated on Wed Sep 18 2013 18:10:53 GMT+0100 (BST)

module.exports = function (config) {
    config.set({

        // base path, that will be used to resolve files and exclude
        basePath: '../../',

        // frameworks to use
        frameworks: ['mocha'],


        // list of files / patterns to load in the browser
        files: [

            "lib/js/vendor/angular.min.js",
            "test/client/vendor/angular-mocks.js",

            // Test Libs
            "node_modules/sinon/pkg/sinon.js",
            "node_modules/chai/chai.js",

            // Setup stuff
            "test/client/setup.js",
            "lib/js/scripts/app.js",

            // Specs
            "test/client/specs/*.js"
        ],


        // list of files to exclude
        exclude: [

        ],


        // test results reporter to use
        // possible values: 'dots', 'progress', 'junit', 'growl', 'coverage'
        reporters: ['dots'],


        // web server port
        port: 9876,


        // enable / disable colors in the output (reporters and logs)
        colors: true,


        // level of logging
        // possible values: config.LOG_DISABLE || config.LOG_ERROR || config.LOG_WARN || config.LOG_INFO || config.LOG_DEBUG
        logLevel: config.LOG_INFO,


        // enable / disable watching file and executing tests whenever any file changes
        autoWatch: true,

        // Start these browsers, currently available:
        // - Chrome
        // - ChromeCanary
        // - Firefox
        // - Opera
        // - Safari (only Mac)
        // - PhantomJS
        // - IE (only Windows)
        browsers: ['Firefox'],


        // If browser does not capture in given timeout [ms], kill it
        captureTimeout: 60000,

        preprocessors: {
            'fixtures/*.html' : ['html2js']
        },

        // Continuous Integration mode
        // if true, it capture browsers, run tests and exit
        singleRun: false

    });
};
