var gulp   = require('gulp'),
    gutil  = require('gulp-util'),
    jshint = require('gulp-jshint'),
    less   = require('gulp-less'),
    minify = require('gulp-minify-css'),
    concat = require('gulp-concat'),
    uglify = require('gulp-uglify'),
    rename = require('gulp-rename');

gulp.task('lint', function() {
  /*
   * We are ignoring assets/plugins and assets/vendor as we expect these to be 
   * functional and we can't assume any particular JSHint options
   *
   * We're also ignoring scripts.min.js 
   */
  return gulp.src(['assets/js/*.js', '!assets/js/scripts.min.js'])
    .pipe(jshint())
    .pipe(jshint.reporter('default'));
});

gulp.task('less', function() {
  /*
   * Grab any .less files in our assets/less folder (not including subdirectories)
   * and the main file for Bootstrap (the one with the imports!)
   *
   * Crunch it all down into assets/css/main.min.css
   */
  return gulp.src(['assets/less/*.less', 'assets/less/bootstrap/bootstrap.less'])
    .pipe(less())
    .pipe(concat('main.min.css'))
    .pipe(minify())
    .pipe(gulp.dest('assets/css'));
});

gulp.task('scripts', function() {
  /*
   * Combine all JS files in assets/js and uglify them into assets/js/scripts.min.js
   */
  return gulp.src(['!assets/js/scripts.min.js', 'assets/js/*.js', 'assets/js/plugins/*/*.js', 'assets/js/plugins/*.js'])
    .pipe(concat('scripts.min.js'))
    .pipe(uglify())
    .pipe(gulp.dest('assets/js'));
});

gulp.task('watch', function() {
  // Watch our JS files for changes
  gulp.watch(['!assets/js/scripts.min.js', 'assets/js/*.js', 'assets/js/plugins/*/*.js', 'assets/js/plugins/*.js'], ['lint', 'scripts']);

  // Watch our LESS files for changes
  gulp.watch(['assets/less/bootstrap/*.less', 'assets/less/*.less'], ['less']);
});

gulp.task('default', ['lint', 'scripts', 'less']);