#!/usr/bin/env node
var cp = require('child_process'),
  fs = require('fs'),
  path = require('path'),
  Mocha = require('mocha');

// Parse args
var force = false, debug = false;

var arch = process.arch,
  platform = process.platform,
  v8 = /[0-9]+\.[0-9]+/.exec(process.versions.v8)[0];

var args = process.argv.slice(2).filter(function(arg) {
  if (arg === '-f') {
    force = true;
    return false;
  } else if (arg.substring(0, 13) === '--target_arch') {
    arch = arg.substring(14);
  } else if (arg === '--debug') {
    debug = true;
  }
  return true;
});
if (!{ia32: true, x64: true, arm: true}.hasOwnProperty(arch)) {
  console.error('Unsupported (?) architecture: `'+ arch+ '`');
  process.exit(1);
}

// Test for pre-built library
var modPath = platform + '-' + arch + '-v8-' + v8;
if (!force && !process.env.SKIP_NODE_SASS_TESTS) {
  try {
    fs.statSync(path.join(__dirname, 'bin', modPath, 'binding.node'));
    console.log('`'+ modPath+ '` exists; testing');

    var mocha = new Mocha({
      reporter: 'dot',
      ui: 'bdd',
      timeout: 999999
    });

    mocha.addFile(path.resolve(__dirname, "test", "test.js"));

    mocha.run(function (done) {
      if (done !== 0) {
        console.log('Problem with the binary; manual build incoming');
        console.log('Please consider contributing the release binary to https://github.com/andrew/node-sass-binaries for npm distribution.');
        build();
      } else {
        console.log('Binary is fine; exiting');
      }
    });
  } catch (ex) {
    // Stat failed
    build();
  }
} else {
  build();
}

// Build it
function build() {
  cp.spawn(
    process.platform === 'win32' ? 'node-gyp.cmd' : 'node-gyp',
    ['rebuild'].concat(args),
    {customFds: [0, 1, 2]})
  .on('exit', function(err) {
    if (err) {
      if (err === 127) {
        console.error(
          'node-gyp not found! Please upgrade your install of npm! You need at least 1.1.5 (I think) '+
          'and preferably 1.1.30.'
        );
      } else {
        console.error('Build failed');
      }
      return process.exit(err);
    }
    afterBuild();
  });
}

// Move it to expected location
function afterBuild() {
  var targetPath = path.join(__dirname, 'build', debug ? 'Debug' : 'Release', 'binding.node');
  var installPath = path.join(__dirname, 'bin', modPath, 'binding.node');

  try {
    fs.mkdirSync(path.join(__dirname, 'bin', modPath));
  } catch (ex) {}

  try {
    fs.statSync(targetPath);
  } catch (ex) {
    console.error('Build succeeded but target not found');
    process.exit(1);
  }
  fs.renameSync(targetPath, installPath);
  console.log('Installed in `'+ installPath+ '`');
}
