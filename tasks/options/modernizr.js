'use strict';

module.exports = {
	build : {
		devFile : 'assets/vendor/modernizr/modernizr.js',
		outputFile : 'assets/js/vendor/modernizr.min.js',
		files : {
			'src' : [['assets/js/scripts.min.js'], ['assets/css/main.min.css']]
		},
		uglify : true,
		parseFiles : true
	}
};
