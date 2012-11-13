<?php

// Adds the Admin page for Shoestrap
add_action( 'admin_menu', 'shoestrap_admin_page' );
function shoestrap_admin_page() {
  add_theme_page( 'Shoestrap', 'Shoestrap', 'manage_options', 'shoestrap_options', 'shoestrap_admin_page_content' );
}

function shoestrap_admin_page_content() { ?>

  <div class="wrap">
    <h2><?php _e( 'Shoestrap Administration Page' ); ?></h2>
    <?php do_action( 'shoestrap_admin_content' ); ?>
  </div>
  <?php
}
