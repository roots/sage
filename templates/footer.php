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
  <div id="bottom-block" class="outer-wrap">
    <div class="container">
      <div id="copyright" class="row">
        <div class="nav-footer-wrap">
        	<?php if (current_user_can("manage_options")) : ?>
						<a href="<?php echo bloginfo("siteurl") ?>/wp-admin/">Admin</a>
					<?php endif; ?>
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
