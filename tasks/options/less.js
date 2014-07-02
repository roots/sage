'use strict';

module.exports = {
	dist : {
		files : {
			'assets/css/main.min.css' : ['assets/less/app.less']
		},
		options : {
			compress : true,
			// LESS source map
			// To enable, set sourceMap to true and update sourceMapRootpath based on your install
			sourceMap : false,
			sourceMapFilename : 'assets/css/main.min.css.map',
			sourceMapRootpath : '/app/themes/roots/'
		}
	}
};
