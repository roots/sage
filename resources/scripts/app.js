import {main} from '@scripts/components/main';

/**
 * Initialize scripts
 */
const init = () =>
  window.requestAnimationFrame(function ready() {
    return document.body ? main() : window.requestAnimationFrame(ready);
  });

init();

/**
 * Accept module updates
 *
 * @see https://webpack.js.org/api/hot-module-replacement
 */
if (module) {
  module.hot?.accept('./components/main.js', init);
}
