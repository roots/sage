# imagemin-svgo [![Build Status](https://travis-ci.org/kevva/imagemin-svgo.svg?branch=master)](https://travis-ci.org/kevva/imagemin-svgo)

> svgo image-min plugin

## Install

```bash
$ npm install --save imagemin-svgo
```

## Usage

```js
var Imagemin = require('image-min');
var svgo = require('imagemin-svgo');

var imagemin = new Imagemin()
    .src('foo.svg')
    .dest('foo-optimized.svg')
    .use(svgo());

imagemin.optimize();
```

## License

[MIT License](http://en.wikipedia.org/wiki/MIT_License) © [Kevin Mårtensson](https://github.com/kevva)
