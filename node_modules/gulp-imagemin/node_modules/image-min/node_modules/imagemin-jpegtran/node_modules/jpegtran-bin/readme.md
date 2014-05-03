# node-jpegtran-bin [![Build Status](https://secure.travis-ci.org/yeoman/node-jpegtran-bin.png?branch=master)](http://travis-ci.org/yeoman/node-jpegtran-bin)

jpegtran 1.3 (part of [libjpeg-turbo](http://libjpeg-turbo.virtualgl.org/)) Node.js wrapper that makes it seamlessly available as a local dependency on OS X, Linux, FreeBSD, Solaris and Windows. Most commonly used to losslessly minify JPEG images.

> libjpeg-turbo is a derivative of libjpeg that uses SIMD instructions (MMX, SSE2, NEON) to accelerate baseline JPEG compression and decompression on x86, x86-64, and ARM systems. On such systems, libjpeg-turbo is generally 2-4x as fast as the unmodified version of libjpeg, all else being equal.


## Install

- Install with [npm](https://npmjs.org/package/jpegtran-bin): `npm install --save jpegtran-bin`


## Example usage

```js
var execFile = require('child_process').execFile;
var jpegtranPath = require('jpegtran-bin').path;

execFile(jpegtranPath, ['-outfile', 'output.jpg', 'input.jpg'], function() {
    console.log('Image minified');
});
```

Can also be run directly from `./node_modules/.bin/jpegtran`.


## Dev

Note to self on how to update the binaries.

### OS X and Linux

- Run `npm install` to build the binary.

The `nasm` (Netwide Assember) package is required to build the binary on Ubuntu.

### Windows

- Download the [Windows files 32/64-bit](http://sourceforge.net/projects/libjpeg-turbo/files/) (GCC compiled) on a Windows machine

  (current version 1.3.0, x64 `libjpeg-turbo-1.3.0-gcc64.exe` and for x86 `libjpeg-turbo-1.3.0-gcc.exe`)
- Run the downloaded file to extract
- In the extracted folder go to the `bin` folder and copy `jpegtran.exe` and `libjpeg-62.dll` to `jpegtran-bin\vendor\` folder
  
  (for `grunt-contrib-imagemin` the folder is `grunt-contrib-imagemin\node_modules\jpegtran-bin\vendor\`)


## License

Everything excluding the binaries licensed under the [BSD license](http://opensource.org/licenses/bsd-license.php) and copyright Google.

libjpeg-turbo licensed under the BSD license and copyright dcommander.
