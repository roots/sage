<?php

// Add our custom admin-styles file
function shoestrap_add_custom_smof_admin_styles() {
  wp_enqueue_style('admin-style-shoestrap', get_template_directory_uri() . SMOF_DIR . '/addons/assets/css/admin-style.css');
}
add_action('of_style_only_after', 'shoestrap_add_custom_smof_admin_styles');