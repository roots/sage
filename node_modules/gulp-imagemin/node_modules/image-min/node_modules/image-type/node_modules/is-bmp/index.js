'use strict';
module.exports = function (buf) {
	if (!buf || buf.length < 2) {
		return false;
	}

	return buf[0] === 66 && buf[1] === 77;
};
