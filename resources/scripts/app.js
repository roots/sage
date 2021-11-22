import {domReady} from '@scripts/components';

/**
 * Remove `.no-js` from document body
 * when DOM has loaded.
 */
domReady(() => {
  document.body.classList.contains('no-js') &&
    document.body.classList.remove('no-js');
});

/**
 * Accept module updates
 *
 * @see https://webpack.js.org/api/hot-module-replacement
 */
import.meta.webpackHot?.accept(console.error);
