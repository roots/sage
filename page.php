<?php

if ( !has_action( 'shoestrap_page_header_override' ) )
	get_template_part('templates/page', 'header');
else
	do_action( 'shoestrap_page_header_override' );

if ( !has_action( 'shoestrap_single_page_content' ) )
	get_template_part('templates/content', 'page');
else
	do_action( 'shoestrap_single_page_content' );
