'use strict';

var BinWrapper = require('bin-wrapper');
var chalk = require('chalk');
var fs = require('fs');
var path = require('path');

/**
 * Initialize a new BinWrapper
 */

var bin = new BinWrapper({ bin: 'jpegtran', dest: path.join(__dirname, 'vendor') });
var bs = './configure --disable-shared ' +
		 '--prefix="' + bin.dest + '" && ' + 'make install';
var args = [
	'-copy', 'none',
	'-optimize',
	'-outfile', path.join(__dirname, 'test/tmp/test.jpg'),
	path.join(__dirname, 'test/fixtures/test.jpg')
];

/**
 * Only run check if binary doesn't already exist
 */

fs.exists(bin.path, function (exists) {
	if (!exists) {
		if (!fs.existsSync(path.join(__dirname, 'test/tmp'))) {
			fs.mkdirSync(path.join(__dirname, 'test/tmp'));
		}

		bin
			.addUrl('https://raw.github.com/yeoman/node-jpegtran-bin/0.2.4/vendor/osx/jpegtran', 'darwin')
			.addUrl('https://raw.github.com/yeoman/node-jpegtran-bin/0.2.4/vendor/linux/x86/jpegtran', 'linux', 'x86')
			.addUrl('https://raw.github.com/yeoman/node-jpegtran-bin/0.2.4/vendor/linux/x64/jpegtran', 'linux', 'x64')
			.addUrl('https://raw.github.com/yeoman/node-jpegtran-bin/0.2.4/vendor/freebsd/jpegtran', 'freebsd')
			.addUrl('https://raw.github.com/yeoman/node-jpegtran-bin/0.2.4/vendor/sunos/x86/jpegtran', 'sunos', 'x86')
			.addUrl('https://raw.github.com/yeoman/node-jpegtran-bin/0.2.4/vendor/sunos/x64/jpegtran', 'sunos', 'x64')
			.addUrl('https://raw.github.com/yeoman/node-jpegtran-bin/0.2.4/vendor/win/x86/jpegtran.exe', 'win32', 'x86')
			.addUrl('https://raw.github.com/yeoman/node-jpegtran-bin/0.2.4/vendor/win/x64/jpegtran.exe', 'win32', 'x64')
			.addFile('https://raw.github.com/yeoman/node-jpegtran-bin/0.2.4/vendor/win/x86/libjpeg-62.dll', 'win32', 'x86')
			.addFile('https://raw.github.com/yeoman/node-jpegtran-bin/0.2.4/vendor/win/x64/libjpeg-62.dll', 'win32', 'x64')
			.addSource('http://downloads.sourceforge.net/project/libjpeg-turbo/1.3.0/libjpeg-turbo-1.3.0.tar.gz')
			.check(args)
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
				console.log(chalk.green('✓ jpegtran rebuilt successfully'));
			});
	}
});

/**
 * Module exports
 */

module.exports.path = bin.path;
