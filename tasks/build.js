'use strict';

module.exports = function(grunt) {
	grunt.registerTask('build', function() {
		grunt.log.subhead("Running 'build.js'");
		grunt.task.run([
	    'jshint',
	    'less:build',
	    'autoprefixer:build',
	    'uglify',
	    'modernizr',
	    'version'
		]);
	});
}; 