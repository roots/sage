'use strict';

module.exports = {
	default: {
		options: {
			format: true,
			length: 32,
			manifest: 'assets/manifest.json',
			querystring: {
				style: 'roots_css',
				script: 'roots_js'
			}
		},
		files: {
			'lib/scripts.php': 'assets/{css,js}/{main,scripts}.min.{css,js}'
		}
	}
};
