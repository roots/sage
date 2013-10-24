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