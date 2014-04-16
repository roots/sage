<?php

global $ss_framework;

echo '<article '; post_class(); echo '>';

	do_action( 'shoestrap_in_article_top' );
	shoestrap_title_section( true, 'h2', true );
	if ( has_action( 'shoestrap_entry_meta_override' ) ) {
		do_action( 'shoestrap_entry_meta_override' );
	} else {
		do_action( 'shoestrap_entry_meta' );	
	}

	echo '<div class="entry-summary">';
		echo apply_filters( 'shoestrap_do_the_excerpt', get_the_excerpt() );
		echo $ss_framework->clearfix();
	echo '</div>';

	if ( has_action( 'shoestrap_entry_footer' ) ) {
		echo '<footer class="entry-footer">';
		do_action( 'shoestrap_entry_footer' );
		echo '</footer>';
	}

	do_action( 'shoestrap_in_article_bottom' );

echo '</article>';
