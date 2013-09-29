<?php
 
while(has_sub_field("product_images")): ?>
 
	<?php if(get_row_layout() == "product_image"): // layout: Content ?>
 
		<div class="product-image">
			<?php the_sub_field("product_image"); ?>
		</div>
 
	<?php elseif(get_row_layout() == "product_illustration"): // layout: File ?>
 
		<div class="product-illustration">
			<img class="img-responsive" src="<?php the_sub_field("product_image"); ?>" alt="" />
		</div>
  
	<?php endif; ?>
 
<?php endwhile;