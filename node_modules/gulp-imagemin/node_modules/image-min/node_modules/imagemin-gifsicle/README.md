# imagemin-gifsicle [![Build Status](https://travis-ci.org/kevva/imagemin-gifsicle.svg?branch=master)](https://travis-ci.org/kevva/imagemin-gifsicle)

> gifsicle image-min plugin

## Install

```bash
$ npm install --save imagemin-gifsicle
```

## Usage

```js
var Imagemin = require('image-min');
var gifsicle = require('imagemin-gifsicle');

var imagemin = new Imagemin()
    .src('foo.gif')
    .dest('foo-optimized.gif')
    .use(gifsicle({ interlaced: true }));

imagemin.optimize();
```

## License

[MIT License](http://en.wikipedia.org/wiki/MIT_License) © [Kevin Mårtensson](https://github.com/kevva)
