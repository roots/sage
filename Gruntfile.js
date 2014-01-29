/*

This Gruntfile will find and read tasks in the /tasks directory
and all task options in /tasks/options.

To add new commands to the default build task save its options to 
/task/options and add a reference to the task as an option in 
grunt.registerTask('default') at the bottom of this file.

If you're deep in terminal and forget them, run `grunt --help` to list
all available tasks.

*/
'use strict';
module.exports = function(grunt) {

  // Utility to load the different option files
  // based on their names
  function loadConfig(path) {
    var glob = require('glob');
    var object = {};
    var key;

    glob.sync('*', {cwd: path}).forEach(function(option) {
      key = option.replace(/\.js$/,'');
      object[key] = require(path + option);
    });

    return object;
  }

  // Initial config
  var config = {
    pkg: grunt.file.readJSON('package.json')
  };

  // Load tasks from the tasks folder
  grunt.loadTasks('tasks');

  // Load all the tasks options in tasks/options base on the name:
  // watch.js => watch{}
  grunt.util._.extend(config, loadConfig('./tasks/options/'));

  grunt.initConfig(config);

  require('load-grunt-tasks')(grunt);

  // Default Task is build
  // Find the options for these tasks in /tasks/options
  grunt.registerTask('default', ['clean', 'concat', 'uglify', 'less', 'cssmin', 'version']);

};
