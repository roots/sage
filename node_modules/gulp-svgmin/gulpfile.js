/* jshint node:true */

'use strict';

var gulp    = require('gulp'),
    gutil   = require('gulp-util'),
    clear   = require('clear'),
    mocha   = require('gulp-mocha'),
    jshint  = require('gulp-jshint'),
    stylish = require('jshint-stylish');

gulp.task('lint', function () {
    gulp.src('*.js')
        .pipe(jshint())
        .pipe(jshint.reporter('jshint-stylish'))
        .pipe(mocha());
});

gulp.task('default', function() {
    gulp.run('lint');
    gulp.watch('*.js', function(event) {
        clear();
        gutil.log(gutil.colors.cyan(event.path.replace(process.cwd(), '')) + ' ' + event.type + '. (' + gutil.colors.magenta(gutil.date('HH:MM:ss')) + ')');
        gulp.run('lint');
    });
});
