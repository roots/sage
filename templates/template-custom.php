<?php
/**
 * Template Name: Custom Template
 */
?>

<?php while (have_posts()) : the_post(); ?>
  <?php App\template_part('partials/page-header'); ?>
  <?php App\template_part('partials/content-page'); ?>
<?php endwhile; ?>
