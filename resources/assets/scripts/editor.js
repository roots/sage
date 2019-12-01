import { __ } from '@wordpress/i18n';
import { filterCategories } from './hooks/inserter'
import { whitelistBlocks } from './hooks/whitelist';
import { registerBlockStyles, unregisterBlockStyles } from './hooks/styles'

/**
 * Restricts blocks to the following list.
 */
whitelistBlocks([
  'core/audio',
  'core/button',
  'core/column',
  'core/columns',
  'core/cover',
  'core/embed',
  'core/file',
  'core/gallery',
  'core/group',
  'core/heading',
  'core/html',
  'core/image',
  'core/list',
  'core/media-text',
  'core/more',
  'core/paragraph',
  'core/preformatted',
  'core/pullquote',
  'core/shortcode',
  'core/search',
  'core/text-columns',
  'core/quote',
  'core/table',
  'core/video',
]);

/**
 * Flattens block categories to a single "Blocks" category
 */
filterCategories('blocks');

/**
 * Unregister core block default styles.
*/
unregisterBlockStyles([
  {block: 'core/button',    styles: ['outline', 'fill']},
  {block: 'core/image',     styles: ['default', 'circle-mask']},
  {block: 'core/pullquote', styles: ['default', 'solid-color']},
  {block: 'core/table',     styles: ['regular', 'stripes']},
  {block: 'core/quote',     styles: ['default', 'large']},
]);

/**
 * Register styles onto blocks.
 */
registerBlockStyles([
  {
    block: 'core/button',
    styles: [
      {name: 'solid',   label: __('Solid', 'sage')},
      {name: 'outline', label: __('Outline', 'sage')},
    ],
  },
]);
