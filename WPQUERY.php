<?php
// WP_Query arguments
$args = array (
	//'parent'                 => 'xx',
	//'category_name'          => 'category-slug',
	'post_type'              => 'product',
	'tax_query' => array(
		array(
			'taxonomy' => 'product_cat',
			'field' => 'slug',						// ID or slug
			'terms' => '',							// array of IDs or slugs
			'operator' => 'NOT IN',				// Possible values are 'IN', 'NOT IN', 'AND'.
			'include_children'	=>	''		// (boolean) - Whether or not to include children for hierarchical taxonomies. Defaults to true.
		)
	),
	'meta_query'             => array(
		array(
			'key'       => 'part_number',
			'value'     => '00000000',
			'compare'   => '=',
		),
	),
);

// The Query
$query = new WP_Query( $args );

// The Loop
if ( $query->have_posts() ) {
	while ( $query->have_posts() ) {
		$query->the_post();
		// do something
	}
} else {
	// no posts found
}

// Restore original Post Data
wp_reset_postdata();