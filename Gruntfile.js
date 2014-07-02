/*
 * Create grunt tasks in the /tasks directory
 * Define task options in the /tasks/options directory
 */

'use strict';

module.exports = function(grunt) {

	// Set and then load configurable variables we can use in our grunt file
	var config = {
		pkg : grunt.file.readJSON('package.json'),
		path : {
			assets : "./assets"
		}
	};
	grunt.initConfig(config);

	// Load task options from the /tasks/options folder
	function loadConfig(path) {
		var glob = require('glob');
		var object = {};
		var key;

		glob.sync('*', {
			cwd : path
		}).forEach(function(option) {
			key = option.replace(/\.js$/, '');
			object[key] = require(path + option);
		});

		return object;
	}
	grunt.util._.extend(config, loadConfig('./tasks/options/'));

	// The default grunt task builds all files ready for development
	// There are more tasks defined in /tasks
	grunt.registerTask('default', function() {
		grunt.log.subhead("Running grunt and building project files");
		grunt.task.run([
		    'clean',
		    'less',
		    'uglify',
		    'version'
		]);
	});

	// Load grunt tasks from the /tasks folder
	grunt.loadTasks('tasks');

	// Load all packages starting with 'grunt-'
	// Packages that don't start with 'grunt-' should be defined below
	require('load-grunt-tasks')(grunt, {
		pattern : 'grunt-*'
	});
	
};
