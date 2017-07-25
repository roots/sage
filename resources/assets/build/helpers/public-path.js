/* eslint-env browser */
/* globals SAGE_DIST_PATH */

/** Dynamically set absolute public path from current protocol and host */
if (SAGE_DIST_PATH) {
  __webpack_public_path__ = SAGE_DIST_PATH; // eslint-disable-line no-undef, camelcase
}
