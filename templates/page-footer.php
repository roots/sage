      <?php wp_link_pages(array('before' => '<nav class="page-nav"><p>' . __('Pages:', 'roots'), 'after' => '</p></nav>')); ?>
      <?php get_template_part('templates/content', 'related'); ?>
      <?php get_template_part('templates/product', 'specifications'); ?>
      <?php get_template_part('templates/product', 'submittal-sheets'); ?>
      <?php get_template_part('templates/content', 'legal'); ?>
      <?php if (current_user_can("manage_options")) : ?>
      <div class="row">
      	<div class="col-lg-6">
					<?php edit_post_link('Edit', '<p>', '</p>'); ?>
      	</div>
      	<div class="col-lg-6">
					<a class="btn btn-block btn-default" href="<?php echo bloginfo("siteurl") ?>/wp-admin/">Admin</a>
				</div>
			</div>
			<?php endif; ?>