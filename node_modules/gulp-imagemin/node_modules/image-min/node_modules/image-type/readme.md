# image-type [![Build Status](https://travis-ci.org/sindresorhus/image-type.svg?branch=master)](https://travis-ci.org/sindresorhus/image-type)

> Detect the image type of a Buffer/Uint8Array

See the [file-type](https://github.com/sindresorhus/file-type) module for more file types.


## Install

```sh
$ npm install --save image-type
```

```sh
$ bower install --save image-type
```

```sh
$ component install sindresorhus/image-type
```


## Usage

##### Node.js

```js
var readChunk = require('read-chunk'); // npm install read-chunk
var imageType = require('image-type');
var buffer = readChunk.sync('unicorn.png', 0, 12);

imageType(buffer);
//=> png
```

##### Browser

```js
var xhr = new XMLHttpRequest();
xhr.open('GET', 'unicorn.png');
xhr.responseType = 'arraybuffer';

xhr.onload = function () {
	imageType(new Uint8Array(this.response));
	//=> png
};

xhr.send();
```


## API

### imageType(buffer)

Returns: [`png`](https://github.com/sindresorhus/is-png), [`jpg`](https://github.com/sindresorhus/is-jpg), [`gif`](https://github.com/sindresorhus/is-gif), [`webp`](https://github.com/sindresorhus/is-webp), [`tif`](https://github.com/sindresorhus/is-tif), [`bmp`](https://github.com/sindresorhus/is-bmp), [`jxr`](https://github.com/sindresorhus/is-jxr), [`psd`](https://github.com/sindresorhus/is-psd), `false`

*SVG isn't included as it requires the whole file to be read, but you can get it [here](https://github.com/sindresorhus/is-svg).*

#### buffer

Type: `buffer` *(Node.js)*, `uint8array`

It only needs the first 12 bytes.


## CLI

```sh
$ npm install --global image-type
```

```sh
$ image-type --help

Usage
  $ cat <filename> | image-type
  $ image-type <filename>

Example
  $ cat unicorn.png | image-type
  png
```


## License

MIT © [Sindre Sorhus](http://sindresorhus.com)
