'use strict';
module.exports = function (buf) {
	if (!buf) {
		return false;
	}

	if (require('is-jpg')(buf)) {
		return 'jpg';
	}

	if (require('is-png')(buf)) {
		return 'png';
	}

	if (require('is-gif')(buf)) {
		return 'gif';
	}

	if (require('is-webp')(buf)) {
		return 'webp';
	}

	if (require('is-tif')(buf)) {
		return 'tif';
	}

	if (require('is-bmp')(buf)) {
		return 'bmp';
	}

	if (require('is-jxr')(buf)) {
		return 'jxr';
	}

	if (require('is-psd')(buf)) {
		return 'psd';
	}

	return false;
};
