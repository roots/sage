<?php

/*
 * Adds the Administration page for Shoestrap.
 * This page will hold any option for the shoestrap theme
 * as well as any shoestrap addon plugins.
 */
add_action( 'admin_menu', 'shoestrap_admin_page' );
function shoestrap_admin_page() {
  add_theme_page( 'Shoestrap', 'Shoestrap', 'manage_options', 'shoestrap_options', 'shoestrap_admin_page_content' );
}

/*
 * The content of the administration page for Shoestrap.
 * We add an action here called 'shoestrap_admin_content'
 * that all other plugins and addons can hook into.
 */
function shoestrap_admin_page_content() { ?>
  <div class="wrap">
    <h2><?php _e( 'Shoestrap Administration Page' ); ?></h2>
    <?php do_action( 'shoestrap_admin_content' ); ?>
  </div>
  <?php
}
