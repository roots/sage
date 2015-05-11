<header class="banner" role="banner">
  <div class="container">
    <h1 class="hidden"><?php bloginfo('name'); ?></h1>

    <div class="<?php if (is_single()): ?>col-xs-6 col-md-4 <?php else: ?>center<?php endif; ?>">
      <a class="brand" href="<?= esc_url(home_url('/')); ?>">
        <img src="<?= get_template_directory_uri().'/dist/images/logo.png'; ?>" title="<?php bloginfo('name'); ?>">
      </a>
      <?php if (is_home()): ?>
        <h2 class="brand-description"><?= get_bloginfo('description') ?></h2>
      <?php endif; ?>
    </div>

    <?php if (is_single() && !is_attachment()): ?>
      <div class="day-nav col-xs-6 col-md-8">
        <?php previous_post_link('%link', '<i class="glyphicon glyphicon-chevron-left"></i> <span>Previous Day</span>'); ?>
        <?php next_post_link('%link', '<span>Next Day</span> <i class="glyphicon glyphicon-chevron-right"></i>'); ?>
      </div>
    <?php endif; ?>

    <?php if (is_attachment()): ?>
      <div class="attachment-nav col-xs-6 col-md-8">
        <?php previous_image_link('full', '<i class="glyphicon glyphicon-chevron-left"></i> <span>Previous Image</span>'); ?>
        <?php next_image_link('full', '<span>Next Image</span> <i class="glyphicon glyphicon-chevron-right"></i>'); ?>
      </div>
    <?php endif; ?>
  </div>
</header>
