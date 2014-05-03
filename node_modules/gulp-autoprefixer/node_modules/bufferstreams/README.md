# BufferStreams [![NPM version](https://badge.fury.io/js/bufferstreams.png)](https://npmjs.org/package/bufferstreams) [![Build Status](https://travis-ci.org/nfroidure/BufferStreams.png?branch=master)](https://travis-ci.org/nfroidure/BufferStreams)

BufferStreams abstracts streams to allow you to deal with the whole contents in
 a single buffer when it becomes necessary (by example: a legacy library that
 do not support streams).

It is not a good practice, just some glue. Using BufferStreams means:
* there is no library dealing with streams for your needs
* you filled an issue to the wrapped library to support streams

## Usage
Install the [npm module](https://npmjs.org/package/bufferstreams):
```sh
npm install bufferstreams --save
```
Then, in your scripts:
```js
var BufferStreams = require('bufferstreams');

Fs.createReadStream('input.txt')
  .pipe(new BufferStreams(function(err, buf, cb) {

    // err will be filled with an error if the piped in stream emits one.
    if(err) {
      throw err;
    }

    // buf will contain the whole piped in stream contents
    buf = Buffer(buf.toString(utf-8).repalce('foo', 'bar'));

    // cb is a callback to pass the result back to the piped out stream
    // first argument is an error that wil be emitted if one
    // the second argument is the modified buffer
    cb(null, buf);

  }))
  .pipe(Fs.createWriteStream('output.txt'));
```

## Contributing
Feel free to pull your code if you agree with publishing it under the MIT license.

