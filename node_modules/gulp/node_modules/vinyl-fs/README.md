# vinyl-fs [![NPM version][npm-image]][npm-url] [![Build Status][travis-image]][travis-url] [![Coveralls Status][coveralls-image]][coveralls-url] [![Dependency Status](https://david-dm.org/wearefractal/vinyl.png?theme=shields.io)](https://david-dm.org/wearefractal/vinyl-fs)

## Information

<table>
<tr>
<td>Package</td><td>vinyl-fs</td>
</tr>
<tr>
<td>Description</td>
<td>Vinyl adapter for the file system</td>
</tr>
<tr>
<td>Node Version</td>
<td>>= 0.10</td>
</tr>
</table>

## Usage

```javascript
var es = require('event-stream');
var fs = require('vinyl-fs');

var log = function(file, cb) {
  console.log(file.path);
  cb(null, file);
};

fs.src(["./js/**/*.js", "!./js/vendor/*.js"])
  .pipe(es.map(log))
  .pipe(fs.dest("./output"));
```

## API

### src(globs[, opt])

- Takes a glob string or an array of glob strings as the first argument.
- Possible options for the second argument:
  - buffer - `true` or `false` if you want to buffer the file.
    - Default value is `true`
    - `false` will make file.contents a paused Stream
  - read - `true` or `false` if you want the file to be read or not. Useful for stuff like `rm`ing files.
    - Default value is `true`
    - `false` will disable writing the file to disk via `.dest()`
  - Any glob-related options are documented in [glob-stream] and [node-glob]
- Returns a Readable/Writable stream.
- On write the stream will simply pass items through.
- This stream emits matching [vinyl] File objects

### watch(globs[, opt, cb])

This is just [glob-watcher]

- Takes a glob string or an array of glob strings as the first argument.
- Possible options for the second argument:
  - Any options are passed to [gaze]
- Returns an EventEmitter
  - 'changed' event is emitted on each file change
- Optionally calls the callback on each change event

### dest(folder[, opt])

- Takes a folder path as the first argument.
- Possible options for the second argument:
  - cwd - Specify the working directory the folder is relative to. Default is `process.cwd()`
  - mode - Specify the mode the files should be created with. Default is the mode of the input file (file.stat.mode)
- Returns a Readable/Writable stream.
- On write the stream will save the [vinyl] File to disk at the folder/cwd specified.
- After writing the file to disk it will be emitted from the stream so you can keep piping these around

[glob-stream]: https://github.com/wearefractal/glob-stream
[node-glob]: https://github.com/isaacs/node-glob
[gaze]: https://github.com/shama/gaze
[glob-watcher]: https://github.com/shama/gaze
[vinyl]: https://github.com/wearefractal/vinyl

[npm-url]: https://npmjs.org/package/vinyl-fs
[npm-image]: https://badge.fury.io/js/vinyl-fs.png
[travis-url]: https://travis-ci.org/wearefractal/vinyl-fs
[travis-image]: https://travis-ci.org/wearefractal/vinyl-fs.png?branch=master
[coveralls-url]: https://coveralls.io/r/wearefractal/vinyl-fs
[coveralls-image]: https://coveralls.io/repos/wearefractal/vinyl-fs/badge.png
[depstat-url]: https://david-dm.org/wearefractal/vinyl-fs
[depstat-image]: https://david-dm.org/wearefractal/vinyl-fs.png
