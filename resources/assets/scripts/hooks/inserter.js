import { addFilter } from '@wordpress/hooks'

/**
 * Flatten all blocks into a single category.
 *
 * @see /app/filters.php L25-31.
 * @see https://developer.wordpress.org/block-editor/developers/filters/block-filters/#managing-block-categories
 *
 * @param string name of new category to place modified blocks in
 * @param array  categories which should not be overwritten
 */
const filterCategories = (newCategory, exceptions = []) => {
  addFilter('blocks.registerBlockType', 'sage/inserter', (props) => {
    props.category = exceptions.includes(props.category)
      ? props.category
      : props.category = newCategory;

    return props;
  });
}

export { filterCategories }
