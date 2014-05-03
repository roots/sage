# multipipe

A better `Stream#pipe` that creates duplex streams and lets you handle errors in one place.

[![build status](https://secure.travis-ci.org/segmentio/multipipe.png)](http://travis-ci.org/segmentio/multipipe)

## Example

```js
var pipe = require('multipipe');

// pipe streams
var stream = pipe(streamA, streamB, streamC);

// centralized error handling
stream.on('error', fn);

// creates a new stream
source.pipe(stream).pipe(dest);
```

## Duplex streams

  Write to the pipe and you'll really write to the first stream, read from the pipe and you'll read from the last stream.

```js
var stream = pipe(a, b, c);

source
  .pipe(stream)
  .pipe(destination);
```

  In this example the flow of data is:

  * source ->
  * a ->
  * b ->
  * c ->
  * destination

## Error handling

  Each `pipe` forwards the errors the streams it wraps emit, so you have one central place to handle errors:

```js
var stream = pipe(a, b, c);

stream.on('error', function(err){
  // called three times
});

a.emit('error', new Error);
b.emit('error', new Error);
c.emit('error', new Error);
```

## API

### pipe(stream, ...)

Pass a variable number of streams and each will be piped to the next one.

A stream will be returned that wraps passed in streams in a way that errors will be forwarded and you can write to and/or read from it.

## Installation

```bash
$ npm install multipipe
```

## License

  MIT
