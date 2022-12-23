import { domReady } from '@roots/sage/client';
import { registerBlockStyle, unregisterBlockStyle } from '@wordpress/blocks';

/**
 * Editor entrypoint
 */
domReady(() => {
  unregisterBlockStyle('core/button', 'outline');

  registerBlockStyle('core/button', {
    name: 'outline',
    label: 'Outline',
  });
});

/**
 * @see {@link https://webpack.js.org/api/hot-module-replacement/}
 */
import.meta.webpackHot?.accept(console.error);
