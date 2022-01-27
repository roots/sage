import {domReady} from '@scripts/components';

/**
 * @todo
 * This import is just a check that @roots/bud-typescript is processing
 * javascript files correctly and can be removed before merging anything
 */
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
