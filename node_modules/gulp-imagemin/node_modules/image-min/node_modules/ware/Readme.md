
# ware

  Easily create your own middleware layer.

## Example

```js
var ware = require('ware');
var middleware = ware()
  .use(function (req, res, next) {
    res.x = 'hello';
    next();
  })
  .use(function (req, res, next) {
    res.y = 'world';
    next();
  });

middleware.run({}, {}, function (err, req, res) {
  res.x; // "hello"
  res.y; // "world"
});
```

  Give it any number of arguments:

```js
var ware = require('ware');
var middleware = ware()
  .use(function (a, b, c, next) {
    console.log(a, b, c);
    next();
  })

middleware.run(1, 2, 3); // 1, 2, 3
```

  Handles errors for you, just use a handler with an arity of `+1`:

```js
var ware = require('ware');
var middleware = ware()
  .use(function (obj, next) {
    if ('42' != obj.value) return next(new Error());
    next();
  })
  .use(function (obj, next) {
    console.log('yes!');
  })
  .use(function (err, obj, next) {
    console.log('no!');
  });

middleware.run({ life: '41' }); // "no!"
middleware.run({ life: '42' }); // "yes!"
```

## API

#### ware()

  Create a new list of middleware.

#### .use(fn)

  Push a middleware `fn` onto the list. If the middleware has an arity of one more than the input to `run` it's an error middleware.

#### .run(input..., [callback])

  Runs the middleware functions with `input...` and optionally calls `callback(err, input...)`.

## License

  (The MIT License)

  Copyright (c) 2013 Segment.io &lt;friends@segment.io&gt;

  Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the 'Software'), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

  The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

  THE SOFTWARE IS PROVIDED 'AS IS', WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
