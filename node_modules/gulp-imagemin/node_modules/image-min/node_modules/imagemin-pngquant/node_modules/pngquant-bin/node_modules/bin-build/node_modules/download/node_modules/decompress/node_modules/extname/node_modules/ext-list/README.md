# ext-list [![Build Status](https://travis-ci.org/kevva/ext-list.svg?branch=master)](https://travis-ci.org/kevva/ext-list)

> Return a list of known file extensions and their MIME types

## Install

```bash
$ npm install --save ext-list
```

## Usage

```js
var extList = require('ext-list');

console.log(extList);
// => { '3gp': 'video/3gpp', a: 'application/octet-stream', ai: 'application/postscript', ... }
```

## License

[MIT License](http://en.wikipedia.org/wiki/MIT_License) © [Kevin Mårtensson](https://github.com/kevva)
