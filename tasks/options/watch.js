'use strict';

module.exports = {
	less: {
		files: [
			'assets/less/*.less',
			'assets/less/bootstrap/*.less'
		],
		tasks: ['less', 'version']
	},
	js: {
		files: [
			'<%= jshint.all %>'
		],
		tasks: ['jshint', 'uglify', 'version']
	},
	livereload: {
		// Browser live reloading
		// https://github.com/gruntjs/grunt-contrib-watch#live-reloading
		options: {
			livereload: false
		},
		files: [
			'assets/css/main.min.css',
			'assets/js/scripts.min.js',
			'templates/*.php',
			'*.php'
		]
	}
};
