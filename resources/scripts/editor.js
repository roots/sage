import {registerBlockStyle, unregisterBlockStyle} from '@wordpress/blocks';

import {domReady} from '@roots/sage/client';

/**
 * Customize block styles
 */
domReady(() => {
  unregisterBlockStyle('core/button', 'outline');

  registerBlockStyle('core/button', {
    name: 'outline',
    label: 'Outline',
  });
});

/**
 * Accept module updates
 *
 * @see https://webpack.js.org/api/hot-module-replacement
 */
import.meta.webpackHot?.accept(console.error);
