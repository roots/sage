/*global $:true*/
var gulp = require('gulp');

var $ = require('gulp-load-plugins')();

var build = {
  src: 'assets/',
  dist: 'dist/'
};

var paths = {
  scripts: [
    build.src + 'scripts/**/*'
  ],
  jshint: [
    'bower.json',
    'gulpfile.js',
    build.src + 'scripts/**/*'
  ],
  styles: build.src + 'styles/main.less',
  editorStyle: build.src + 'styles/editor-style.less'
};

gulp.task('styles:dev', function() {
  return gulp.src(paths.styles)
    .pipe($.plumber())
    .pipe($.sourcemaps.init())
      .pipe($.less()).on('error', function(err) {
        console.warn(err.message);
      })
      .pipe($.autoprefixer('last 2 versions', 'ie 8', 'ie 9', 'android 2.3', 'android 4', 'opera 12'))
    .pipe($.sourcemaps.write())
    .pipe($.rename('main.css'))
    .pipe(gulp.dest(build.dist + 'styles'))
    .pipe($.livereload({ auto: false }));
});

gulp.task('styles:build', function() {
  return gulp.src(paths.styles)
    .pipe($.plumber())
      .pipe($.less()).on('error', function(err) {
        console.warn(err.message);
      })
      .pipe($.autoprefixer('last 2 versions', 'ie 9', 'android 2.3', 'android 4', 'opera 12'))
      .pipe($.rename('main.css'))
    .pipe($.minifyCss())
    .pipe(gulp.dest(build.dist + 'styles'));
});

gulp.task('styles:editorStyle', function() {
  return gulp.src(paths.editorStyle)
    .pipe($.plumber())
    .pipe($.less()).on('error', function(err) {
      console.warn(err.message);
    })
    .pipe($.autoprefixer('last 2 versions', 'ie 9', 'android 2.3', 'android 4', 'opera 12'))
    .pipe($.rename('editor-style.css'))
    .pipe(gulp.dest(build.dist + 'styles'));
});

gulp.task('jshint', function() {
  return gulp.src(paths.jshint)
    .pipe($.jshint())
    .pipe($.jshint.reporter('jshint-stylish'))
    .pipe($.jshint.reporter('fail'));
});

gulp.task('scripts:dev', ['jshint'], function() {
  return gulp.src(require('main-bower-files')().concat(paths.scripts))
    .pipe($.filter('**/*.js'))
    .pipe($.concat('scripts.js'))
    .pipe(gulp.dest(build.dist + 'scripts'))
    .pipe($.livereload({ auto: false }));
});

gulp.task('scripts:build', ['jshint'], function() {
  return gulp.src(require('main-bower-files')().concat(paths.scripts))
    .pipe($.filter('**/*.js'))
    .pipe($.concat('scripts.js'))
    .pipe($.uglify())
    .pipe(gulp.dest(build.dist + 'scripts'));
});

gulp.task('copy:fonts', function() {
  return gulp.src(require('main-bower-files')().concat(build.src + 'fonts/**/*'))
    .pipe($.filter('**/*.{eot,svg,ttf,woff}'))
    .pipe(gulp.dest(build.dist + 'fonts'));
});

gulp.task('copy:jquery', function() {
  return gulp.src(['bower_components/jquery/dist/jquery.js'])
    .pipe($.rename('jquery.js'))
    .pipe(gulp.dest(build.dist + 'scripts'));
});

gulp.task('copy:modernizr', function() {
  return gulp.src(['bower_components/modernizr/modernizr.js'])
    .pipe($.uglify())
    .pipe($.rename('modernizr.js'))
    .pipe(gulp.dest(build.dist + 'scripts'));
});

gulp.task('images', function() {
  return gulp.src(build.src + 'src/images/**/*')
    .pipe($.imagemin({
      progressive: true,
      interlaced: true
    }))
    .pipe(gulp.dest(build.dist + 'images'));
});

gulp.task('version', function() {
  return gulp.src([build.dist + 'styles/main.css', build.dist + 'js/scripts.js'], { base: build.dist })
    .pipe(gulp.dest(build.dist))
    .pipe($.rev())
    .pipe(gulp.dest(build.dist))
    .pipe($.rev.manifest())
    .pipe(gulp.dest(build.dist));
});

gulp.task('clean', function() {
  return gulp.src(build.dist, { read: false })
    .pipe($.clean());
});

gulp.task('watch', function() {
  $.livereload.listen();
  gulp.watch([build.src + 'styles/**/*', 'bower.json'], ['styles:dev']);
  gulp.watch([build.src + 'scripts/**/*', 'bower.json'], ['jshint', 'scripts:dev']);
  gulp.watch('**/*.php').on('change', function(file) {
    $.livereload.changed(file.path);
  });
});

gulp.task('default', ['styles:dev', 'styles:editorStyle', 'jshint', 'scripts:dev', 'copy:fonts', 'images']);
gulp.task('dev', ['default']);
gulp.task('build', ['styles:build', 'styles:editorStyle', 'scripts:build', 'copy:fonts', 'copy:jquery', 'copy:modernizr', 'images', 'version']);
