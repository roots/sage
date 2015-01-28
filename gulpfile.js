// ## Globals
/*global $:true*/
var $           = require('gulp-load-plugins')();
var argv        = require('yargs').argv;
var browserSync = require('browser-sync');
var gulp        = require('gulp');
var lazypipe    = require('lazypipe');
var merge       = require('merge-stream');

// See https://github.com/austinpray/asset-builder
var manifest = require('asset-builder')('./assets/manifest.json');

// `path` - Paths to base asset directories. With trailing slashes.
// - `path.source` - Path to the source files. default: `assets/`
// - `path.dist` - Path to the build directory. default: `dist/`
var path = manifest.paths;

// `config` - Store arbitrary configuration values here.
var config = manifest.config || {};

// `globs` - These ultimately end up in their respective `gulp.src`.
// - `globs.js` - array of asset-builder js Dependency objects. Example:
//   ```
//   { type: 'js', name: 'main.js', globs: [] }
//   ```
// - `globs.css` an array of asset-builder css Dependency objects. Example:
//   ```
//   { type: 'css', name: 'main.css', globs: [] }
//   ```
// - `globs.fonts` - array of font path globs
// - `globs.images` - array of image path globs
// - `globs.bower` - array of all the bower main files
var globs = manifest.globs;

// `project` - paths to first-party assets.
// - `project.js` - array of first-party js assets
// - `project.css` - array of first-party css assets
var project = manifest.getProjectGlobs();

// CLI options
var enabled = {
  // Enable static asset revisioning when `--production`
  rev: argv.production,
  // Disable source maps when `--production`
  maps: !argv.production
};

// Path to the compiled assets manifest in the dist directory
var revManifest = path.dist + 'assets.json';

// ## Reusable Pipelines
// see https://github.com/OverZealous/lazypipe

// ### CSS processing pipeline
// Example
// ```
// gulp.src(cssFiles)
//   .pipe(cssTasks('main.css')
//   .pipe(gulp.dest(path.dist + 'styles'))
// ```
var cssTasks = function(filename) {
  return lazypipe()
    .pipe($.plumber)
    .pipe(function() {
      return $.if(enabled.maps, $.sourcemaps.init());
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
            'last 2 versions', 'ie 8', 'ie 9', 'android 2.3', 'android 4',
            'opera 12'
          ]
        }
      })
    .pipe(function() {
      return $.if(enabled.rev, $.rev());
    })
    .pipe(function() {
      return $.if(enabled.maps, $.sourcemaps.write('.'));
    })();
};

// ### JS processing pipeline
// Example
// ```
// gulp.src(jsFiles)
//   .pipe(jsTasks('main.js')
//   .pipe(gulp.dest(path.dist + 'scripts'))
// ```
var jsTasks = function(filename) {
  return lazypipe()
    .pipe(function() {
      return $.if(enabled.maps, $.sourcemaps.init());
    })
    .pipe($.concat, filename)
    .pipe($.uglify)
    .pipe(function() {
      return $.if(enabled.rev, $.rev());
    })
    .pipe(function() {
      return $.if(enabled.maps, $.sourcemaps.write('.'));
    })();
};

// ### Write to Rev Manifest
// If there are any revved files then write them to the rev manifest.
// See https://github.com/sindresorhus/gulp-rev
var writeToManifest = function(directory) {
  return lazypipe()
    .pipe(gulp.dest, path.dist + directory)
    .pipe(browserSync.reload, {stream:true})
    .pipe($.rev.manifest, revManifest, {
      base: path.dist,
      merge: true
    })
    .pipe(gulp.dest, path.dist)();
};

// ## Gulp Tasks
// Run `gulp -T` for a task summary

// ### Styles
// `gulp styles` - compiles, combines, and optimizes bower css and project css.
gulp.task('styles', function() {
  var merged = merge();
  manifest.forEachDependency('css', function(dep) {
    merged.add(gulp.src(dep.globs, {base: 'styles'})
      .pipe(cssTasks(dep.name)));
  });
  return merged
    .pipe(writeToManifest('styles'));
});

// ### Scripts
// `gulp scripts` - runs jshint then compiles, combines, and optimizes bower
// javascript and project javascript
gulp.task('scripts', ['jshint'], function() {
  var merged = merge();
  manifest.forEachDependency('js', function(dep) {
    merged.add(
      gulp.src(dep.globs, {base: 'scripts'})
        .pipe(jsTasks(dep.name))
    );
  });
  return merged
    .pipe(writeToManifest('scripts'));
});

// ### Fonts
// `gulp fonts` - grabs all the fonts and outputs them in a flattened directory
// structure. See: https://github.com/armed/gulp-flatten
gulp.task('fonts', function() {
  return gulp.src(globs.fonts)
    .pipe($.flatten())
    .pipe(gulp.dest(path.dist + 'fonts'));
});

// ### Images
// `gulp images` - run lossless compression on all the images.
gulp.task('images', function() {
  return gulp.src(globs.images)
    .pipe($.imagemin({
      progressive: true,
      interlaced: true
    }))
    .pipe(gulp.dest(path.dist + 'images'));
});

// ### JsHint
// `gulp jshint` - lints configuration JSON and project javascript
gulp.task('jshint', function() {
  return gulp.src([
    'bower.json', 'gulpfile.js'
  ].concat(project.js))
    .pipe($.jshint())
    .pipe($.jshint.reporter('jshint-stylish'))
    .pipe($.jshint.reporter('fail'));
});

// ### Clean
// `gulp clean` - deletes the build folder entirely
gulp.task('clean', require('del').bind(null, [path.dist]));

// ### Watch
// `gulp watch` - recompile assets whenever they change
gulp.task('watch', function() {
  browserSync({
    proxy: config.devUrl
  });
  gulp.watch([path.source + 'styles/**/*'], ['styles']);
  gulp.watch([path.source + 'scripts/**/*'], ['jshint', 'scripts']);
  gulp.watch(['bower.json'], ['wiredep']);
  gulp.watch('**/*.php', function() {
    browserSync.reload();
  });
});

// ### Build
// `gulp build` - Run all the build tasks but don't clean up beforehand.
// Generally you should be running `gulp` instead of `gulp build`.
gulp.task('build', ['styles', 'scripts', 'fonts', 'images']);

// ### Wiredep
// `gulp wiredep` - Automatically inject less and Sass bower dependencies. See
// https://github.com/taptapship/wiredep
gulp.task('wiredep', function() {
  var wiredep = require('wiredep').stream;
  return gulp.src(project.css)
    .pipe(wiredep())
    .pipe($.changed(path.source + 'styles'))
    .pipe(gulp.dest(path.source + 'styles'));
});

// ### Gulp
// `gulp` - Run a complete build. To compile for production run `gulp --production`.
gulp.task('default', ['clean'], function() {
  gulp.start('build');
});
