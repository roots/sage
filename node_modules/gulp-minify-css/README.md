[![Build Status](https://travis-ci.org/jonathanepollack/gulp-minify-css.png?branch=master)](https://travis-ci.org/jonathanepollack/gulp-minify-css)
## Information

<table>
<tr> 
<td>Package</td><td>gulp-minify-css</td>
</tr>
<tr>
<td>Description</td>
<td>Minify css with <a href="https://github.com/GoalSmashers/clean-css">clean-css</a>, including optional caching.</td>
</tr>
<tr>
<td>Node Version</td>
<td>>= 0.10</td>
</tr>
</table>

## Installion

```
npm install --save gulp-minify-css
```

## Usage

```js
var gulp = require('gulp'),
		minifyCSS = require('gulp-minify-css');

gulp.task('minify-css', function() {
  gulp.src('./static/css/*.css')
    .pipe(minifyCSS({keepBreaks:true}))
    .pipe(gulp.dest('./dist/'))
});
```
### Options
* `cache` - check and return minified CSS from cache if it exists; minify and store in cache when it does not

This is a gulp-minify-css feature and not a [clean-css](https://github.com/GoalSmashers/clean-css/) feature.

___

* `keepSpecialComments` - `*` for keeping all (default), `1` for keeping first one only, `0` for removing all
* `keepBreaks` - whether to keep line breaks (default is `false`)
* `benchmark` - turns on benchmarking mode measuring time spent on cleaning up
  (run `npm run bench` to see example)
* `root` - path to resolve absolute `@import` rules and rebase relative URLs
* `relativeTo` - path with which to resolve relative `@import` rules and URLs
* `processImport` - whether to process `@import` rules
* `noRebase` - whether to skip URLs rebasing
* `noAdvanced` - set to true to disable advanced optimizations - selector & property merging, reduction, etc.
* `compatibility` - Force compatibility mode to `ie7` or `ie8`. Defaults to not set.
* `debug` - set to true to get minification statistics under `stats` property (see `test/custom-test.js` for examples)

Source: [clean-css](https://github.com/GoalSmashers/clean-css/blob/80f2d2cdbbe061c49ed1bfd0653edcb50dbebf57/README.md)

## LICENSE

(MIT License)

Copyright (c) 2013 Jonathan Pollack (<jonathanepollack@gmail.com>), Cloublabs Inc.

Permission is hereby granted, free of charge, to any person obtaining
a copy of this software and associated documentation files (the
"Software"), to deal in the Software without restriction, including
without limitation the rights to use, copy, modify, merge, publish,
distribute, sublicense, and/or sell copies of the Software, and to
permit persons to whom the Software is furnished to do so, subject to
the following conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
