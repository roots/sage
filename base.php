<?php get_template_part('templates/head'); ?>
<body <?php body_class(); ?>>

  <!--[if lt IE 7]><div class="alert">Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</div><![endif]-->

  <?php
    // Use Bootstrap's navbar if enabled in config.php
    if (current_theme_supports('bootstrap-top-navbar')) {
      get_template_part('templates/header-top-navbar');
    } else {
      get_template_part('templates/header');
    }
  ?>
  
  <?php do_action('bc_core_branding'); ?>
  <?php do_action('bc_core_hero'); ?>
  
  <?php dynamic_sidebar('hero-area'); ?>
  
  <?php do_action('bc_core_pre_wrap'); ?>
  <div id="wrap" class="container" role="document">
    <?php do_action('bc_core_pre_content'); ?>
    <div id="content" class="row">
      <?php do_action('bc_core_pre_main'); ?>
      <div id="main" class="<?php echo bc_core_main_class(); ?>" role="main">
        <?php include bc_core_template_path(); ?>
      </div>
      <?php do_action('bc_core_after_main'); ?>
      <?php if (bc_core_display_sidebar()) : ?>
      <aside id="sidebar" class="<?php echo bc_core_sidebar_class(); ?>" role="complementary">
        <?php do_action('bc_core_pre_sidebar'); ?>
        <?php get_template_part('templates/sidebar'); ?>
        <?php do_action('bc_core_after_sidebar'); ?>
      </aside>
      <?php endif; ?>
    </div><!-- /#content -->
    <?php do_action('bc_core_after_content'); ?>
  </div><!-- /#wrap -->
  <?php do_action('bc_core_after_wrap'); ?>
  
  <?php do_action('bc_core_pre_footer'); ?>
  <?php get_template_part('templates/footer'); ?>
  <?php do_action('bc_core_after_footer'); ?>

</body>
</html>
