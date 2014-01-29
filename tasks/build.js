// build.js gives you a `grunt build` task, it's the same as the default task but its command is a bit simpler to remember
module.exports = function(grunt) {
  grunt.registerTask('build', ['clean', 'concat', 'uglify', 'less', 'cssmin', 'version']);
};