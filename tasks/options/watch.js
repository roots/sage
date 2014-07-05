'use strict';

module.exports = {
	less : {
		files : ['assets/less/*.less', 'assets/less/**/*.less'],
		tasks : ['less:dev', 'autoprefixer:dev']
	},
	js : {
		files : [jsFileList, '<%= jshint.all %>'],
		tasks : ['jshint', 'concat']
	},
	livereload : {
		// Browser live reloading
		// https://github.com/gruntjs/grunt-contrib-watch#live-reloading
		options : {
			livereload : false
		},
		files : ['assets/css/main.css', 'assets/js/scripts.js', 'templates/*.php', '*.php']
	}
};
