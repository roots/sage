// version.js updates the version for css + js in lib/scripts.php to cache-bust
module.exports = {
  options: {
    file: 'lib/scripts.php',
    css: 'assets/css/main.min.css',
	cssHandle: 'roots_main',
	js: 'assets/js/scripts.min.js',
	jsHandle: 'roots_scripts'
  }
}