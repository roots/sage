<?php

while ( have_posts() ) : the_post();
	shoestrap_title_section();
	do_action( 'shoestrap_page_pre_content' );
	the_content();
	echo '<div class="clearfix"></div>';
	do_action( 'shoestrap_page_after_content' );

	wp_link_pages( array( 'before' => '<nav class="pagination">', 'after' => '</nav>' ) );
endwhile;