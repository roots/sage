'use strict';

module.exports = {
	dist : {
		files : {
			'assets/js/scripts.min.js' : 
				[
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
				]
		},
		options : {
			// JS source map: to enable, uncomment the lines below and update sourceMappingURL based on your install
			// sourceMap: 'assets/js/scripts.min.js.map',
			// sourceMappingURL: '/app/themes/roots/assets/js/scripts.min.js.map'
		}
	}
};
