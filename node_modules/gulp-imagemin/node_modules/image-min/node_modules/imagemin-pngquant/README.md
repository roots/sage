# imagemin-pngquant [![Build Status](https://travis-ci.org/kevva/imagemin-pngquant.svg?branch=master)](https://travis-ci.org/kevva/imagemin-pngquant)

> pngquant image-min plugin

## Install

```bash
$ npm install --save imagemin-pngquant
```

## Usage

```js
var Imagemin = require('image-min');
var pngquant = require('imagemin-pngquant');

var imagemin = new Imagemin()
    .src('foo.png')
    .dest('foo-optimized.png')
    .use(pngquant());

imagemin.optimize();
```

## License

[MIT License](http://en.wikipedia.org/wiki/MIT_License) © [Kevin Mårtensson](https://github.com/kevva)
