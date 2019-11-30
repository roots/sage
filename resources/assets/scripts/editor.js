import '@wordpress/edit-post';
import domReady from '@wordpress/dom-ready';
import { registerBlockStyle } from '@wordpress/blocks';

domReady(() => {
  registerBlockStyle('core/button', {
    name: 'ghost',
    label: 'Ghost',
  });
});
