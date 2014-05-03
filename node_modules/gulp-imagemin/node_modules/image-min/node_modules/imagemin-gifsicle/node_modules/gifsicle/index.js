'use strict';

var BinWrapper = require('bin-wrapper');
var chalk = require('chalk');
var fs = require('fs');
var path = require('path');

/**
 * Initialize a new BinWrapper
 */

var bin = new BinWrapper({ bin: 'gifsicle', dest: path.join(__dirname, 'vendor') });
var bs = './configure --disable-gifview --disable-gifdiff ' +
		 '--prefix="' + bin.dest + '" ' +
		 '--bindir="' + bin.dest + '" && ' +
		 'make install';
var msg = chalk.red('\n✗ Installation of gifsicle failed\n\n') +
		  'Try installing the binary manually by visiting http://www.lcdf.org/gifsicle/\n' +
		  'and choose the desired binary for your platform.\n\n' +
		  'Then try reinstalling this module again.';

/**
 * Only run check if binary doesn't already exist
 */

fs.exists(bin.path, function (exists) {
	if (!exists) {
		bin
			.addUrl('https://raw.github.com/yeoman/node-gifsicle/0.1.5/vendor/osx/gifsicle', 'darwin')
			.addUrl('https://raw.github.com/yeoman/node-gifsicle/0.1.5/vendor/linux/x86/gifsicle', 'linux', 'x86')
			.addUrl('https://raw.github.com/yeoman/node-gifsicle/0.1.5/vendor/linux/x64/gifsicle', 'linux', 'x64')
			.addUrl('https://raw.github.com/yeoman/node-gifsicle/0.1.5/vendor/freebsd/x86/gifsicle', 'freebsd', 'x86')
			.addUrl('https://raw.github.com/yeoman/node-gifsicle/0.1.5/vendor/freebsd/x64/gifsicle', 'freebsd', 'x64')
			.addUrl('https://raw.github.com/yeoman/node-gifsicle/0.1.5/vendor/win/x86/gifsicle.exe', 'win32', 'x86')
			.addUrl('https://raw.github.com/yeoman/node-gifsicle/0.1.5/vendor/win/x86/gifsicle.exe', 'win32', 'x64')
			.addSource('http://www.lcdf.org/gifsicle/gifsicle-1.80.tar.gz')
			.check()
			.on('error', function () {
				console.error(msg);
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
				console.log(chalk.green('✓ gifsicle rebuilt successfully'));
			});
	}
});

/**
 * Module exports
 */

module.exports.path = bin.path;
