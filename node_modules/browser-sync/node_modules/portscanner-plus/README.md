#portscanner-plus [![Build Status](https://travis-ci.org/shakyShane/portscanner-plus.png?branch=master)](https://travis-ci.org/shakyShane/portscanner-plus)

Get multiple availble ports within a range - with optional naming

##Install

```
npm install portscanner-plus --save-dev
```

##Usage

```js
var portScanner = require("./lib/index");

var names = ['controlPanel', 'socket', 'client'];

// Return named ports as object
portScanner.getPorts(3, 3000, 4000, names).then(function (ports) {
    console.log(ports.controlPanel); // 3000
    console.log(ports.socket); // 3001
    console.log(ports.client); // 3002
});

// Return an array of ports
portScanner.getPorts(2, 3000, 4000).then(function (ports) {
    console.log(ports); // [3001, 3002]
});
```


## Contributing
In lieu of a formal styleguide, take care to maintain the existing coding style. Add unit tests for any new or changed functionality. Lint and test your code using [Grunt](http://gruntjs.com/).

## Release History
_(Nothing yet)_

## License
Copyright (c) 2013 Shane Osbourne
Licensed under the MIT license.
