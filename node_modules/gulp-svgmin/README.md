# [gulp](https://github.com/wearefractal/gulp)-svgmin [![Build Status](https://travis-ci.org/ben-eb/gulp-svgmin.svg?branch=master)](https://travis-ci.org/ben-eb/gulp-svgmin) [![NPM version](https://badge.fury.io/js/gulp-svgmin.png)](http://badge.fury.io/js/gulp-svgmin) [![Dependency Status](https://gemnasium.com/ben-eb/gulp-svgmin.png)](https://gemnasium.com/ben-eb/gulp-svgmin)

> Minify SVG with [SVGO](https://github.com/svg/svgo).

*If you have any difficulties with the output of this plugin, please use the [SVGO tracker](https://github.com/svg/svgo/issues).*

Install via [npm](https://npmjs.org/package/gulp-svgmin):

```
npm install gulp-svgmin --save-dev
```

## Example

```js
var gulp = require('gulp');
var svgmin = require('gulp-svgmin');

gulp.task('default', function() {
    return gulp.src('logo.svg')
        .pipe(svgmin())
        .pipe(gulp.dest('./out'));
});
```

## Plugins

Optionally, you can disable any [SVGO plugins](https://github.com/svg/svgo/tree/master/plugins) to customise the output. You will need to provide the config in comma separated objects, like the example below.

```js
gulp.task('default', function() {
    return gulp.src('logo.svg')
        .pipe(svgmin([{
            removeDoctype: false
        }, {
            removeComments: false
        }]))
        .pipe(gulp.dest('./out'));
});
```
