<?php function remove_footer_admin () {
  echo 'Just Maintain';
}
add_filter('admin_footer_text', 'remove_footer_admin');