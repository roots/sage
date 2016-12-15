// config
var pkg = require('./package.json');
var config = pkg.sageConfig;
var paths = config.paths;

var pump = require('pump');
var browserify = require('browserify');
var babelify = require('babelify');

var gulp = require('gulp');
var source = require('vinyl-source-stream');
var buffer = require('vinyl-buffer');

var sourcemaps = require('gulp-sourcemaps');
var sass = require('gulp-sass');
var uglify = require('gulp-uglify');
var cleancss = require('gulp-clean-css');
var browserSync = require('browser-sync');




gulp.task('js', function(cb) {

  var bundler = browserify({
	  	entries: paths.source + 'scripts/index.js',
	  	debug: true
	  }).transform("babelify")

  pump([
    bundler.bundle(),
    source('index.js'),
    buffer(),
    sourcemaps.init(),
    uglify(),
    sourcemaps.write('./maps'),
    gulp.dest(paths.dist + 'scripts'),
    browserSync.stream()
  ], cb);

});

gulp.task('css', function(cb) {

  pump([
    gulp.src(paths.source + 'styles/main.scss'),
    sass(),
    cleancss(),
    gulp.dest(paths.dist + 'styles'),
    browserSync.stream(),
  ], cb);

});

// gulp.task('img', function() {

// });

gulp.task('watch', function() {
  browserSync.init({
    files: ['{lib,templates}/**/*.php', '*.php'],
    proxy: config.devUrl,
    snippetOptions: {
      whitelist: ['/wp-admin/admin-ajax.php'],
      blacklist: ['/wp-admin/**']
    }
  });

  gulp.watch([paths.source + 'styles/**/*'], ['css']);
  gulp.watch([paths.source + 'scripts/**/*'], ['js']);
  // gulp.watch([paths.source + 'images/**/*'], ['img']);
});

gulp.task('build', ['js', 'css']);
