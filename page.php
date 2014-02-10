<?php

if ( !has_action( 'shoestrap_content_page_override' ) )
	get_template_part( 'templates/content', 'page' );
else
	do_action( 'shoestrap_content_page_override' );
