// custom.js is a template for you to create your own tasks
module.exports = function(grunt) {
  grunt.registerTask('custom', 'Say hello!', function() {
    grunt.log.writeln("Custom task log");
  });
};