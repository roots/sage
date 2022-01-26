import {domReady} from '@scripts/components';

import test from './test';

/**
 * Run the application when the DOM is ready.
 */
domReady(test);

/**
 * Accept module updates
 *
 * @see https://webpack.js.org/api/hot-module-replacement
 */

// @ts-ignore
import.meta.webpackHot?.accept(console.error);
