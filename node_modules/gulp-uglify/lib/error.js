'use strict';
var PluginError = require('gulp-util/lib/PluginError');

module.exports = function uglifyError() {
	var Factory = PluginError.bind.apply(PluginError, [].concat(null, 'gulp-uglify', Array.prototype.slice.call(arguments)));
	return new Factory();
};
