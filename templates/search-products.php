<?php get_template_part('templates/page', 'header'); ?>
<div class="row">
  <div class="col-lg-12">
		<div class="archive-description">
		<?php woocommerce_product_finder( array( 'use_category' => false , 'search_attributes' => array( 'genre' , 'size' , 'colour' ) ) );?>
		</div>
  </div>
</div>
