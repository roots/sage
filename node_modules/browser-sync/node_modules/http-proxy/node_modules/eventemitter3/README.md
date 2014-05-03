# EventEmitter3

EventEmitter3 is a faster alternative to EventEmitter2 and the build-in
EventEmitter that ships within Node.js. It removes some features that you might
not need:

- Domain support.
- Thrown errors when there are no error listeners specified.
- That a `newListener` event is emitted when an event is emitted.
- No silly `setMaxListeners`.
- No silly `listenerCount` function.. Just do `EventEmitter.listeners(event).length`

And adds some features you want:

- Emit events with a custom context without binding: `EE.on(event, fn, context)`
  which also works with once `EE.once(event, fn, context)`

It's a drop in replacement of your existing EventEmitters, but just faster. Free
performance, who wouldn't want that.

The source of the EventEmitter is compatible for browser usage, no fancy pancy
`Array.isArray` stuff is used, it's just plain ol JavaScript that should even
work IE5 if you want to. This module currently serves it's use in
[Primus](http://github.com/primus/primus)'s client file.

## Installation

```bash
$ npm install --save eventemitter3
```
or as a [component](http://component.io)

```bash
$ component install eventemitter3
```

then

```js
var EventEmitter = require('eventemitter3');

// or

var EventEmitter = require('eventemitter3').EventEmitter;
```

For API methods see the official Node.js documentation: 

http://nodejs.org/api/events.html
