'use strict';

var BinWrapper = require('bin-wrapper');
var chalk = require('chalk');
var fs = require('fs');
var path = require('path');

/**
 * Initialize a new BinWrapper
 */

var bin = new BinWrapper({ bin: 'optipng', dest: path.join(__dirname, 'vendor') });
var bs = './configure --with-system-zlib ' +
		 '--mandir="' + path.join(bin.dest, 'man') + '" ' +
		 '--bindir="' + bin.dest + '" && ' +
		 'make install';

/**
 * Only run check if binary doesn't already exist
 */

fs.exists(bin.path, function (exists) {
	if (!exists) {
		bin
			.addUrl('https://raw.github.com/yeoman/node-optipng-bin/0.3.2/vendor/osx/optipng', 'darwin')
			.addUrl('https://raw.github.com/yeoman/node-optipng-bin/0.3.2/vendor/linux/x86/optipng', 'linux', 'x86')
			.addUrl('https://raw.github.com/yeoman/node-optipng-bin/0.3.2/vendor/linux/x64/optipng', 'linux', 'x64')
			.addUrl('https://raw.github.com/yeoman/node-optipng-bin/0.3.2/vendor/freebsd/optipng', 'freebsd')
			.addUrl('https://raw.github.com/yeoman/node-optipng-bin/0.3.2/vendor/sunos/x86/optipng', 'sunos', 'x86')
			.addUrl('https://raw.github.com/yeoman/node-optipng-bin/0.3.2/vendor/sunos/x64/optipng', 'sunos', 'x64')
			.addUrl('https://raw.github.com/yeoman/node-optipng-bin/0.3.2/vendor/win/optipng.exe', 'win32')
			.addSource('http://downloads.sourceforge.net/project/optipng/OptiPNG/optipng-0.7.4/optipng-0.7.4.tar.gz')
			.check()
			.on('error', function (err) {
				console.log(chalk.red('✗ ' + err.message));
			})
			.on('fail', function () {
				if (process.platform === 'win32') {
					return console.log(chalk.red('✗ building is not supported on ' + process.platform));
				}

				console.log(chalk.red('✗ pre-build test failed, compiling from source...'));

				this.build(bs);
			})
			.on('success', function () {
				console.log(chalk.green('✓ pre-build test passed successfully'));
			})
			.on('finish', function () {
				console.log(chalk.green('✓ optipng rebuilt successfully'));
			});
	}
});

/**
 * Module exports
 */

module.exports.path = bin.path;
module.exports.stream = path.join(__dirname, 'stream.js');
