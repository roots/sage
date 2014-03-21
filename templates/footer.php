<?php roots_footer_before(); ?>
<footer class="content-info" role="contentinfo">
  <div class="container">
    <?php tha_footer_top(); ?>
    <?php dynamic_sidebar('sidebar-footer'); ?>
    <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?></p>
    <?php tha_footer_bottom(); ?>
  </div>
</footer>
<?php roots_footer_after(); ?>
<?php roots_body_bottom(); ?>
<?php wp_footer(); ?>
