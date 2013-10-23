<?php
// http://www.advancedcustomfields.com/resources/field-types/relationship/
$posts = get_posts(array(
	'post_type' => 'product',
	'meta_query' => array(
		array(
			'key' => 'part_number', // name of custom field
			'value' => '"123"', // matches exaclty "123", not just 123. This prevents a match for "1234"
			'compare' => 'LIKE'
		)
	)
));
 
if( $posts )
{

}
 
?>