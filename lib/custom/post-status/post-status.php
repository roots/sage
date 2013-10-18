<?php
if ( ! function_exists('discontinued_post_status') ) {

// Register Custom Status
function discontinued_post_status() {

	$args = array(
		'label'                     => _x( 'discontinued', 'Status General Name', 'atkore' ),
		'label_count'               => _n_noop( 'Discontinued (%s)',  'Discontinued (%s)', 'atkore' ), 
		'public'                    => true,
		'show_in_admin_all_list'    => true,
		'show_in_admin_status_list' => true,
		'exclude_from_search'       => true,
	);
	register_post_status( 'discontinued', $args );

}

// Hook into the 'init' action
add_action( 'init', 'discontinued_post_status', 0 );