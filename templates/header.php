<header class="banner" role="banner">
  <div class="container">
    <a class="navbar-brand" href="<?= esc_url(home_url('/')); ?>">
      <img src="<?php echo get_template_directory_uri(); ?>/dist/images/logo.svg" onerror="this.onerror=null; this.src='<?php echo get_template_directory_uri(); ?>/dist/images/logo.png'" alt="<?php bloginfo('name'); ?>" title="<?php bloginfo('name'); ?>">
    </a>
    <nav role="navigation">
      <?php
      if (has_nav_menu('primary_navigation')) :
        wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav']);
      endif;
      ?>
    </nav>
  </div>
</header>
