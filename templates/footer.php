<footer class="content-info" role="contentinfo">
  <?php if ( is_front_page() ) {
    if( get_field('homepage_call_to_action', 'options') ) { ?>
        <?php get_template_part('templates/call-to-action', 'home'); ?>
    <?php } ?>
  <?php } ?>
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
  <div id="bottom-block" class="outer-wrap">
    <div class="container">
      <div id="copyright" class="row">
        <div class="nav-footer-wrap">
          <?php get_template_part('templates/menu-navbar', 'footer'); ?>
        </div><!-- /.nav-footer-wrap -->
      </div><!-- /.nav-footer-wrap -->
    </div><!-- /#copyright.row -->
  </div><!-- /#bottom-block.outer-wrap -->
  <div id="brands-footer" class="fullwidth-wrapper">
    <?php get_template_part('templates/carousel', 'brands'); ?>
  </div>
</footer><!-- /.content-info -->
<?php get_template_part('templates/content', 'modals'); ?>
<?php wp_footer(); ?>
