# imagemin-optipng [![Build Status](https://travis-ci.org/kevva/imagemin-optipng.svg?branch=master)](https://travis-ci.org/kevva/imagemin-optipng)

> optipng image-min plugin

## Install

```bash
$ npm install --save imagemin-optipng
```

## Usage

```js
var Imagemin = require('image-min');
var optipng = require('imagemin-optipng');

var imagemin = new Imagemin()
    .src('foo.png')
    .dest('foo-optimized.png')
    .use(optipng({ optimizationLevel: 3 }));

imagemin.optimize();
```

## License

[MIT License](http://en.wikipedia.org/wiki/MIT_License) © [Kevin Mårtensson](https://github.com/kevva)
