<?php

/*
 * Get the content and widget areas for the footer
 */
function shoestrap_footer_content() {
	// Finding the number of active widget sidebars
	$num_of_sidebars = 0;
	$base_class = 'col-md-';

	for ( $i=0; $i<5 ; $i++ ) {
		$sidebar = 'sidebar-footer-'.$i.'';
		if ( is_active_sidebar( $sidebar ) )
			$num_of_sidebars++;
	}

	// Showing the active sidebars
	for ( $i=0; $i<5 ; $i++ ) {
		$sidebar = 'sidebar-footer-' . $i;

		if ( is_active_sidebar( $sidebar ) ) {
			// Setting each column width accordingly
			$col_class = 12 / $num_of_sidebars;
		
			echo '<div class="' . $base_class . $col_class . '">';
			dynamic_sidebar( $sidebar );
			echo '</div>';
		}
	}
	echo '</div>';

	do_action( 'shoestrap_footer_html' );
}
