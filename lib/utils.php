<?php

namespace Roots\Sage\Utils;

/**
 * Tell WordPress to use searchform.php from the templates/ directory
 */
add_filter('get_search_form', function () {
  $form = '';
  locate_template('/templates/searchform.php', true, false);
  return $form;
});
