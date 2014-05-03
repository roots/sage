'use strict';
module.exports = function (buf) {
	if (!buf || buf.length < 4) {
		return false;
	}

	return buf[0] === 56 &&
		buf[1] === 66 &&
		buf[2] === 80 &&
		buf[3] === 83;
};
