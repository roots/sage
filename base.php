<?php get_template_part('templates/head'); ?>
<body <?php body_class(); ?>>
  <?php roots_body_top(); ?>
  <!--[if lt IE 8]>
    <div class="alert alert-warning">
      <?php _e('You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.', 'roots'); ?>
    </div>
  <![endif]-->

  <?php
    do_action('get_header');
    // Use Bootstrap's navbar if enabled in config.php
    if (current_theme_supports('bootstrap-top-navbar')) {
      get_template_part('templates/header-top-navbar');
    } else {
      get_template_part('templates/header');
    }
  ?>

  <div class="wrap container" role="document">
    <div class="content row">
      <?php roots_content_before(); ?>
      <main class="main <?php echo roots_main_class(); ?>" role="main">
        <?php roots_content_top(); ?>
        <?php include roots_template_path(); ?>
        <?php roots_content_bottom(); ?>
      </main><!-- /.main -->
      <?php roots_content_after(); ?>
      <?php if (roots_display_sidebar()) : ?>
        <?php roots_sidebars_before(); ?>
        <aside class="sidebar <?php echo roots_sidebar_class(); ?>" role="complementary">
          <?php roots_sidebar_top(); ?>
          <?php include roots_sidebar_path(); ?>
          <?php roots_sidebar_top(); ?>
        </aside><!-- /.sidebar -->
      <?php endif; ?>
      <?php tha_sidebars_after(); ?>
    </div><!-- /.content -->
  </div><!-- /.wrap -->

  <?php get_template_part('templates/footer'); ?>
</body>
</html>
