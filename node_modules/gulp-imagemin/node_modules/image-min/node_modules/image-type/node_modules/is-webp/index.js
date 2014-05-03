'use strict';
module.exports = function (buf) {
	if (!buf || buf.length < 12) {
		return false;
	}

	return buf[8] === 87 &&
		buf[9] === 69 &&
		buf[10] === 66 &&
		buf[11] === 80;
};
