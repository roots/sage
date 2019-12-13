import '@wordpress/edit-post';
import { registerBlockStyle, unregisterBlockStyle } from '@wordpress/blocks';

/**
 * Register specified styles.
 *
 * @param object keyed by block; styles to be registered.
 */
const registerBlockStyles = items => {
  items.forEach(({block, styles}) => (
    styles.forEach(style => registerBlockStyle(block, style))
  ));
}

/**
 * Unregister specified styles.
 *
 * @param object keyed by block; styles to be unregistered.
 */
const unregisterBlockStyles = items => {
  items.forEach(({block, styles}) => (
    styles.forEach(style => unregisterBlockStyle(block, style))
  ));
}

export {
  registerBlockStyles,
  unregisterBlockStyles,
}
