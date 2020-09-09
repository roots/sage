/**
 * External Dependencies
 */
import 'jquery';
import 'bootstrap';

$(document).ready(() => {
  // console.log('Hello world');
});

/**
 * Accept module updates
 *
 * @see https://webpack.js.org/api/hot-module-replacement
 * @see https://webpack.js.org/api/hot-module-replacement/#accept-self
 */
if (module.hot) {
  module.hot.accept(err =>
    console.err(err)
  );
}
