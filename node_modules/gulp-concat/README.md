![status](https://secure.travis-ci.org/wearefractal/gulp-concat.png?branch=master)

## Information

<table>
<tr> 
<td>Package</td><td>gulp-concat</td>
</tr>
<tr>
<td>Description</td>
<td>Concatenates files</td>
</tr>
<tr>
<td>Node Version</td>
<td>>= 0.4</td>
</tr>
</table>

## Usage

```javascript
var concat = require('gulp-concat');

gulp.task('scripts', function() {
  gulp.src('./lib/*.js')
    .pipe(concat('all.js'))
    .pipe(gulp.dest('./dist/'))
});
```

This will concat files by your operating systems newLine. It will take the base directory from the first file that passes through it.

Files will be concatenated in the order that they are specified in the `gulp.src` function. For example, to concat `./lib/file3.js`, `./lib/file1.js` and `./lib/file2.js` in that order, the following code will create a task to do that:

```javascript
var concat = require('gulp-concat');

gulp.task('scripts', function() {
  gulp.src(['./lib/file3.js', './lib/file1.js', './lib/file2.js'])
    .pipe(concat('all.js'))
    .pipe(gulp.dest('./dist/'))
});
```

To change the newLine simply pass an object as the second argument to concat with newLine being whatever (\r\n if you want to support any OS to look at it)

For instance:

```
.pipe(concat('main.js', {newLine: ';'}))
```


## LICENSE

(MIT License)

Copyright (c) 2013 Fractal <contact@wearefractal.com>

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
