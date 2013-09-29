<footer class="content-info" role="contentinfo">
  <div id="sidebar-footer" class="container">
    <?php if ( is_front_page() ) : ?>
    <div class="row">
      <?php dynamic_sidebar('homepage-full-width-widget'); ?>
    </div>
    <div class="row">
      <?php dynamic_sidebar('homepage-widgets'); ?>
    </div>
    <?php endif; ?>
  </div><!-- /.container -->
  <div id="brands-footer" class="fullwidth-wrapper">
    <?php get_template_part('templates/carousel', 'brands'); ?>
  </div>
  <div id="bottom-block" class="outer-wrap">
    <div class="container">
      <div id="copyright" class="row">
        <div class="nav-footer-wrap">
            <nav class="nav-footer" role="navigation">
            <div class="footer-menu-wrap">
              <span>&copy; <?php echo date('Y'); ?> <a href="<?php bloginfo('url'); ?>" title="<?php bloginfo('name'); ?>"><?php bloginfo('name'); ?></a></span>
            <?php if (has_nav_menu('footer_navigation')) :
                  wp_nav_menu(array('theme_location' => 'footer_navigation', 'menu_class' => 'footer-menu'));
              endif; ?>
            </div>
            <div class="social-menu-wrap pull-right">
            <?php if (has_nav_menu('social_nav')) :
                  wp_nav_menu(array('theme_location' => 'social_nav', 'menu_class' => 'socials-menu'));
              endif; ?>
            </div>
          </nav>

        </div><!-- /.nav-footer-wrap -->
      </div><!-- /.nav-footer-wrap -->
    </div><!-- /#copyright.row -->
  </div><!-- /#bottom-block.outer-wrap -->
</footer><!-- /.content-info -->
<?php get_template_part('templates/content', 'modals'); ?>
<?php wp_footer(); ?>
