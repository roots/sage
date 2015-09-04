<?php

use Roots\Sage\Config;
use Roots\Sage\Wrapper;

?>

<!doctype html>
<html class="no-js" <?php language_attributes(); ?>>
  <?php get_template_part('templates/head'); ?>
  <body <?php body_class(); ?>>
    <!--[if lt IE 9]>
      <div class="alert alert-warning">
        <?php _e('You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.', 'sage'); ?>
      </div>
    <![endif]-->
    <div class="mdl-layout__container">
      <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
        <?php
          do_action('get_header');
          get_template_part('templates/header');
        ?>
        <div class="mdl-layout__content" role="document">
          <div class="mdl-grid">
            <?php
            if (Config\display_sidebar()) :
              $col = 'mdl-cell--8-col';
            else :
              $col = 'mdl-cell--12-col';
            endif;
            ?>
            <main class="mdl-cell <?php echo $col; ?>" role="main">
              <?php include Wrapper\template_path(); ?>
            </main><!-- main -->
            <?php if (Config\display_sidebar()) : ?>
              <aside class="mdl-cell mdl-cell--4-col" role="complementary">
                <?php include Wrapper\sidebar_path(); ?>
              </aside><!-- sidebar -->
            <?php endif; ?>
          </div><!-- /.page-content.mdl-grid -->
          <?php
            do_action('get_footer');
            get_template_part('templates/footer');
            wp_footer();
          ?>
        </div><!-- /.mdl-layout__content -->
      </div><!-- /.mdl-layout -->
    </div>
  </body>
</html>
