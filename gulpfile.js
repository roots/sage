/*global $:true*/
var $        = require('gulp-load-plugins')();
var _        = require('lodash');
var argv     = require('yargs').argv;
var gulp     = require('gulp');
var lazypipe = require('lazypipe');
var manifest = require('asset-builder')('./assets/manifest.json');
var merge    = require('merge-stream');

var mapsEnabled = !argv.production;
var path = manifest.paths;
var globs = manifest.globs;
var project = manifest.getProjectGlobs();

var cssTasks = function(filename) {
  return lazypipe()
    .pipe($.plumber)
    .pipe(function () {
      return $.if(mapsEnabled, $.sourcemaps.init());
    })
      .pipe(function() {
        return $.if('*.less', $.less().on('error', function(err) {
          console.warn(err.message);
        }));
      })
      .pipe(function() {
        return $.if('*.scss', $.sass({
          outputStyle: 'nested', // libsass doesn't support expanded yet
          precision: 10,
          includePaths: ['.'],
          onError: console.error.bind(console, 'Sass error:')
        }));
      })
      .pipe($.concat, filename)
      .pipe($.pleeease, {
        autoprefixer: {
          browsers: [
            'last 2 versions', 'ie 8', 'ie 9', 'android 2.3', 'android 4', 'opera 12'
          ]
        }
      })
    .pipe(function () {
      return $.if(mapsEnabled, $.sourcemaps.write('.'));
    })
    .pipe(gulp.dest, path.dist + 'styles')();
};

gulp.task('styles', ['wiredep'], function() {
  var merged = merge();
  manifest.forEachDependency('css', function (dep) {
    merged.add(gulp.src(dep.globs)
      .pipe(cssTasks(dep.name)));
  });
  return merged;
});

gulp.task('jshint', function() {
  return gulp.src([
    'bower.json', 'gulpfile.js'
  ].concat(project.js))
    .pipe($.jshint())
    .pipe($.jshint.reporter('jshint-stylish'))
    .pipe($.jshint.reporter('fail'));
});

var jsTasks = function(filename) {
  var fn = filename;
  return lazypipe()
    .pipe(function () {
      return $.if(mapsEnabled, $.sourcemaps.init());
    })
    .pipe(function() {
      return $.if(!!fn, $.concat(fn || 'all.js'));
    })
    .pipe($.uglify)
    .pipe(function () {
      return $.if(mapsEnabled, $.sourcemaps.write('.'));
    })
    .pipe(gulp.dest, path.dist + 'scripts')();
};

gulp.task('scripts', ['jshint'], function() {
  var merged = merge();
  manifest.forEachDependency('js', function (dep) {
    merged.add(gulp.src(dep.globs)
      .pipe(jsTasks(dep.name)));
  });
  return merged;
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
  gulp.watch([path.source + 'styles/**/*'], ['styles']);
  gulp.watch([path.source + 'scripts/**/*'], ['jshint', 'scripts']);
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
  gulp.src(project.css)
    .pipe(wiredep())
    .pipe(gulp.dest(path.source + 'styles'));
});

gulp.task('default', ['clean'], function() {
  gulp.start('build');
});
