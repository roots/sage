<style>
<?php global $post ?>
<?php if (get_field('header_image')) { ?>
  .header-background {
    background-image: url("<?php echo the_field("header_background"); ?>");
  }
<?php } ?>

<?php if (get_field('header_subtitle') == null || is_archive() || is_post_type_archive() || is_term() ) { ?>
  .page-header h1 {
    padding-top: 50px;
  }
<?php } ?>

</style>
<div class="page-header-wrapper">
  <div class="page-header">
    <div class="header-background">
      <div class="header-background-grad"></div>
    </div>
    <div class="header-text">
      <h1><?php echo roots_title(); ?></h1>
    </div>
  </div>
</div>