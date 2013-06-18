<?php
/*
Template Name: Custom Template
*/

if ( !has_action( 'shoestrap_page_header_override' )
  get_template_part('templates/page', 'header');
else
  do_action( 'shoestrap_page_header_override' );

get_template_part('templates/content', 'page');
