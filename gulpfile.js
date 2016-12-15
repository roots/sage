var pkg = require('./package.json');
var config = pkg.sage;
var paths = config.paths;

var gulp = require('gulp');
var browserify = require('browserify');
var es6ify = require('es6ify');
var source = require('vinyl-source-stream');
var pump = require('pump');
var sass = require('gulp-sass');
var uglify = require('gulp-uglify');
var cleancss = require('gulp-clean-css');
var browserSync = require('browser-sync');


gulp.task('js', function(cb) {

  var bundler = browserify(es6ify.runtime)
    .transform(es6ify)
    .add(paths.source + 'scripts/index.js');

  pump([
    bundler.bundle(),
    source('index.js'),
    // uglify(),
    gulp.dest(paths.dist + 'scripts'),
    browserSync.stream()
  ], cb);

});

gulp.task('css', function(cb) {

  pump([
    gulp.src(paths.source + 'styles/main.scss'),
    sass(),
    cleancss(),
    gulp.dest(paths.dist + 'styles/main.css'),
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

gulp.task('dev', []);