      <?php wp_link_pages(array('before' => '<nav class="page-nav"><p>' . __('Pages:', 'roots'), 'after' => '</p></nav>')); ?>
      <!-- <a class="btn btn-default" href="?article2pdf=1">PDF Version</a> -->
      <?php get_template_part('templates/content', 'related'); ?>
      <?php get_template_part('templates/product', 'specifications'); ?>
      <?php get_template_part('templates/product', 'submittal-sheets'); ?>
      <?php get_template_part('templates/content', 'legal'); ?>
      <?php edit_post_link('Edit', '<p>', '</p>'); ?>
      <?php if (current_user_can("manage_options")) : ?>
				<a class="btn btn-block btn-default" href="<?php echo bloginfo("siteurl") ?>/wp-admin/">Admin</a>
			<?php endif; ?>