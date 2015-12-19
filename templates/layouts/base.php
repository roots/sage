<!doctype html>
<html <?php language_attributes(); ?>>
  <?php App\template_part('partials/head'); ?>
  <body <?php body_class(); ?>>
    <!--[if IE]>
      <div class="alert alert-warning">
        <?php _e('You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.', 'sage'); ?>
      </div>
    <![endif]-->
    <?php
      do_action('get_header');
      App\template_part('partials/header');
    ?>
    <div class="wrap container" role="document">
      <div class="content row">
        <main class="main">
          <?php App\template_unwrap(); ?>
        </main><!-- /.main -->
        <?php if (App\display_sidebar()) : ?>
          <aside class="sidebar">
            <?php App\template_sidebar(); ?>
          </aside><!-- /.sidebar -->
        <?php endif; ?>
      </div><!-- /.content -->
    </div><!-- /.wrap -->
    <?php
      do_action('get_footer');
      App\template_part('partials/footer');
      wp_footer();
    ?>
  </body>
</html>
