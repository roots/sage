import '@wordpress/edit-post';
import { getBlockTypes, unregisterBlockType } from '@wordpress/blocks';

/**
 * Whitelist blocks.
 * @see https://developer.wordpress.org/block-editor/developers/filters/block-filters/#using-a-whitelist
 *
 * @param object blocks
 */
const whitelistBlocks = blocks => {
  getBlockTypes().forEach(({name}) => (
    blocks.indexOf(name) === -1 && unregisterBlockType(name)
  ));
}

export { whitelistBlocks }
