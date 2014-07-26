<?php
/**
 * Utility functions
 */
function is_element_empty($element) {
  $element = trim($element);
  return !empty($element);
}

// Tell WordPress to use searchform.php from the templates/ directory
function roots_get_search_form($form) {
  $form = '';
  locate_template('/templates/searchform.php', true, false);
  return $form;
}
add_filter('get_search_form', 'roots_get_search_form');

// Tell WordPress to use passwordform.php from the templates/ directory
function roots_get_the_password_form($output) {
  $output = '';
  locate_template('/templates/passwordform.php', true, false);
  return $output;
}
add_filter('the_password_form', 'roots_get_the_password_form');
