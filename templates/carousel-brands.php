<?php
$posttype = 'brand';
$number_of_posts = -1;
$startframe = 0;
$exclude = array('-4988','-4952','-4950','-4948','-4946','-4944','-4931','-4929');
$args = array(
  'post_type'     => $posttype,
  'post__not_in'  => $exclude,
  'posts_per_page' => $number_of_posts,
  'orderby' => 'rand',
);
$the_query = new WP_Query( $args );
$classes = '';
?>
<div class="container">
  <div id="carousel-brands" class="carousel slide">
    <div class="carousel-inner">
      <div class="fade-block white left"></div>
      <div class="fade-block white right"></div>
    <?php $i = 0 ?>
      <div class="item active">
        <div class="row">
        <ul>
          <?php while ($the_query->have_posts()) : $the_query->the_post();?>
          <?php if( $i == 6 ) : ?>
        </ul>
        </div>
      </div><!-- /.item -->
      <?php $i = 0 ?>
      <div class="item">
	      <div class="row">
	        <ul>
          <?php endif; ?>
          <?php $classes = array('col-xs-12 col-sm-4 col-md-2 col-lg-2');?>
          <li <?php post_class($classes);?>>
          	<?php $graylogo          			= get_field('brand_logo_gray');?>
          	<?php $colorlogo         			= get_field('brand_logo_color');?>
						<?php $attachment_id          = get_post_thumbnail_id();?>
						<?php $size                   = "full"; ?>
						<?php $image_attributes       = wp_get_attachment_image_src( $graylogo, $size );?>
						<?php $gray_image_attributes  = wp_get_attachment_image_src( $graylogo, $size );?>
						<?php $color_image_attributes = wp_get_attachment_image_src( $colorlogo, $size );?>
						<script>
						$('.swap-<?php the_ID();?>').each(function () {
							  var curSrc = $(this).attr('src');
							  if ( curSrc === '<?php echo $gray_image_attributes[0]; ?>' ) {
							      $(this).attr('src', '<?php echo $color_image_attributes[0]; ?>');
							  }
							  if ( curSrc === '<?php echo $color_image_attributes[0]; ?>' ) {
							      $(this).attr('src', '<?php echo $gray_image_attributes[0]; ?>');
							  }
							});
						</script>
						<div class="brand-logo-wrap">
							<a title="<?php the_title();?>" href="<?php the_field('brand_website');?>"><img class="swap-<?php the_ID();?> img-responsive" alt="<?php the_title();?>" src="<?php echo $gray_image_attributes[0]; ?>" width="<?php echo $image_attributes[1]; ?>" height="<?php echo $image_attributes[2]; ?>"/></a>
						</div>
					</li>
          <?php $i++ ?>
          <?php endwhile; ?>
        </ul>
	      </div>
      </div>
    </div>
  </div>
    <div class="carousel-control-wrapper">
      <a class="carousel-control left" href="#carousel-brands" data-slide="prev"><span class=""></span></a>
      <a class="carousel-control right" href="#carousel-brands" data-slide="next"><span class=""></span></a>
    </div>
</div>
<?php wp_reset_query();?>