'use strict';

module.exports = {
	options : {
		jshintrc : '.jshintrc'
	},
	all : [
		'Gruntfile.js',
		'assets/js/*.js',
		'!assets/js/scripts.min.js'
	]
};
