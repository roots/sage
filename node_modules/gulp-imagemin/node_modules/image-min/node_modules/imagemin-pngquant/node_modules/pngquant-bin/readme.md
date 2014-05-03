# node-pngquant-bin [![Build Status](https://travis-ci.org/sindresorhus/node-pngquant-bin.svg?branch=master)](https://travis-ci.org/sindresorhus/node-pngquant-bin)

[pngquant](http://pngquant.org) 1.8.4 Node.js wrapper that makes it seamlessly available as a local dependency on OS X, Linux and Windows.

> pngquant is a command-line utility for converting 24/32-bit PNG images to paletted (8-bit) PNGs. The conversion reduces file sizes significantly (often as much as 70%) and preserves full alpha transparency.

## Install

```bash
$ npm install --save pngquant-bin
```

## Usage

```js
var execFile = require('child_process').execFile;
var pngquant = require('pngquant-bin').path;

execFile(pngquant, ['-o', 'output.png', 'input.png'], function (err) {
	if (err) {
		throw err;
	}

	console.log('Image minified');
});
```

## CLI

```bash
$ npm install --global pngquant-bin
```

```bash
$ pngquant --help
```

## License

MIT © [Kevin Mårtensson](http://kevinmartensson.com)
