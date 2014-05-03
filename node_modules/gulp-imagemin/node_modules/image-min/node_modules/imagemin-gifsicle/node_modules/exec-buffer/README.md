# exec-buffer [![Build Status](https://travis-ci.org/kevva/exec-buffer.svg?branch=master)](https://travis-ci.org/kevva/exec-buffer)

> Run a Buffer through a child process

## Install

```bash
$ npm install --save exec-buffer
```

## Usage

```js
var ExecBuffer = require('exec-buffer');
var fs = require('fs');
var gifsicle = require('gifsicle').path;

var execBuffer = new ExecBuffer();

execBuffer
    .use(gifsicle, ['-o', execBuffer.dest, execBuffer.src])
    .run(fs.readFileSync('test.gif'), function (err, data) {
        if (err) {
            throw err;
        }

        console.log(data);
        // <Buffer 47 49 46 38 37 61 ...>
    });
});
```

## API

### new ExecBuffer

Creates a new `ExecBuffer` instance.

### .use(bin, args)

Accepts a path to a binary and an Array of arguments.

### .src(path)

Set or get the temporary source path.

### .dest(path)

Set or get the temporary destination path.

### .run(buf, cb)

Run the Buffer through the child process.

## License

[MIT License](http://en.wikipedia.org/wiki/MIT_License) © [Kevin Mårtensson](https://github.com/kevva)
