# read-chunk [![Build Status](https://travis-ci.org/sindresorhus/read-chunk.png?branch=master)](http://travis-ci.org/sindresorhus/read-chunk)

> Read a chunk from a file

Because the built-in way is too much boilerplate.


## Install

Install with [npm](https://npmjs.org/package/read-chunk)

```
npm install --save read-chunk
```


## Example

```js
var readChunk = require('read-chunk');

// foo.txt => hello

readChunk.sync('foo.txt', 1, 3);
//=> ell
```


## API

### readChunk(filepath, position, length, callback)

#### filepath

Type: `String`

#### position

Type: `Number`

Position to start reading.

#### length

Type: `Number`

Number of bytes to read.

#### callback(error, buffer)

Type: `Function`


### readChunk.sync(filepath, start, length)

Same arguments as `readChunk` except the callback.

Returns a buffer.


## License

MIT © [Sindre Sorhus](http://sindresorhus.com)
