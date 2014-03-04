<?php

/*
 * Get the content and widget areas for the footer
 */
function shoestrap_footer_content() {
	global $ss_framework;
	// Finding the number of active widget sidebars
	$num_of_sidebars = 0;

	for ( $i = 0; $i < 5; $i++ ) {
		$sidebar = 'sidebar-footer-' . $i;
		if ( is_active_sidebar( $sidebar ) ) {
			$num_of_sidebars++;
		}
	}

	// Showing the active sidebars
	for ( $i = 0; $i < 5; $i++ ) {
		$sidebar = 'sidebar-footer-' . $i;

		if ( is_active_sidebar( $sidebar ) ) {
			// Setting each column width accordingly
			$col_class = 12 / $num_of_sidebars;
		
			echo $ss_framework->open_col( 'div', array( 'medium' => $col_class ) );
			dynamic_sidebar( $sidebar );
			echo $ss_framework->close_col( 'div' );
		}
	}
	echo '</div>';

	do_action( 'shoestrap_footer_html' );
}
