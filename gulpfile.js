/*global $:true*/
var gulp = require('gulp');
var $ = require('gulp-load-plugins')();
var pngcrush = require('imagemin-pngcrush');
var mainBowerFiles = require('main-bower-files');

var paths = {
  scripts: [
    'assets/js/**/*.js',
    '!assets/js/vendor/**/*',
    '!assets/js/scripts*.js'
  ],
  jshint: [
    'gulpfile.js',
    'assets/js/*.js',
    '!assets/js/scripts.js',
    '!assets/js/scripts.min.js',
    '!assets/js/vendor/**/*',
    '!assets/**/*.min-*'
  ],
  less: 'assets/less/main.less',
  bower: mainBowerFiles()
};

var destination = {
  css: 'assets/css',
  scripts: 'assets/js',
  modernizr: 'assets/vendor/modernizr',
  vendor: 'assets/js/vendor'
};

gulp.task('less', function() {
  return gulp.src(paths.less)
    .pipe($.plumber())
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
  return gulp.src(paths.bower.concat(paths.scripts))
    .pipe($.filter(['**/*.js', '!jquery.js', '!modernizr.js']))
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

gulp.task('images', function () {
  return gulp.src('assets/img/**/*')
    .pipe($.imagemin({
      progressive: true,
      interlaced: true,
      use: [pngcrush()]
    }))
    .pipe(gulp.dest('assets/img'));
});

gulp.task('bust', function () {
  $.cache.clearAll();
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
