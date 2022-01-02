import {domReady} from '@scripts/components';

/**
 * Run the application when the DOM is ready.
 */
domReady(() => {
  // Application code.
});

/**
 * Accept module updates
 *
 * @see https://webpack.js.org/api/hot-module-replacement
 */
import.meta.webpackHot?.accept(console.error);
