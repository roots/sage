// concat.js combines scripts into one file but does not minify, uglify.js does that
module.exports = {
  dist: {
    src: [
      	'assets/js/plugins/bootstrap/transition.js',
	    'assets/js/plugins/bootstrap/alert.js',
	    'assets/js/plugins/bootstrap/button.js',
	    'assets/js/plugins/bootstrap/carousel.js',
	    'assets/js/plugins/bootstrap/collapse.js',
	    'assets/js/plugins/bootstrap/dropdown.js',
	    'assets/js/plugins/bootstrap/modal.js',
	    'assets/js/plugins/bootstrap/tooltip.js',
	    'assets/js/plugins/bootstrap/popover.js',
	    'assets/js/plugins/bootstrap/scrollspy.js',
	    'assets/js/plugins/bootstrap/tab.js',
	    'assets/js/plugins/bootstrap/affix.js',
	    'assets/js/plugins/*.js',
	    'assets/js/_*.js'
    ],
    dest: 'assets/js/scripts.js'
  }
}
