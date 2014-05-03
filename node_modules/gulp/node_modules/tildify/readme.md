# tildify [![Build Status](https://travis-ci.org/sindresorhus/tildify.svg?branch=master)](https://travis-ci.org/sindresorhus/tildify)

> Convert an absolute path to tilde path: `/Users/sindresorhus/dev` => `~/dev`


## Install

```bash
$ npm install --save tildify
```


## Usage

```js
var tildify  = require('tildify');

tildify('/Users/sindresorhus/dev');
//=> ~/dev
```


## License

[MIT](http://opensource.org/licenses/MIT) © [Sindre Sorhus](http://sindresorhus.com)
