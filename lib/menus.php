<?php

if (!has_nav_menu('primary_navigation')) {
  $primary_nav_id = wp_create_nav_menu('Primary Navigation', array('slug' => 'primary_navigation'));
  $shoestrap_nav_theme_mod['primary_navigation'] = $primary_nav_id;
}

if ($shoestrap_nav_theme_mod) {
  set_theme_mod('nav_menu_locations', $shoestrap_nav_theme_mod);
}
