<footer class="content-info" role="contentinfo">
  <div class="<?php echo shoestrap_container_class(); ?>">
    <?php dynamic_sidebar('sidebar-footer'); ?>
    <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?></p>
  </div>
</footer>

<?php wp_footer(); ?>
