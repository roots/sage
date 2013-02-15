<?php get_template_part('templates/page', 'header'); ?>

<div class="alert">
  <?php _e('Sorry, but the page you were trying to view does not exist.', 'roots'); ?>
</div>

<p><?php _e('It looks like this was the result of either:', 'roots'); ?></p>
<ul>
  <li><?php _e('a mistyped address', 'roots'); ?></li>
  <li><?php _e('an out-of-date link', 'roots'); ?></li>
</ul>

<?php get_search_form(); ?>
