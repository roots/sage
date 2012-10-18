<?php get_template_part('templates/page', 'header'); ?>

<div class="alert alert-block fade in">
  <a class="close" data-dismiss="alert">&times;</a>
  <p><?php _e('The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.', 'bc_core'); ?></p>
</div>

<p><?php _e('Please try the following:', 'bc_core'); ?></p>
<ul>
  <li><?php _e('Check your spelling', 'bc_core'); ?></li>
  <li><?php printf(__('Return to the <a href="%s">home page</a>', 'bc_core'), home_url()); ?></li>
  <li><?php _e('Click the <a href="javascript:history.back()">Back</a> button', 'bc_core'); ?></li>
</ul>

<?php get_search_form(); ?>