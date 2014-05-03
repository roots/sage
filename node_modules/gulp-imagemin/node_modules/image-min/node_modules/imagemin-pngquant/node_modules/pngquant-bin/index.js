'use strict';

var BinBuild = require('bin-build');
var BinWrapper = require('bin-wrapper');
var chalk = require('chalk');
var fs = require('fs');
var path = require('path');

/**
 * Initialize a new BinWrapper
 */

var bin = new BinWrapper()
	.src('https://raw.github.com/sindresorhus/node-pngquant-bin/0.3.0/vendor/osx/pngquant', 'darwin')
	.src('https://raw.github.com/sindresorhus/node-pngquant-bin/0.3.0/vendor/linux/x86/pngquant', 'linux', 'x86')
	.src('https://raw.github.com/sindresorhus/node-pngquant-bin/0.3.0/vendor/linux/x64/pngquant', 'linux', 'x64')
	.src('https://raw.github.com/sindresorhus/node-pngquant-bin/0.3.0/vendor/win/pngquant.exe', 'win32')
	.dest(path.join(__dirname, 'vendor'))
	.use(process.platform === 'win32' ? 'pngquant.exe' : 'pngquant');

/**
 * Only run check if binary doesn't already exist
 */

fs.exists(bin.use(), function (exists) {
	if (!exists) {
		bin.run(['--version'], function (err) {
			if (err) {
				console.log(chalk.red('✗ pre-build test failed, compiling from source...'));

				var builder = new BinBuild()
					.src('https://github.com/pornel/pngquant/archive/2.1.0.tar.gz')
					.make('make install BINPREFIX="' + bin.dest() + '"');

				return builder.build(function (err) {
					if (err) {
						return console.log(chalk.red('✗ ' + err));
					}

					console.log(chalk.green('✓ pngquant built successfully'));
				});
			}

			console.log(chalk.green('✓ pre-build test passed successfully'));
		});
	}
});

/**
 * Module exports
 */

module.exports.path = bin.use();
