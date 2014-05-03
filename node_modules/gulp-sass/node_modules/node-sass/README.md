# node-sass

[![Build Status](https://secure.travis-ci.org/andrew/node-sass.png?branch=master)](https://travis-ci.org/andrew/node-sass)
[![NPM version](https://badge.fury.io/js/node-sass.png)](http://badge.fury.io/js/node-sass)
[![Dependency Status](https://david-dm.org/andrew/node-sass.png?theme=shields.io)](https://david-dm.org/andrew/node-sass)
[![devDependency Status](https://david-dm.org/andrew/node-sass/dev-status.png?theme=shields.io)](https://david-dm.org/andrew/node-sass#info=devDependencies)
[![Coverage Status](https://coveralls.io/repos/andrew/node-sass/badge.png)](https://coveralls.io/r/andrew/node-sass)
[![Gitter chat](https://badges.gitter.im/andrew/node-sass.png)](https://gitter.im/andrew/node-sass)

Node-sass is a library that provides binding for Node.js to [libsass], the C version of the popular stylesheet preprocessor, Sass.

It allows you to natively compile .scss files to css at incredible speed and automatically via a connect middleware.

Find it on npm: <https://npmjs.org/package/node-sass>

## Reporting Sass compilation and syntax issues

The [libsass] library is not currently at feature parity with the 3.2 [Ruby Gem](https://github.com/nex3/sass) that most Sass users will use, and has little-to-no support for 3.3 syntax. While we try our best to maintain feature parity with [libsass], we can not enable features that have not been implemented in [libsass] yet.

If you'd like to see what features are still upcoming in [libsass], [Jo Liss](http://twitter.com/jo_liss) has written [a blog post on the subject](http://www.solitr.com/blog/2014/01/state-of-libsass/).

Please check for [issues on the libsass repo](https://github.com/hcatlin/libsass/issues) (as there is a good chance that it may already be an issue there for it), and otherwise [create a new issue there](https://github.com/hcatlin/libsass/issues/new).

If this project is missing an API or command line flag that has been added to [libsass], then please open an issue here. We will then look at updating our [libsass] submodule and create a new release. You can help us create the new release by rebuilding binaries, and then creating a pull request to the [node-sass-binaries](https://github.com/andrew/node-sass-binaries) repo.

## Install

    npm install node-sass

## Usage

```javascript
var sass = require('node-sass');
sass.render({
	file: scss_filename,
	success: callback
	[, options..]
	});
// OR
var css = sass.renderSync({
	data: scss_content
	[, options..]
});
```

### Options

The API for using node-sass has changed, so that now there is only one variable - an options hash. Some of these options are optional, and in some circumstances some are mandatory.

#### file
`file` is a `String` of the path to an `scss` file for [libsass] to render. One of this or `data` options are required, for both render and renderSync.

#### data
`data` is a `String` containing the scss to be rendered by [libsass]. One of this or `file` options are required, for both render and renderSync. It is recommended that you use the `includePaths` option in conjunction with this, as otherwise [libsass] may have trouble finding files imported via the `@import` directive.

#### success
`success` is a `Function` to be called upon successful rendering of the scss to css. This option is required but only for the render function. If provided to renderSync it will be ignored.

#### error
`error` is a `Function` to be called upon occurance of an error when rendering the scss to css. This option is optional, and only applies to the render function. If provided to renderSync it will be ignored.

#### includePaths
`includePaths` is an `Array` of path `String`s to look for any `@import`ed files. It is recommended that you use this option if you are using the `data` option and have **any** `@import` directives, as otherwise [libsass] may not find your depended-on files.

#### imagePath
`imagePath` is a `String` that represents the public image path. When using the `image-url()` function in a stylesheet, this path will be prepended to the path you supply. eg. Given an `imagePath` of `/path/to/images`, `background-image: image-url('image.png')` will compile to `background-image: url("/path/to/images/image.png")`

#### outputStyle
`outputStyle` is a `String` to determine how the final CSS should be rendered. Its value should be one of `'nested'` or `'compressed'`.
[`'expanded'` and `'compact'` are not currently supported by [libsass]]

#### sourceComments
`sourceComments` is a `String` to determine what debug information is included in the output file. Its value should be one of `'none', 'normal', 'map'`. The default is `'none'`.
The `map` option will create the source map file in your CSS destination.
[Important: `souceComments` is only supported when using the `file` option, and does nothing when using `data` flag.]

#### sourceMap
If your `sourceComments` option is set to `map`, `sourceMap` allows setting a new path context for the referenced Sass files.
The source map describes a path from your CSS file location, into the the folder where the Sass files are located. In most occasions this will work out-of-the-box but, in some cases, you may need to set a different output.

### renderFile()

Same as `render()` but writes the CSS and sourceMap (if requested) to the filesystem.

#### outFile

`outFile` specifies where to save the CSS.

#### sourceMap

`sourceMap` specifies that the source map should be saved.

- If falsy the source map will not be saved
- If `sourceMap === true` the source map will be saved to the
standard location of `path.basename(options.outFile) + '.map'`
- Otherwise specifies the path (relative to the `outFile`) 
where the source map should be saved


### Examples

```javascript
var sass = require('node-sass');
sass.render({
	data: 'body{background:blue; a{color:black;}}',
	success: function(css){
  		console.log(css)
	},
	error: function(error) {
		console.log(error);
	},
	includePaths: [ 'lib/', 'mod/' ],
	outputStyle: 'compressed'
});
// OR
console.log(sass.renderSync({
	data: 'body{background:blue; a{color:black;}}',
	outputStyle: 'compressed'
}));
```

### Edge-case behaviours

* In the case that both `file` and `data` options are set, node-sass will only attempt to honour the `file` directive.

## Connect/Express middleware

Recompile `.scss` files automatically for connect and express based http servers

```javascript
var server = connect.createServer(
  sass.middleware({
      src: __dirname
    , dest: __dirname + '/public'
    , debug: true
    , outputStyle: 'compressed'
    , prefix:  '/prefix'
  }),
  connect.static('/prefix', __dirname + '/public')
);
```

Heavily inspired by <https://github.com/LearnBoost/stylus>

## DocPad Plugin

[@jking90](https://github.com/jking90) wrote a [DocPad](http://docpad.org/) plugin that compiles `.scss` files using node-sass: <https://github.com/jking90/docpad-plugin-nodesass>

## Grunt extension

[@sindresorhus](https://github.com/sindresorhus/) has created a set of grunt tasks based on node-sass: <https://github.com/sindresorhus/grunt-sass>

## Gulp extension

[@dlmanning](https://github.com/dlmanning/) has created a gulp sass plugin based on node-sass: <https://github.com/dlmanning/gulp-sass>

## Harp

[@sintaxi](https://github.com/sintaxi)’s Harp web server implicitly compiles `.scss` files using node-sass: <https://github.com/sintaxi/harp>

## Meteor plugin

[@fourseven](https://github.com/fourseven) has created a meteor plugin based on node-sass: <https://github.com/fourseven/meteor-scss>

## Mimosa module

[@dbashford](https://github.com/dbashford) has created a Mimosa module for sass which includes node-sass: <https://github.com/dbashford/mimosa-sass>

## Example App

There is also an example connect app here: <https://github.com/andrew/node-sass-example>

## Rebuilding binaries

Node-sass includes pre-compiled binaries for popular platforms, to add a binary for your platform follow these steps:

Check out the project:

    git clone https://github.com/andrew/node-sass.git
    cd node-sass
    git submodule init
    git submodule update
    npm install
    npm install -g node-gyp
    node-gyp rebuild

## Command Line Interface

The interface for command-line usage is fairly simplistic at this stage, as seen in the following usage section.

Output will be saved with the same name as input SASS file into the current working directory if it's omitted.

### Usage
 `node-sass [options] <input.scss> [<output.css>]`

 **Options:**

      --output-style     CSS output style (nested|expanded|compact|compressed)  [default: "nested"]
      --source-comments  Include debug info in output (none|normal|map)         [default: "none"]
      --include-path     Path to look for @import-ed files                      [default: cwd]
      --help, -h         Print usage info

## Post-install Build

Install runs a series of Mocha tests to see if your machine can use the pre-built [libsass] which will save some time during install. If any tests fail it will build from source.

If you know the pre-built version will work and do not want to wait for the tests to run you can skip the tests by setting the environment variable `SKIP_NODE_SASS_TESTS` to true.

      SKIP_NODE_SASS_TESTS=true npm install

## Maintainers

This module is brought to you and maintained by the following people:

* Andrew Nesbitt ([Github](https://github.com/andrew) / [Twitter](https://twitter.com/teabass))
* Dean Mao ([Github](https://github.com/deanmao) / [Twitter](https://twitter.com/deanmao))
* Brett Wilkins ([Github](https://github.com/bwilkins) / [Twitter](https://twitter.com/bjmaz))
* Keith Cirkel ([Github](https://github.com/keithamus) / [Twitter](https://twitter.com/Keithamus))
* Laurent Goderre ([Github](https://github.com/laurentgoderre) / [Twitter](https://twitter.com/laurentgoderre))
* Nick Schonning ([Github](https://github.com/nschonni) / [Twitter](https://twitter.com/nschonni))
* Adam Yeats ([Github](https://github.com/adamyeats) / [Twitter](https://twitter.com/adamyeats))

## Contributors

We <3 our contributors! A special thanks to all those who have clocked in some dev time on this project, we really appreciate your hard work. You can find [a full list of those people here.](https://github.com/andrew/node-sass/graphs/contributors)

### Note on Patches/Pull Requests

 * Fork the project.
 * Make your feature addition or bug fix.
 * Add documentation if necessary.
 * Add tests for it. This is important so I don't break it in a future version unintentionally.
 * Send a pull request. Bonus points for topic branches.

## Copyright

Copyright (c) 2013 Andrew Nesbitt. See [LICENSE](https://github.com/andrew/node-sass/blob/master/LICENSE) for details.

[libsass]: https://github.com/hcatlin/libsass
