/*global $:true*/
var $              = require('gulp-load-plugins')();
var _              = require('lodash');
var autoprefixer   = require('autoprefixer-core');
var csswring       = require('csswring');
var gulp           = require('gulp');
var lazypipe       = require('lazypipe');
var mainBowerFiles = require('main-bower-files');
var obj            = require('object-path');

var manifest = require('asset-builder')('./assets/manifest.json');

var path = manifest.buildPaths;
var globs = manifest.globs;

var cssTasks = function(filename) {
  var processors = [
    autoprefixer({browsers: ['last 2 versions', 'ie 8', 'ie 9', 'android 2.3', 'android 4', 'opera 12']}),
    csswring
  ];

  return lazypipe()
    .pipe($.plumber)
    .pipe($.sourcemaps.init)
      .pipe(function() {
        return $.if('*.less', $.less().on('error', function(err) {
          console.warn(err.message);
        }));
      })
      .pipe(function() {
        return $.if('*.scss', $.sass());
      })
      .pipe($.concat, filename)
    .pipe($.postcss, processors)
    .pipe($.sourcemaps.write, '.')
    .pipe(gulp.dest, path.dist + 'styles')();
};

gulp.task('styles', ['wiredep', 'styles:editorStyle'], function() {
  return gulp.src(globs.styles)
    .pipe(cssTasks('main.css'));
});

gulp.task('styles:editorStyle', function() {
  return gulp.src(globs.editorStyle)
    .pipe(cssTasks('editor-style.css'));
});

gulp.task('jshint', function() {
  return gulp.src([
    'bower.json', 'gulpfile.js'
  ].concat(obj.get(manifest, 'dependencies.theme.scripts', [])))
    .pipe($.jshint())
    .pipe($.jshint.reporter('jshint-stylish'))
    .pipe($.jshint.reporter('fail'));
});

var jsTasks = function(filename) {
  var fn = filename;
  return lazypipe()
    .pipe($.sourcemaps.init)
    .pipe(function() {
      return $.if(!!fn, $.concat(fn || 'all.js'));
    })
    .pipe($.uglify)
    .pipe($.sourcemaps.write, '.')
    .pipe(gulp.dest, path.dist + 'scripts')();
};

gulp.task('scripts', ['jshint', 'scripts:ignored'], function() {
  return gulp.src(globs.scripts)
    .pipe(jsTasks('app.js'));
});

gulp.task('scripts:ignored', function() {
  return gulp.src(globs.scriptsIgnored)
    .pipe(jsTasks());
});

gulp.task('fonts', function() {
  return gulp.src(globs.fonts)
    .pipe($.flatten())
    .pipe(gulp.dest(path.dist + 'fonts'));
});

gulp.task('images', function() {
  return gulp.src(globs.images)
    .pipe($.imagemin({
      progressive: true,
      interlaced: true
    }))
    .pipe(gulp.dest(path.dist + 'images'));
});

gulp.task('version', function() {
  return gulp.src([path.dist + '**/*.{js,css}'], { base: path.dist })
    .pipe(gulp.dest(path.dist))
    .pipe($.rev())
    .pipe(gulp.dest(path.dist))
    .pipe($.rev.manifest())
    .pipe(gulp.dest(path.dist));
});

gulp.task('clean', require('del').bind(null, [path.dist]));

gulp.task('watch', function() {
  $.livereload.listen();
  gulp.watch([path.src + 'styles/**/*', 'bower.json'], ['styles']);
  gulp.watch([path.src + 'scripts/**/*', 'bower.json'], ['jshint', 'scripts']);
  gulp.watch(['bower.json'], ['wiredep']);
  gulp.watch('**/*.php').on('change', function(file) {
    $.livereload.changed(file.path);
  });
});

gulp.task('build', ['styles', 'scripts', 'fonts', 'images'], function() {
  gulp.start('version');
});

gulp.task('wiredep', function() {
  var wiredep = require('wiredep').stream;
  gulp.src(obj.get(manifest, 'dependencies.theme.styles'))
    .pipe(wiredep())
    .pipe(gulp.dest(manifest.buildPaths.src + 'styles/'));
});

gulp.task('default', ['clean'], function() {
  gulp.start('build');
});
