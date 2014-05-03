var gulp       = require("gulp");
var karma      = require('gulp-karma');
var jshint     = require('gulp-jshint');
var contribs   = require('gulp-contribs');
var browserify = require('gulp-browserify');
var uglify     = require('gulp-uglify');
var through2   = require('through2');
var rename     = require('gulp-rename');

var testFiles = [
    'test/todo.js'
];

gulp.task('test', function() {
    // Be sure to return the stream
    return gulp.src(testFiles)
        .pipe(karma({
            configFile: 'test/karma.conf.ci.js',
            action: 'run'
        }));
});

gulp.task('test:watch', function() {
    gulp.src(testFiles)
        .pipe(karma({
            configFile: 'test/karma.conf.js',
            action: 'watch'
        }));
});

gulp.task('lint-test', function () {
    gulp.src(['test/client-new/*.js', 'test/middleware/*.js'])
        .pipe(jshint('test/.jshintrc'))
        .pipe(jshint.reporter("default"))
        .pipe(jshint.reporter("fail"))
});

gulp.task('lint-lib', function () {
    gulp.src(['lib/*', '!lib/browser-sync-client.js', '!lib/events.js'])
        .pipe(jshint('lib/.jshintrc'))
        .pipe(jshint.reporter("default"))
        .pipe(jshint.reporter("fail"))
});

gulp.task('contribs', function () {
    gulp.src('README.md')
        .pipe(contribs())
        .pipe(gulp.dest("./"))
});

/**
 * Strip debug statements
 * @returns {*}
 */
var stripDebug = function () {
    return through2.obj(function (file, enc, cb) {
        var string = file.contents.toString();
        var regex  = /\/\*\*debug:start\*\*\/[\s\S]*\/\*\*debug:end\*\*\//g;
        var stripped = string.replace(regex, "");
        file.contents = new Buffer(stripped);
        this.push(file);
        cb();
    });
};

// Basic usage
gulp.task('build-dist', function() {
    // Single entry point to browserify
    gulp.src('lib/index.js')
        .pipe(browserify())
        .pipe(gulp.dest('./dist'))
        .pipe(stripDebug())
        .pipe(uglify())
        .pipe(rename("index.min.js"))
        .pipe(gulp.dest('./dist'));
});

gulp.task('build-dev', function() {
    // Single entry point to browserify
    gulp.src('lib/index.js')
        .pipe(browserify())
        .pipe(gulp.dest('./dist'))
});

gulp.task("dev", ['build-dist'], function () {
    gulp.watch(["lib/*.js", "test/client-new/**/*.js"], ['build-dist']);
});

gulp.task('default', ["lint-lib", "lint-test", "build-dist", "test"]);
