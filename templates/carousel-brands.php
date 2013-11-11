<?php
$metaquery = array(
        array(
            'key' => 'include_in_footer', // name of custom field
            'value' => '1', // matches exaclty "red", not just red. This prevents a match for "acquired"
            'compare' => 'LIKE'
        ));
$posttype = 'brand';
$number_of_posts = 18;
$startframe = 0;
$args = array(
  'post_type'     => $posttype,
  'posts_per_page' => $number_of_posts,
  'orderby' => 'rand',
  'meta_query' => $metaquery,
);
$the_query = new WP_Query( $args );
$classes = '';
?>
<div class="container">
  <div id="carousel-brands" class="carousel slide">
    <div class="carousel-inner">
      <div class="fade-block white left reset-filter"></div>
      <div class="fade-block white right reset-filter"></div>
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

						<div class="brand-logo-wrap">
							<a title="<?php the_title();?>" href="<?php the_field('brand_website');?>"><img class="swap-<?php the_ID();?> swap img-responsive" alt="<?php the_title();?>" src="<?php echo $gray_image_attributes[0]; ?>" data-rollover="<?php echo $color_image_attributes[0]; ?>" width="<?php echo $image_attributes[1]; ?>" height="<?php echo $image_attributes[2]; ?>" /></a>
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
      <a class="carousel-control left reset-filter" href="#carousel-brands" data-slide="prev"><img src="/assets/img/arrow-left-circle.png"></a>
      <a class="carousel-control right reset-filter" href="#carousel-brands" data-slide="next"><img src="/assets/img/arrow-right-circle.png"></a>
    </div>
</div>
<?php wp_reset_query();