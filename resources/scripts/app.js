/**
 * External Dependencies
 */
import 'jquery';

$(() => {
  // console.log('Hello world');
});

/**
 * Accept module updates
 *
 * @see https://webpack.js.org/api/hot-module-replacement
 */
module?.hot?.accept((err) => {
  console.err(err);
});
