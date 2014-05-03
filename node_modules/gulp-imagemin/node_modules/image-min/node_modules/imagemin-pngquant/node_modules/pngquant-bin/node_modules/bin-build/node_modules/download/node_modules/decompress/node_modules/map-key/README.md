# map-key [![Build Status](https://travis-ci.org/kevva/map-key.svg?branch=master)](https://travis-ci.org/kevva/map-key)

> Map an object key that ends with a value

## Install

```bash
$ npm install --save map-key
```

## Usage

```js
var map = require('map-key');

map({ '.tar.gz': 'bar' }, 'foo.tar.gz');
// => 'bar'
```

## License

MIT © [Kevin Mårtensson](https://github.com/kevva)
