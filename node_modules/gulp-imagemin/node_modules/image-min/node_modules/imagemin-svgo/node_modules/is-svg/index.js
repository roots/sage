'use strict';
module.exports = function (buf) {
	return /<svg[^>]+xmlns="http:\/\/www\.w3\.org\/2000\/svg"[^>]*>/.test(buf);
};
