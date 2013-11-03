<?php if (is_page_template('template-map.php')){ ?>
<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
<?php } ?>
<style>
<?php global $post ?>
<?php if (get_field('header_image')) { ?>
  .header-background {
    background-image: url("<?php echo the_field("header_background"); ?>");
  }
<?php } ?>
</style>
<div class="page-header-wrapper">
  <div class="page-header">
    <h1><span class="text-wrap"><?php echo roots_title(); ?></span></h1>
  </div>
</div>
<?php get_template_part('templates/content', 'breadcrumbs'); ?>
<?php if (is_page_template('template-map.php')){ ?>
</div>
<?php } ?>