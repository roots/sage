<?php

if ( !has_action( 'shoestrap_page_header' ) )
	get_template_part('templates/page', 'header');
else
	do_action( 'shoestrap_page_header' );

if ( !has_action( 'shoestrap_single_page_content' ) )
	get_template_part('templates/content', 'page');
else
	do_action( 'shoestrap_single_page_content' );