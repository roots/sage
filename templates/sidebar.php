<?php
if ( !has_action( 'shoestrap_sidebar_override' ) ) { ?>

<?php dynamic_sidebar('sidebar-primary'); ?>

<?php } else { do_action( 'shoestrap_sidebar_override' ); } ?>