/*global $:true*/
var gulp = require('gulp');
var $ = require('gulp-load-plugins')();
var plugins = require('gulp-load-plugins')();

var pngcrush = require('imagemin-pngcrush');

var mainBowerFiles = require('main-bower-files');

var pkg = require('./package.json');

var paths = {
  scripts: [
    'assets/src/js/**/*'
  ],
  jshint: [
    'gulpfile.js',
    'assets/src/js/**/*'
  ],
  less: 'assets/src/less/main.less',
  bower: mainBowerFiles()
};

gulp.task('less:dev', function() {
  return gulp.src(paths.less)
    .pipe($.plumber())
    .pipe($.sourcemaps.init())
      .pipe($.less()).on('error', function(err) {
        console.warn(err.message);
      })
      .pipe($.autoprefixer('last 2 versions', 'ie 8', 'ie 9', 'android 2.3', 'android 4', 'opera 12'))
      .pipe($.rename('./main.css'))
      .pipe(gulp.dest('assets/dist/css'))
    .pipe($.sourcemaps.write())
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
    .pipe(gulp.dest('assets/dist/css'))
    .pipe($.minifyCss());
});

gulp.task('jshint', function() {
  return gulp.src(paths.jshint)
    .pipe($.jshint())
    .pipe($.jshint.reporter('jshint-stylish'))
    .pipe($.jshint.reporter('fail'));
});

gulp.task('js:dev', ['jshint'], function() {
  return gulp.src(paths.bower.concat(paths.scripts))
    .pipe($.concat('./scripts.js'))
    .pipe(gulp.dest('assets/dist/js'))
    .pipe($.livereload({ auto: false }));
});

gulp.task('js:build', ['jshint'], function() {
  return gulp.src(paths.bower.concat(paths.scripts))
    .pipe($.concat('./scripts.min.js'))
    .pipe($.uglify())
    .pipe(gulp.dest('assets/dist/js'));
});

gulp.task('copy:fonts', function() {
  return gulp.src(['bower_components/bootstrap/fonts/*', 'assets/src/fonts/*'])
    .pipe(gulp.dest('assets/dist/fonts'));
});

gulp.task('copy:jquery', function() {
  return gulp.src(['bower_components/jquery/dist/jquery.min.js'])
    .pipe($.rename('jquery-' + pkg.devDependencies.jquery + '.min.js'))
    .pipe(gulp.dest('assets/dist/js'));
});

gulp.task('images', function() {
  return gulp.src('assets/src/img/**/*')
    .pipe($.imagemin({
      progressive: true,
      interlaced: true,
      use: [pngcrush()]
    }))
    .pipe(gulp.dest('assets/dist/img'));
});

gulp.task('modernizr', function() {
  return gulp.src(
    ['assets/dist/js/scripts.min.js'],
    ['assets/dist/css/main.min.css']
  )
    .pipe($.modernizr('modernizr.min.js'))
    .pipe($.uglify())
    .pipe(gulp.dest('assets/dist/js'));
});

gulp.task('version', function() {
  return gulp.src(['assets/dist/css/main.min.css', 'assets/dist/js/scripts.min.js'], { base: 'assets' })
    .pipe($.rev())
    .pipe(gulp.dest('assets'))
    .pipe($.rev.manifest())
    .pipe(gulp.dest('assets'));
});

gulp.task('watch', function() {
  $.livereload.listen();
  gulp.watch('assets/src/less/**/*', ['less:dev']);
  gulp.watch('assets/src/js/**/*', ['jshint', 'js:dev']);
  gulp.watch('**/*.php').on('change', function(file) {
    $.livereload.changed(file.path);
  });
});

gulp.task('default', ['less:dev', 'jshint', 'js:dev']);
gulp.task('dev', ['default']);
gulp.task('build', ['less:build', 'js:build', 'copy:fonts', 'copy:jquery', 'images', 'modernizr', 'version']);
