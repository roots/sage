<?php

if ( !has_action( 'shoestrap_sidebar_override' ) )
  dynamic_sidebar('sidebar-primary');
else
  do_action( 'shoestrap_sidebar_override' );
