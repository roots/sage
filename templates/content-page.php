<?php

global $ss_framework;

while ( have_posts() ) : the_post();
	shoestrap_title_section();
	do_action( 'shoestrap_entry_meta' );
	do_action( 'shoestrap_page_pre_content' );
	the_content();
	echo $ss_framework->clearfix();
	shoestrap_meta( 'cats' );
	shoestrap_meta( 'tags' );
	do_action( 'shoestrap_page_after_content' );

	wp_link_pages( array( 'before' => '<nav class="pagination">', 'after' => '</nav>' ) );
endwhile;
