var gulp = require("gulp");
var karma = require('gulp-karma');
var jshint = require('gulp-jshint');
var contribs = require('gulp-contribs');

var testFiles = [
    'test/todo.js'
];

gulp.task('test', function() {
    // Be sure to return the stream
    return gulp.src(testFiles)
        .pipe(karma({
            configFile: 'test/client/karma.conf.ci.js',
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

gulp.task('lint', function () {
    gulp.src(['test/client/specs/**/*.js', 'lib/js/scripts/*.js', 'index.js'])
        .pipe(jshint('test/.jshintrc'))
        .pipe(jshint.reporter("default"))
        .pipe(jshint.reporter("fail"))
});

gulp.task('contribs', function () {
    gulp.src('README.md')
        .pipe(contribs())
        .pipe(gulp.dest("./"))
});

gulp.task('default', ["lint", "test"]);
