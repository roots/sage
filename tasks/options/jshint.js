// jshint.js
module.exports = {
  options: {
    jshintrc: '.jshintrc'
  },
  beforeconcat: [
  	'Gruntfile.js',
  	'assets/js/*.js',
  	'!assets/js/scripts.min.js'
  ]
}