# gzip-size [![Build Status](https://travis-ci.org/sindresorhus/gzip-size.svg?branch=master)](https://travis-ci.org/sindresorhus/gzip-size)

> Get the gzipped size of a string or buffer


## Install

```bash
$ npm install --save gzip-size
```


## Usage

```js
var gzipSize = require('gzip-size');

var string = 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.';

console.log(string.length);
//=> 191

console.log(gzipSize.sync(string));
//=> 78
```


## API

### gzipSize(input, callback)

#### input

*Required*  
Type: `String`|`Buffer`

#### callback(err, size)

*Required*  
Type: `Function`

### gzipSize.sync(input)

*Required*  
Type: `String`|`Buffer`  
Returns: size


## CLI

You can also use it as a CLI app by installing it globally:

```bash
$ npm install --global gzip-size
```

#### Usage

```bash
$ gzip-size --help

gzip-size <input-file>
or
cat <input-file> | gzip-size
```

#### Example

```bash
$ gzip-size jquery.min.js
29344
```

or with [pretty-bytes](https://github.com/sindresorhus/pretty-bytes):

```bash
$ pretty-bytes $(gzip-size jquery.min.js)
29.34 kB
```


## License

[MIT](http://opensource.org/licenses/MIT) © [Sindre Sorhus](http://sindresorhus.com)
