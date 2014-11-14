<?php

global $ss_framework;

while ( have_posts() ) : the_post();
	shoestrap_title_section();
	do_action( 'shoestrap_entry_meta' );
	do_action( 'shoestrap_page_pre_content' );

	echo '<div class="entry-content">';
	do_action( 'shoestrap_page_pre_content' );
		the_content();
		echo $ss_framework->clearfix();
	echo '</div>';
	
	shoestrap_meta( 'cats' );
	shoestrap_meta( 'tags' );
	do_action( 'shoestrap_page_after_content' );

	wp_link_pages( array( 'before' => '<nav class="pagination">', 'after' => '</nav>' ) );
endwhile;