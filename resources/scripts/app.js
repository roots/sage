import {domReady} from '@roots/sage/client';

const main = () => {
  // application code
};

domReady(main);

/**
 * @see https://webpack.js.org/api/hot-module-replacement
 */
import.meta.webpackHot?.accept(console.error);
