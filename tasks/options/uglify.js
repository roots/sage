'use strict';
var jsFileList = require("../files.js").jsFileList;

module.exports = {
	dist : {
		files : {
			'assets/js/scripts.min.js' : [jsFileList]
		}
	}
};
