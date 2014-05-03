'use strict';
module.exports = function (buf) {
	if (!buf || buf.length < 4) {
		return false;
	}

	return buf[0] === 73 &&
		buf[1] === 73 &&
		buf[2] === 42 &&
		buf[3] === 0;
};
