<?php

function shoestrap_layout_css() {
  $sidebar_location = get_theme_mod( 'shoestrap_aside_layout' );
  ?>

  <style>
    <?php if ( $sidebar_location == 'left' ) { ?>
      #main{ float: right; }
    <?php } ?>
  </style>
  <?php
}
add_action( 'wp_head', 'shoestrap_layout_css', 199 );
