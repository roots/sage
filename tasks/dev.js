// dev.js gives you a `grunt dev` task, which builds and then watches for changes
module.exports = function(grunt) {
  grunt.registerTask('dev', ['clean', 'concat', 'uglify', 'less', 'cssmin', 'version', 'watch']);
};