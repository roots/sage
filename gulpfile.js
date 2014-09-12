/*global $:true*/
var gulp = require('gulp');
var $ = require('gulp-load-plugins')();

var paths = {
  scripts: [
    'assets/vendor/bootstrap/js/transition.js',
    'assets/vendor/bootstrap/js/alert.js',
    'assets/vendor/bootstrap/js/button.js',
    'assets/vendor/bootstrap/js/carousel.js',
    'assets/vendor/bootstrap/js/collapse.js',
    'assets/vendor/bootstrap/js/dropdown.js',
    'assets/vendor/bootstrap/js/modal.js',
    'assets/vendor/bootstrap/js/tooltip.js',
    'assets/vendor/bootstrap/js/popover.js',
    'assets/vendor/bootstrap/js/scrollspy.js',
    'assets/vendor/bootstrap/js/tab.js',
    'assets/vendor/bootstrap/js/affix.js',
    'assets/js/plugins/*.js',
    'assets/js/_*.js'
  ],
  jshint: [
    'gulpfile.js',
    'assets/js/*.js',
    '!assets/js/scripts.js',
    '!assets/js/scripts.min.js',
    '!assets/**/*.min-*'
  ],
  less: 'assets/less/main.less'
};

var destination = {
  css: 'assets/css',
  scripts: 'assets/js',
  modernizr: 'assets/vendor/modernizr',
  vendor: 'assets/js/vendor'
};

gulp.task('less', function() {
  return gulp.src(paths.less)
    .pipe($.sourcemaps.init())
      .pipe($.less()).on('error', function(err) {
        console.warn(err.message);
      })
      .pipe($.autoprefixer('last 2 versions', 'ie 8', 'ie 9', 'android 2.3', 'android 4', 'opera 12'))
      .pipe($.rename('./main.css'))
    .pipe($.sourcemaps.write())
    .pipe(gulp.dest(destination.css))
    .pipe($.minifyCss())
    .pipe($.rename('./main.min.css'))
    .pipe(gulp.dest(destination.css))
    .pipe($.livereload({ auto: false }));
});

gulp.task('jshint', function() {
  return gulp.src(paths.jshint)
    .pipe($.jshint())
    .pipe($.jshint.reporter('jshint-stylish'))
    .pipe($.jshint.reporter('fail'));
});

gulp.task('js', ['jshint'], function() {
  return gulp.src(paths.scripts)
    .pipe($.concat('./scripts.js'))
    .pipe(gulp.dest(destination.scripts))
    .pipe($.uglify())
    .pipe($.rename('./scripts.min.js'))
    .pipe(gulp.dest(destination.scripts))
    .pipe($.livereload({ auto: false }));
});

gulp.task('modernizr', function() {
  return gulp.src(
    ['assets/js/scripts.min.js'],
    ['assets/css/main.min.css']
  )
    .pipe($.modernizr())
    .pipe(gulp.dest(destination.modernizr))
    .pipe($.uglify())
    .pipe($.rename('./modernizr.min.js'))
    .pipe(gulp.dest(destination.vendor));
});

gulp.task('version', function() {
  return gulp.src(['assets/css/main.min.css', 'assets/js/scripts.min.js'], { base: 'assets' })
    .pipe($.rev())
    .pipe(gulp.dest('assets'))
    .pipe($.rev.manifest())
    .pipe(gulp.dest('assets'));
});

gulp.task('watch', function() {
  $.livereload.listen();
  gulp.watch('assets/less/**/*.less', ['less']);
  gulp.watch('assets/js/**/*.js', ['jshint', 'js']);
  gulp.watch('**/*.php').on('change', function(file) {
    $.livereload.changed(file.path);
  });
});

gulp.task('default', ['less', 'jshint', 'js', 'modernizr']);
gulp.task('dev', ['default']);
gulp.task('build', ['less', 'jshint', 'js', 'modernizr', 'version']);
