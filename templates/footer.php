<footer class="content-info" role="contentinfo">
  <div id="sidebar-footer" class="container">
    <?php if ( is_front_page() ) : ?>
    <div class="row">
      <?php dynamic_sidebar('sidebar-footer'); ?>
    </div>
    <?php endif; ?>
  </div>
  <div id="brands-footer" class="fullwidth-wrapper">
    <?php get_template_part('templates/carousel', 'brands'); ?>
  </div>
  <div id="bottom-block" class="outer-wrap">
    <div class="container">
    <div id="copyright" class="row">
      <div class="nav-footer-wrap">
          <nav class="nav-footer" role="navigation"> <span>&copy; <?php echo date('Y'); ?> <a href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a></span>
          <?php if (has_nav_menu('footer_navigation')) :
                wp_nav_menu(array('theme_location' => 'footer_navigation', 'menu_class' => 'footer-menu'));
            endif; ?>
          <?php wp_nav_menu(array('menu' => 'Socials', 'menu_class' => 'socials-menu pull-right')); ?>
          </nav>
        </div>
      </div>
    </div><!-- /.container -->

  </div><!-- /.ourer-wrap -->
  
</footer>

<?php wp_footer(); ?>