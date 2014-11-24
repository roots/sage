/*global $:true*/
var $              = require('gulp-load-plugins')();
var _              = require('lodash');
var gulp           = require('gulp');
var lazypipe       = require('lazypipe');
var mainBowerFiles = require('main-bower-files');
var obj            = require('object-path');
var traverse       = require('traverse');
var util           = require('util');

function getManifest(path) {
  var m = require(path);
  var defaults = {
    buildPaths: {
      appendSrc: ['theme'],
      src: 'assets/',
      dist: 'dist/'
    }
  };
  var err = function (msg) {
    msg = msg || 'file seems to be malformed';
    console.error('Manifest File Error: %s: %s', path, msg);
    process.exit(1);
  };
  var required = ['dependencies', 'buildPaths'];

  if(_.isPlainObject(m)) {
    m = _.defaults(m, defaults);

    _.forEach(required, function (req) {
      if(!obj.has(m, req)) {
        err('missing "'+req+'" property');
      }
    });

    traverse(m.dependencies).forEach(function (node) {
      if(this.isLeaf && !_.isArray(node) && !_.isArray(this.parent.node)) {
        this.update([node]);
      }
    });

    if(m.buildPaths.appendSrc) {
      _.forOwn(m.dependencies, function (dependency, name) {
        if(m.buildPaths.appendSrc.indexOf(name) >= 0) {
          traverse(m.dependencies[name]).forEach(function (node) {
            if(this.isLeaf) {
              this.update(m.buildPaths.src + node);
            }
          });
        }
      });
    }

    return m;
  } else {
    err();
  }
}

var manifest = getManifest('./assets/manifest.json');

var path = manifest.buildPaths;

var bower = require('wiredep')({
  exclude: obj.get(manifest, 'ignoreDependencies.bower')
});

var globs = (function buildGlobs() {
  return {
    scripts: (bower.js || [])
      .concat(obj.get(manifest, 'dependencies.vendor.scripts', []))
      .concat(obj.get(manifest, 'dependencies.theme.scripts', [])),
    scriptsIgnored: _.reduce(obj.get(manifest, 'ignoreDependencies.bower', []), 
      function (paths, depName) {
        return paths.concat(obj.get(bower, 'packages.'+depName+'.main', []));
      }, []),
    styles: (bower.css || [])
      .concat(obj.get(manifest, 'dependencies.vendor.styles', []))
      .concat(obj.get(manifest, 'dependencies.theme.styles', [])),
    editorStyle: obj.get(manifest, 'dependencies.theme.editorStyle', []),
    fonts: mainBowerFiles({ filter: /\.(eot|svg|ttf|woff)$/i })
      .concat(manifest.buildPaths.src + 'fonts/**/*.{eot,svg,ttf,woff}'),
    images: path.src + 'images/**/*'
  };
})();

var cssTasks = function(filename) {
  return lazypipe()
    .pipe($.plumber)
    .pipe($.sourcemaps.init)
      .pipe(function () {
        return $.if('*.less', $.less().on('error', function(err) {
          console.warn(err.message);
        }));
      })
      .pipe(function () {
        return $.if('*.scss', $.sass());
      })
      .pipe($.autoprefixer, 'last 2 versions', 'ie 8', 'ie 9', 'android 2.3', 'android 4', 'opera 12')
      .pipe($.concat, filename)
    .pipe($.sourcemaps.write, '.')
    .pipe(gulp.dest, path.dist + 'styles')();
};

gulp.task('styles', ['styles:editorStyle'], function() {
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
    .pipe(function () {
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

gulp.task('scripts:ignored', function () {
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
  gulp.watch('**/*.php').on('change', function(file) {
    $.livereload.changed(file.path);
  });
});

gulp.task('build', ['styles', 'scripts', 'fonts', 'images'], function () {
  gulp.start('version');
});

gulp.task('default', ['clean'], function () {
  gulp.start('build');
});
