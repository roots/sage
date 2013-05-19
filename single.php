<?php

if ( !has_action( 'shoestrap_single_content' ) )
  get_template_part('templates/content', 'single');
else
  do_action( 'shoestrap_single_content' );