// Allow working with BrowserSync external URL + Webpack HMR:
// In external clients, hot-update.json gets called from `local:3000` instead of `xx.xx.xx.xx:3000`
// https://github.com/webpack/webpack/issues/443
/*global __webpack_require__ b:true*/
if(__webpack_require__)
  __webpack_require__.p = __webpack_require__.p.replace('localhost', window.location.hostname)
