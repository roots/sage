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

    <?php if (is_single()): ?>
      <div class="day-nav col-xs-6 col-md-8">
        <a href="#prev" class="change-day prev"><i class="glyphicon glyphicon-chevron-left"></i> Previous Day</a>
        <a href="#next" class="change-day next">Next Day <i class="glyphicon glyphicon-chevron-right"></i></a>
      </div>
    <?php endif; ?>
  </div>
</header>
