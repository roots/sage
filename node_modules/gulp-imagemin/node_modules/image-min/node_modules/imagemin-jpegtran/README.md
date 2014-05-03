# imagemin-jpegtran [![Build Status](https://travis-ci.org/kevva/imagemin-jpegtran.svg?branch=master)](https://travis-ci.org/kevva/imagemin-jpegtran)

> jpegtran image-min plugin

## Install

```bash
$ npm install --save imagemin-jpegtran
```

## Usage

```js
var Imagemin = require('image-min');
var jpegtran = require('imagemin-jpegtran');

var imagemin = new Imagemin()
    .src('foo.jpg')
    .dest('foo-optimized.jpg')
    .use(jpegtran({ progressive: true }));

imagemin.optimize();
```

## License

[MIT License](http://en.wikipedia.org/wiki/MIT_License) © [Kevin Mårtensson](https://github.com/kevva)
