<?php

/**
 * Allow for custom post types to be indexed using FacetWP.
 */
// add_filter('facetwp_indexer_query_args', function($args) {
//   $args['post_type'] = ['custom-post-type'];
//   return $args;
// });

/**
 * Modify how fields are indexed.
 */
// add_filter('facetwp_index_row', function($params, $class) {
//   switch ($params['facet_name']) {
//     case 'volume':
//       $params['facet_display_value'] = $params['facet_value'] . ' m<sup>3</sup>';
//       break;
//   }
//   return $params;
// }, 10, 2);
