# [gulp](http://gulpjs.com)-imagemin [![Build Status](https://travis-ci.org/sindresorhus/gulp-imagemin.svg?branch=master)](https://travis-ci.org/sindresorhus/gulp-imagemin)

> Minify PNG, JPEG, GIF and SVG images with [image-min](https://github.com/kevva/image-min)

*Issues with the output should be reported on the image-min [issue tracker](https://github.com/kevva/image-min/issues).*


## Install

```bash
$ npm install --save-dev gulp-imagemin
```

On OS X you're recommended to increase the [ulimit](http://superuser.com/a/443168/6877) as it's ridiculously low by default: `ulimit -S -n 2048`


## Usage

```js
var gulp = require('gulp');
var imagemin = require('gulp-imagemin');
var pngcrush = require('imagemin-pngcrush');

gulp.task('default', function () {
	return gulp.src('src/images/*')
		.pipe(imagemin({
			progressive: true
			svgoPlugins: [{removeViewBox: false}],
			use: [pngcrush()]
		}))
		.pipe(gulp.dest('dist'));
});
```


## API

Comes bundled with the following optimizers:

- [gifsicle](https://github.com/kevva/imagemin-gifsicle) — *Compress GIF images*
- [jpegtran](https://github.com/kevva/imagemin-jpegtran) — *Compress JPEG images*
- [optipng](https://github.com/kevva/imagemin-optipng) — *Compress PNG images*
- [svgo](https://github.com/kevva/imagemin-svgo) — *Compress SVG images*

### imagemin(options)

Unsupported files are filtered out.

#### options

Options are applied to the correct files.

##### optimizationLevel *(png)*

Type: `number`  
Default: `3`

Select an optimization level between `0` and `7`.

> The optimization level 0 enables a set of optimization operations that require minimal effort. There will be no changes to image attributes like bit depth or color type, and no recompression of existing IDAT datastreams. The optimization level 1 enables a single IDAT compression trial. The trial chosen is what. OptiPNG thinks it’s probably the most effective. The optimization levels 2 and higher enable multiple IDAT compression trials; the higher the level, the more trials.

Level and trials:

1. 1 trial
2. 8 trials
3. 16 trials
4. 24 trials
5. 48 trials
6. 120 trials
7. 240 trials


##### progressive *(jpg)*

Type: `boolean`  
Default: `false`

Lossless conversion to progressive.


##### interlaced *(gif)*

Type: `boolean`  
Default: `false`

Interlace gif for progressive rendering.

#### svgoPlugins *(svg)*

Type: `array`  
Default: `[]`

Customize which SVGO plugins to use. [More here](https://github.com/sindresorhus/grunt-svgmin#available-optionsplugins).

##### use

Type: `array`  
Default: `null`

Additional [plugins](https://npmjs.org/keyword/imageminplugin) to use with image-min.


## License

[MIT](http://opensource.org/licenses/MIT) © [Sindre Sorhus](http://sindresorhus.com)
