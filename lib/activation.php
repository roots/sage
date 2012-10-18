<?php

function bc_core_theme_activation_action() {
  if (!has_nav_menu('primary_navigation')) {
    $primary_nav_id = wp_create_nav_menu('Primary Navigation', array('slug' => 'primary_navigation'));
    $bc_core_nav_theme_mod['primary_navigation'] = $primary_nav_id;
  }

  if ($bc_core_nav_theme_mod) {
    set_theme_mod('nav_menu_locations', $bc_core_nav_theme_mod);
  }
}
add_action('admin_init','bc_core_theme_activation_action');