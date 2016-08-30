/* globals WEBPACK_PUBLIC_PATH */

// Dynamically set absolute public path from current protocol and host
if (WEBPACK_PUBLIC_PATH !== false) {
  /* eslint-disable no-undef */
  __webpack_public_path__ = location.protocol + '//' + location.host + WEBPACK_PUBLIC_PATH;
  /*eslint-enable no-undef*/
}
