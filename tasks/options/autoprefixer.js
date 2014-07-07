'use strict';

module.exports = {
	options : {
		browsers : ['last 2 versions', 'ie 8', 'ie 9', 'android 2.3', 'android 4', 'opera 12']
	},
	dev : {
		options : {
			map : 'assets/css/'
		},
		src : 'assets/css/main.css'
	},
	build : {
		src : 'assets/css/main.min.css'
	}
};
