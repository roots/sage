/*global $:true*/
var gulp = require('gulp');

var $ = require('gulp-load-plugins')();

var paths = {
  scripts: [
    'assets/src/js/**/*'
  ],
  jshint: [
    'bower.json',
    'gulpfile.js',
    'assets/src/js/**/*'
  ],
  less: 'assets/src/less/main.less',
  editorStyle: 'assets/src/less/editor-style.less'
};

gulp.task('less:dev', function() {
  return gulp.src(paths.less)
    .pipe($.plumber())
    .pipe($.sourcemaps.init())
      .pipe($.less()).on('error', function(err) {
        console.warn(err.message);
      })
      .pipe($.autoprefixer('last 2 versions', 'ie 8', 'ie 9', 'android 2.3', 'android 4', 'opera 12'))
    .pipe($.sourcemaps.write())
    .pipe($.rename('./main.css'))
    .pipe(gulp.dest('assets/dist/css'))
    .pipe($.livereload({ auto: false }));
});

gulp.task('less:build', function() {
  return gulp.src(paths.less)
    .pipe($.plumber())
      .pipe($.less()).on('error', function(err) {
        console.warn(err.message);
      })
      .pipe($.autoprefixer('last 2 versions', 'ie 9', 'android 2.3', 'android 4', 'opera 12'))
      .pipe($.rename('./main.min.css'))
    .pipe($.minifyCss())
    .pipe(gulp.dest('assets/dist/css'));
});

gulp.task('less:editorStyle', function() {
  return gulp.src(paths.editorStyle)
    .pipe($.plumber())
    .pipe($.less()).on('error', function(err) {
      console.warn(err.message);
    })
    .pipe($.autoprefixer('last 2 versions', 'ie 9', 'android 2.3', 'android 4', 'opera 12'))
    .pipe($.rename('./editor-style.css'))
    .pipe(gulp.dest('assets/dist/css'));
});

gulp.task('jshint', function() {
  return gulp.src(paths.jshint)
    .pipe($.jshint())
    .pipe($.jshint.reporter('jshint-stylish'))
    .pipe($.jshint.reporter('fail'));
});

gulp.task('js:dev', ['jshint'], function() {
  return gulp.src(require('main-bower-files')().concat(paths.scripts))
    .pipe($.filter('**/*.js'))
    .pipe($.concat('./scripts.js'))
    .pipe(gulp.dest('assets/dist/js'))
    .pipe($.livereload({ auto: false }));
});

gulp.task('js:build', ['jshint'], function() {
  return gulp.src(require('main-bower-files')().concat(paths.scripts))
    .pipe($.filter('**/*.js'))
    .pipe($.concat('./scripts.min.js'))
    .pipe($.uglify())
    .pipe(gulp.dest('assets/dist/js'));
});

gulp.task('copy:fonts', function() {
  return gulp.src(require('main-bower-files')().concat('asset/src/fonts/**/*'))
    .pipe($.filter('**/*.{eot,svg,ttf,woff}'))
    .pipe(gulp.dest('assets/dist/fonts'));
});

gulp.task('copy:jquery', function() {
  return gulp.src(['bower_components/jquery/dist/jquery.min.js'])
    .pipe($.rename('jquery-1.11.1.min.js'))
    .pipe(gulp.dest('assets/dist/js'));
});

gulp.task('copy:modernizr', function() {
  return gulp.src(['bower_components/modernizr/modernizr.js'])
    .pipe($.uglify())
    .pipe($.rename('modernizr.min.js'))
    .pipe(gulp.dest('assets/dist/js'));
});

gulp.task('images', function() {
  return gulp.src('assets/src/img/**/*')
    .pipe($.imagemin({
      progressive: true,
      interlaced: true
    }))
    .pipe(gulp.dest('assets/dist/img'));
});

gulp.task('version', function() {
  return gulp.src(['assets/dist/css/main.min.css', 'assets/dist/js/scripts.min.js'], { base: 'assets/dist' })
    .pipe(gulp.dest('assets/dist'))
    .pipe($.rev())
    .pipe(gulp.dest('assets/dist'))
    .pipe($.rev.manifest())
    .pipe(gulp.dest('assets/dist'));
});

gulp.task('clean', function() {
  return gulp.src(['assets/dist/css/main.min*', 'assets/dist/js/scripts.min*'], { read: false })
    .pipe($.clean());
});

gulp.task('watch', function() {
  $.livereload.listen();
  gulp.watch(['assets/src/less/**/*', 'bower.json'], ['less:dev']);
  gulp.watch(['assets/src/js/**/*', 'bower.json'], ['jshint', 'js:dev']);
  gulp.watch('**/*.php').on('change', function(file) {
    $.livereload.changed(file.path);
  });
});

gulp.task('default', ['less:dev', 'less:editorStyle', 'jshint', 'js:dev', 'copy:fonts', 'images']);
gulp.task('dev', ['default']);
gulp.task('build', ['clean', 'less:build', 'less:editorStyle', 'js:build', 'copy:fonts', 'copy:jquery', 'copy:modernizr', 'images', 'version']);
