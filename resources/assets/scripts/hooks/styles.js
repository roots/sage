import '@wordpress/edit-post';
import domReady from '@wordpress/dom-ready';
import { registerBlockStyle, unregisterBlockStyle } from '@wordpress/blocks';

/**
 * Register specified styles.
 *
 * @param object keyed by block; styles to be registered.
 */
const registerBlockStyles = items => {
  domReady(() => items.forEach(({block, styles}) => (
    styles.forEach(style => registerBlockStyle(block, style))
  )));
}

/**
 * Unregister specified styles.
 *
 * @param object keyed by block; styles to be unregistered.
 */
const unregisterBlockStyles = items => {
  domReady(() => items.forEach(({block, styles}) => (
    styles.forEach(style => unregisterBlockStyle(block, style))
  )));
}

export {
  registerBlockStyles,
  unregisterBlockStyles,
}
