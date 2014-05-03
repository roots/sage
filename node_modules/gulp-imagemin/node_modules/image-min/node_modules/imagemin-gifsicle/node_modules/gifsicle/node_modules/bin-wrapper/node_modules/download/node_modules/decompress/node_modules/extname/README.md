# extname [![Build Status](https://travis-ci.org/kevva/extname.svg?branch=master)](https://travis-ci.org/kevva/extname)

> Get the file extension and MIME type from a file

## Install

```bash
$ npm install --save extname
```

## Usage

```js
var extname = require('extname');

console.log(extname('foobar.tar'));
// => { 'ext': 'tar', 'mime': 'application/x-tar' }
```

## CLI

You can also use it as a CLI app by installing it globally:

```bash
$ npm install --global extname
```

### Usage

```bash
$ extname --help

Usage
  $ extname <file>

Example
  $ extname file.tar.gz
```

## License

[MIT License](http://en.wikipedia.org/wiki/MIT_License) © [Kevin Mårtensson](https://github.com/kevva)
