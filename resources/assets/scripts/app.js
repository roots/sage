/**
 * External dependencies.
 */
import 'bootstrap';

$(() => {
  console.log('edit: sage/resources/assets/scripts/app.js');
})

/**
 * Accept module updates
 *
 * @see https://webpack.js.org/api/hot-module-replacement
 */
module.hot && module.hot.accept(err => {
  console.err(err)
});
