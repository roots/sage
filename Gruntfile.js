/*
 * Create grunt tasks in the /tasks directory
 * Define task options in the /tasks/options directory
 */
'use strict';

module.exports = function (grunt) {
	// Load all tasks
	require('load-grunt-tasks')(grunt);
	// Show elapsed time
	require('time-grunt')(grunt);

	var jsFileList = [
		'assets/vendor/bootstrap/js/transition.js',
		'assets/vendor/bootstrap/js/alert.js',
		'assets/vendor/bootstrap/js/button.js',
		'assets/vendor/bootstrap/js/carousel.js',
		'assets/vendor/bootstrap/js/collapse.js',
		'assets/vendor/bootstrap/js/dropdown.js',
		'assets/vendor/bootstrap/js/modal.js',
		'assets/vendor/bootstrap/js/tooltip.js',
		'assets/vendor/bootstrap/js/popover.js',
		'assets/vendor/bootstrap/js/scrollspy.js',
		'assets/vendor/bootstrap/js/tab.js',
		'assets/vendor/bootstrap/js/affix.js',
		'assets/js/plugins/*.js',
		'assets/js/_*.js'
	];

	// Set and then load configurable variables we can use in our grunt file
	var config = {
		pkg: grunt.file.readJSON('package.json'),
		path: {
			assets: "./assets"
		}
	};
	grunt.initConfig(config);

	// Load task options from the /tasks/options folder
	function loadConfig(path) {
		var glob = require('glob');
		var object = {};
		var key;

		glob.sync('*', {
				cwd: path
			})
			.forEach(function (option) {
				key = option.replace(/\.js$/, '');
				object[key] = require(path + option);
			});

		return object;
	}
	grunt.util._.extend(config, loadConfig('./tasks/options/'));

	// The default grunt task builds all files ready for development
	// There are more tasks defined in /tasks
	grunt.registerTask('default', function () {
		grunt.log.subhead("Running grunt and building project files");
		grunt.task.run([
			'jshint',
			'less:dev',
			'autoprefixer:dev',
			'concat'
		]);
	});

	// Load grunt tasks from the /tasks folder
	grunt.loadTasks('tasks');

	// Load all packages starting with 'grunt-'
	// Packages that don't start with 'grunt-' should be defined below
	require('load-grunt-tasks')(grunt, {
		pattern: 'grunt-*'
	});
};