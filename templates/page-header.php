<?php if (!is_front_page()){ ?>
<style>
<?php global $post ?>
.background-image {
  background-image: url("<?php echo the_field("header_image"); ?>");
  background-position: <?php echo the_field("header_image_position"); ?>;
  background-repeat: no-repeat;

  height: 150px;
  position: relative;
  z-index: 10;
  bottom: 0;
  right: 0;
}
</style>

<div class="page-header">
  <div class="background-image">
    <h1>
      <?php echo roots_title(); ?>
    </h1>
    <h2>
      <?php echo the_field('header_subtitle'); ?>
    </h2>
  </div>
</div>
<?php } ?>