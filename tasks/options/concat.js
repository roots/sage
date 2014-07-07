'use strict';
var jsFileList = require("../files.js").jsFileList;

module.exports = {
	options : {
		separator : ';',
	},
	dist : {
		src : [jsFileList],
		dest : 'assets/js/scripts.js',
	},
};
