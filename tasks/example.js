/*
 * example.js is a template for creating your own grunt tasks
 */

'use strict';

module.exports = function(grunt) {
	grunt.registerTask('example', function() {
		grunt.log.subhead("Running 'example.js'");
		grunt.log.writeln("Success! This example task has printed a message to the console.");
		grunt.log.writeln("You can create new tasks by defining them in the /tasks folder.");
	});
}; 