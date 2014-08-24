var gulp = require('gulp'),
  less = require('gulp-less'),
  autoprefix = require('gulp-autoprefixer'),
  sourcemaps = require('gulp-sourcemaps'),
  rename = require('gulp-rename'),
  concat = require('gulp-concat'),
  minifyCSS = require('gulp-minify-css'),
  jshint = require('gulp-jshint'),
  uglify = require('gulp-uglify'),
  livereload = require('gulp-livereload'),
  stylish = require('jshint-stylish'),
  rev = require('gulp-rev');
  modernizr = require('gulp-modernizr');

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

gulp.task('less', function () {
  return gulp.src(paths.less)
    .pipe(sourcemaps.init())
      .pipe(less()).on('error', function(err){
        console.warn(err.message);
      })
      .pipe(autoprefix('last 2 versions', 'ie 8', 'ie 9', 'android 2.3', 'android 4', 'opera 12'))
      .pipe(rename('./main.css'))
    .pipe(sourcemaps.write())
    .pipe(gulp.dest(destination.css))
    .pipe(minifyCSS())
    .pipe(rename('./main.min.css'))
    .pipe(gulp.dest(destination.css));
});

gulp.task('jshint', function() {
  return gulp.src(paths.jshint)
    .pipe(jshint())
    .pipe(jshint.reporter(stylish));
});

gulp.task('js', ['jshint'], function() {
  return gulp.src(paths.scripts)
    .pipe(concat('./scripts.js'))
    .pipe(gulp.dest(destination.scripts))
    .pipe(uglify())
    .pipe(rename('./scripts.min.js'))
    .pipe(gulp.dest(destination.scripts));
});

gulp.task('modernizr', function() {
  return gulp.src(
    ['assets/js/scripts.min.js'],
    ['assets/css/main.min.css']
  )
    .pipe(modernizr())
    .pipe(gulp.dest(destination.modernizr))
    .pipe(uglify())
    .pipe(rename('./modernizr.min.js'))
    .pipe(gulp.dest(destination.vendor));
});

gulp.task('version', function() {
  return gulp.src(['assets/css/main.min.css', 'assets/js/scripts.min.js'], { base: 'assets' })
    .pipe(rev())
    .pipe(gulp.dest('assets'))
    .pipe(rev.manifest())
    .pipe(gulp.dest('assets'));
});

gulp.task('watch', function() {
  livereload.listen();

  gulp.watch('assets/less/**/*.less', ['less']).on('change', livereload.changed);
  gulp.watch('assets/js/**/*.js', ['jshint', 'js']).on('change', livereload.changed);
  gulp.watch('**/*.php').on('change', function(file) {
    livereload.changed(file.path);
  });

});

gulp.task('default', ['less', 'jshint', 'js', 'modernizr']);
gulp.task('dev', ['default']);
gulp.task('build', ['less', 'jshint', 'js', 'modernizr', 'version']);
