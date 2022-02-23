import {domReady} from '@roots/sage/client';
import {registerBlockStyle, unregisterBlockStyle} from '@wordpress/blocks';

const main = () => {
  unregisterBlockStyle('core/button', 'outline');

  registerBlockStyle('core/button', {
    name: 'outline',
    label: 'Outline',
  });
};

domReady(main);

/**
 * @see https://webpack.js.org/api/hot-module-replacement
 */
import.meta.webpackHot?.accept(console.error);
