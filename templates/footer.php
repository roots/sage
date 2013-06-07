<?php do_action( 'shoestrap_before_footer' );?>
<?php if ( !has_action( 'shoestrap_footer_override' ) ) { ?>
  <footer class="content-info" role="contentinfo">
    <div class="<?php echo shoestrap_container_class(); ?>">
      <?php dynamic_sidebar('sidebar-footer'); ?>
      <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?></p>
    </div>
  </footer>
<?php } else { do_action( 'shoestrap_footer_override' ); } ?>
<?php do_action( 'shoestrap_after_footer' );?>
<?php wp_footer();
