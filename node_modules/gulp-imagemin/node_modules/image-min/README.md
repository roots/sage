# image-min [![Build Status](https://travis-ci.org/kevva/image-min.svg?branch=master)](https://travis-ci.org/kevva/image-min)

> Minify images seamlessly with Node.js


## Install

```bash
$ npm install --save image-min
```


## Usage

```js
var Imagemin = require('image-min');

var imagemin = new Imagemin()
    .src('foo.jpg')
    .dest('foo-optimized.jpg')
    .use(Imagemin.jpegtran({ progressive: true }));

imagemin.optimize(function (err, file) {
    console.log(file);
    // => { contents: <Buffer 89 50 4e ...>, mode: '0644' }
});
```


## API

### new Imagemin()

Creates a new `Imagemin` instance.

### .use(plugin)

Add a `plugin` to the middleware stack.

### .src(file)

Set the file to be optimized. Can be a `Buffer` or the path to a file.

### .dest(file)

Set the destination to where your file will be written. If you don't set any destination
the file won't be written.

### .optimize(cb)

Optimize your file with the given settings.

### .run(file, cb)

Run all middleware plugins on your file.

## Plugins

The follwing [plugins](https://www.npmjs.org/browse/keyword/imageminplugin) are bundled with image-min:

* [gifsicle](#gifsicle) — Compress GIF images.
* [jpegtran](#jpegtran) — Compress JPG images.
* [optipng](#optipng) — Lossless compression of PNG images.
* [pngquant](#pngquant) — Lossy compression of PNG images.
* [svgo](#svgo) — Compress SVG images.

### .gifsicle()

Compress GIF images.

```js
var Imagemin = require('image-min');

var imagemin = new Imagemin()
    .use(Imagemin.gifsicle({ interlaced: true }));
```

### .jpegtran()

Compress JPG images.

```js
var Imagemin = require('image-min');

var imagemin = new Imagemin()
    .use(Imagemin.jpegtran({ progressive: true }));
```

### .optipng()

Lossless compression of PNG images.

```js
var Imagemin = require('image-min');

var imagemin = new Imagemin()
    .use(Imagemin.optipng({ optimizationLevel: 3 }));
```

### .pngquant()

Lossy compression of PNG images.

```js
var Imagemin = require('image-min');

var imagemin = new Imagemin()
    .use(Imagemin.pngquant());
```

### .svgo()

Lossy compression of PNG images.

```js
var Imagemin = require('image-min');

var imagemin = new Imagemin()
    .use(Imagemin.svgo());
```

## CLI

You can also use it as a CLI app by installing it globally:

```bash
$ npm install --global image-min
```

### Usage

```bash
$ imagemin --help

Usage
  $ imagemin <file>
  $ cat <file> | imagemin

Example
  $ imagemin --out foo-optimized.png foo.png
  $ cat foo.png | imagemin --out foo-optimized.png

Options
  -i, --interlaced                    Interlace GIF for progressive rendering
  -l, --optimizationLevel <number>    PNG optimization level (0-7)
  -o, --out <file>                    Output file
  -p, --progressive                   Lossless conversion to progressive
```

## License

[MIT License](http://en.wikipedia.org/wiki/MIT_License) © [Kevin Mårtensson](http://kevinmartensson.com)
