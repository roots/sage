<?php get_template_part('templates/page', 'header'); ?>
<?php
  /**
   * woocommerce_before_main_content hook
   *
   * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
   * @hooked woocommerce_breadcrumb - 20
   */
  do_action('woocommerce_before_main_content');
?>

  <?php while (have_posts()) : the_post(); ?>
    <?php woocommerce_get_template_part('content', 'single-product'); ?>
  <?php endwhile; ?>

<?php
  /**
   * woocommerce_after_main_content hook
   *
   * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
   */
  do_action('woocommerce_after_main_content');
?>