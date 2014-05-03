# is-webp [![Build Status](https://travis-ci.org/sindresorhus/is-webp.svg?branch=master)](https://travis-ci.org/sindresorhus/is-webp)

> Check if a Buffer/Uint8Array is a [WebP](http://en.wikipedia.org/wiki/WebP) image

Used by [image-type](https://github.com/sindresorhus/image-type).


## Install

```sh
$ npm install --save is-webp
```

```sh
$ bower install --save is-webp
```

```sh
$ component install sindresorhus/is-webp
```


## Usage

##### Node.js

```js
var readChunk = require('read-chunk'); // npm install read-chunk
var isWebp = require('is-webp');
var buffer = readChunk.sync('unicorn.webp', 0, 12);

isWebp(buffer);
//=> true
```

##### Browser

```js
var xhr = new XMLHttpRequest();
xhr.open('GET', 'unicorn.webp');
xhr.responseType = 'arraybuffer';

xhr.onload = function () {
	isWebp(new Uint8Array(this.response));
	//=> true
};

xhr.send();
```


## API

### isWebp(buffer)

Accepts a Buffer (Node.js) or Uint8Array.

It only needs the first 12 bytes.


## License

MIT © [Sindre Sorhus](http://sindresorhus.com)
