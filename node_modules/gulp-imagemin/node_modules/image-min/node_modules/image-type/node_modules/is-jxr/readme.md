# is-jxr [![Build Status](https://travis-ci.org/sindresorhus/is-jxr.svg?branch=master)](https://travis-ci.org/sindresorhus/is-jxr)

> Check if a Buffer/Uint8Array is a [JPEG XR](http://en.wikipedia.org/wiki/JPEG_XR) image

Used by [image-type](https://github.com/sindresorhus/image-type).


## Install

```sh
$ npm install --save is-jxr
```

```sh
$ bower install --save is-jxr
```

```sh
$ component install sindresorhus/is-jxr
```


## Usage

##### Node.js

```js
var readChunk = require('read-chunk'); // npm install read-chunk
var isJxr = require('is-jxr');
var buffer = readChunk.sync('unicorn.jxr', 0, 3);

isJxr(buffer);
//=> true
```

##### Browser

```js
var xhr = new XMLHttpRequest();
xhr.open('GET', 'unicorn.jxr');
xhr.responseType = 'arraybuffer';

xhr.onload = function () {
	isJxr(new Uint8Array(this.response));
	//=> true
};

xhr.send();
```


## API

### isJxr(buffer)

Accepts a Buffer (Node.js) or Uint8Array.

It only needs the first 3 bytes.


## License

MIT © [Sindre Sorhus](http://sindresorhus.com)
