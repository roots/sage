'use strict';
module.exports = function (str) {
	var dir = process.env.HOME;

	if (process.platform === 'win32') {
		dir = dir || process.env.USERPROFILE || process.env.HOMEDRIVE + process.env.HOMEPATH;
	}

	return str.replace(dir, '~');
};
