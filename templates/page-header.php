<?php if (!is_front_page()){ ?>
<style>
<?php global $post ?>
.background-image {
  background-image: url("<?php echo the_field("header_image"); ?>");
  background-position: <?php echo the_field("header_image_position"); ?>;
}

.no-backgroundsize .background-image {
  background-image: none;
  filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php echo the_field("header_image"); ?>',sizingMethod='scale');
  -ms-filter: "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php echo the_field('header_image'); ?>',sizingMethod='scale')";
}

<?php if (get_field('header_subtitle') == null) { ?>
.page-header h1 {
  padding-top: 50px;
}
<?php } ?>
</style>
<div class="page-header">
  <div class="background-image"></div>
    <div class="header-text">
        <h1><?php echo roots_title(); ?></h1>
        <?php if (! get_field('header_subtitle') == null) { ?><h2><?php echo the_field('header_subtitle'); ?></h2><?php } ?>
    </div>
</div>
<?php } ?>