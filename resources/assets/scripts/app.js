/**
 * External dependencies.
 */
import $ from 'jquery';
import 'bootstrap';

$(() => {
  console.log('edit me.');
});

/**
 * Accept module updates
 *
 * @see https://webpack.js.org/api/hot-module-replacement
 */
module?.hot?.accept((err) => {
  console.err(err);
});
