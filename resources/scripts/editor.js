import '@wordpress/edit-post';
import domReady from '@wordpress/dom-ready';
import {registerBlockStyle, unregisterBlockStyle} from '@wordpress/blocks';

/**
 * Block styles
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
if (module) {
  module.hot?.accept((err) => {
    console.err(err);
  });
}
