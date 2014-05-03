[![Build Status](https://travis-ci.org/terinjokes/gulp-uglify.png?branch=master)](https://travis-ci.org/terinjokes/gulp-uglify)

## Information

<table>
<tr>
<td>Package</td><td>gulp-uglify</td>
</tr>
<tr>
<td>Description</td>
<td>Minify files with UglifyJS.</td>
</tr>
<tr>
<td>Node Version</td>
<td>≥ 0.9</td>
</tr>
</table>

## Usage

```javascript
var uglify = require('gulp-uglify');

gulp.task('compress', function() {
  gulp.src('lib/*.js')
    .pipe(uglify({outSourceMap: true}))
    .pipe(gulp.dest('dist'))
});
```

## Options

- `mangle`

	Pass `false` to skip mangling names.

- `output`

	Pass an object if you wish to specify additional [output
	options](http://lisperator.net/uglifyjs/codegen). The defaults are
	optimized for best compression.

- `compress`

	Pass an object to specify custom [compressor
	options](http://lisperator.net/uglifyjs/compress). Pass `false` to skip
	compression completely.

- `preserveComments`

	A convenience option for `options.output.comments`. Defaults to preserving no
	comments.

	- `all`
		
		Preserve all comments in code blocks

	- `some`

		Preserve comments that start with a bang (`!`) or include a Closure
		Compiler directive (`@preserve`, `@license`, `@cc_on`)

	- `function`

		Specify your own comment preservation function. You will be passed the
		current node and the current comment and are expected to return either
		`true` or `false`.

You can also pass the `uglify` function any of the options [listed
here](https://github.com/mishoo/UglifyJS2#the-simple-way) to modify
UglifyJS's behavior.


### Source Maps

You can have UglifyJS’s generated source maps emitted on the stream by passing
`true` for the `outSourceMap` option. The file object’s path will be based on
the input file’s, with ‘.map’ appended.

Input source maps are no supported by this plugin at this time.

