<footer class="content-info" role="contentinfo">
  <div class="container">
    <?php if ( is_front_page() ) : ?>
    <div class="row">
      <?php dynamic_sidebar('sidebar-footer'); ?>
    </div>
    <?php endif; ?>
    <div id="copyright" class="row">
      <div class="nav-footer-wrap">
      <div class="span6 offset3">
        <nav class="nav-footer" role="navigation"> <span>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?></span>
     <?php if (has_nav_menu('footer_navigation')) :
              wp_nav_menu(array('theme_location' => 'footer_navigation', 'menu_class' => 'footer-menu'));
          endif; ?>
        </nav>
      </div>
      </div>
    </div>
    <?php get_template_part('templates/divider', 'bottom'); ?>
    <?php get_template_part('templates/footer-brands'); ?>
  </div>
  
</footer>

<?php wp_footer(); ?>