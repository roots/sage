var gulp   = require('gulp'),
    gutil  = require('gulp-util'),
    jshint = require('gulp-jshint'),
    less   = require('gulp-less'),
    minify = require('gulp-minify-css'),
    concat = require('gulp-concat'),
    uglify = require('gulp-uglify'),
    rename = require('gulp-rename'),
    rev    = require('gulp-wp-rev');

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
   * Grab our main LESS file (app.less) which imports all other files
   *
   * Crunch it all down into assets/css/main.min.css
   */
  return gulp.src('assets/less/app.less')
    .pipe(less({
      sourceMap: true
    }))
    .pipe(concat('main.min.css'))
    .pipe(minify())
    .pipe(gulp.dest('assets/css'));
});

gulp.task('scripts', function() {
  /*
   * Concatenate JS files in the following order and uglify them
   *
   * Output to assets/scripts.min.js
   */
  return gulp.src([
      'assets/js/plugins/bootstrap/transition.js',
      'assets/js/plugins/bootstrap/alert.js',
      'assets/js/plugins/bootstrap/button.js',
      'assets/js/plugins/bootstrap/carousel.js',
      'assets/js/plugins/bootstrap/collapse.js',
      'assets/js/plugins/bootstrap/dropdown.js',
      'assets/js/plugins/bootstrap/modal.js',
      'assets/js/plugins/bootstrap/tooltip.js',
      'assets/js/plugins/bootstrap/popover.js',
      'assets/js/plugins/bootstrap/scrollspy.js',
      'assets/js/plugins/bootstrap/tab.js',
      'assets/js/plugins/bootstrap/affix.js',
      'assets/js/plugins/*.js',
      'assets/js/_*.js',
      '!assets/js/scripts.min.js'
    ])
    .pipe(concat('scripts.min.js'))
    .pipe(uglify())
    .pipe(gulp.dest('assets/js'));
});

gulp.task('rev', function () {
    return gulp.src('lib/scripts.php')
        .pipe(rev({
            css: "assets/css/main.min.css",
            cssHandle: "roots_main",
            js: "assets/js/scripts.min.js",
            jsHandle: "roots_scripts"
        }))
        .pipe(gulp.dest('lib'));
});

gulp.task('watch', function() {
  // Watch our JS files for changes
  gulp.watch(['!assets/js/scripts.min.js', 'assets/js/*.js', 'assets/js/plugins/*/*.js', 'assets/js/plugins/*.js'], ['lint', 'scripts', 'rev']);
  gutil.log('Watching scripts');

  // Watch our LESS files for changes
  gulp.watch(['assets/less/bootstrap/*.less', 'assets/less/*.less'], ['less', 'rev']);
  gutil.log('Watching styles');

});

gulp.task('default', ['lint', 'scripts', 'less']);