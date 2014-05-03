# is-psd [![Build Status](https://travis-ci.org/sindresorhus/is-psd.svg?branch=master)](https://travis-ci.org/sindresorhus/is-psd)

> Check if a Buffer/Uint8Array is a [PSD](http://en.wikipedia.org/wiki/Adobe_Photoshop#File_format) image

Used by [image-type](https://github.com/sindresorhus/image-type).


## Install

```sh
$ npm install --save is-psd
```

```sh
$ bower install --save is-psd
```

```sh
$ component install sindresorhus/is-psd
```


## Usage

##### Node.js

```js
var readChunk = require('read-chunk'); // npm install read-chunk
var isPsd = require('is-psd');
var buffer = readChunk.sync('unicorn.psd', 0, 4);

isPsd(buffer);
//=> true
```

##### Browser

```js
var xhr = new XMLHttpRequest();
xhr.open('GET', 'unicorn.psd');
xhr.responseType = 'arraybuffer';

xhr.onload = function () {
	isPsd(new Uint8Array(this.response));
	//=> true
};

xhr.send();
```


## API

### isPsd(buffer)

Accepts a Buffer (Node.js) or Uint8Array.

It only needs the first 4 bytes.


## License

MIT © [Sindre Sorhus](http://sindresorhus.com)
