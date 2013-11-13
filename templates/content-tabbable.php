      <div class="tabbable">
        <ul class="nav nav-tabs">
        <?php $count = 1; ?>
        <?php while( has_sub_field("tabs")): ?>
        
        <?php $tab_id = get_sub_field("tab_label"); ?>
        <?php $tab_slug = convert_to_slug($tab_id); ?>
        
        	<li <?php if($count == 1) echo 'class="active"'; ?> ><a href="#<?php echo $tab_slug; ?>" data-toggle="tab"><?php the_sub_field("tab_label");?></a></li>
        	
        <?php $count++; ?>
        <?php endwhile; ?>
        </ul>
        <div class="tab-content">
        
        <?php $count = 1; ?>
        <?php while( has_sub_field("tabs")): ?>
        
        <?php $tab_id = get_sub_field("tab_label"); ?>
        <?php $tab_slug = convert_to_slug($tab_id); ?>
        
          <div class="tab-pane <?php if($count == 1) echo 'active'; ?>" id="<?php echo $tab_slug; ?>">
          <?php if(get_row_layout() == "overview_tab"): ?>
	          <?php if($count == 1 && has_post_thumbnail()) { ?>
	           <div class="pull-right wrap-product-photo"><?php the_post_thumbnail('medium', array('class' => 'img-thumbnail')); ?></div>
	          <?php } ?>
						<?php if($count > 1) { ?><?php if( get_sub_field('tab_image') ) { ?><div class="pull-right wrap-product-photo"><img class="img-thumbnail" src="<?php the_sub_field('tab_image');?>" /></div><?php } ?><?php } ?>
            <?php the_sub_field("tab_content"); ?>
          <?php elseif(get_row_layout() == "specifications_tab"): ?>
						<?php if( get_sub_field('tab_image') ) { ?>
							<?php
								$attachment_id = get_sub_field('tab_image');
								$size = "medium"; // (thumbnail, medium, large, full or custom size)
								 
								$image = wp_get_attachment_image_src( $attachment_id, $size );
								// url = $image[0];
								// width = $image[1];
								// height = $image[2];
								?>
								<div class="pull-right wrap-product-photo"><img class="img-thumbnail"  src="<?php echo $image[0]; ?>" /></div>
						<?php } ?>
            <?php the_sub_field("tab_content"); ?>
          <?php elseif(get_row_layout() == "product_information_tab"): ?>
						<div class="panel panel-default">
						  <!-- Default panel contents -->
						  <?php if( get_sub_field('panel_heading') ) { ?><div class="panel-heading"><?php the_sub_field('panel_heading'); ?></div><?php } ?>
						  
						  <?php if( get_sub_field('panel_body') ) { ?><div class="panel-body"><?php the_sub_field('panel_body');?></div><?php } ?>
						  <?php /* if( get_sub_field('tab_image') ) { ?>
								<?php
									$attachment_id = get_sub_field('tab_image');
									$size = "full"; // (thumbnail, medium, large, full or custom size)
									 
									$image = wp_get_attachment_image_src( $attachment_id, $size );
									// url = $image[0];
									// width = $image[1];
									// height = $image[2];
									?>
									<div class="pull-right wrap-product-photo"><img class="img-thumbnail"  src="<?php echo $image[0]; ?>" /></div>
							<?php } */ ?>
						  
						  <!-- Table -->
	            <div class="table-responsive">
	            	<?php the_sub_field("tab_content"); ?>
	            </div>
							<?php if( get_sub_field('panel_footer') ) { ?><div class="panel-footer"><?php the_sub_field('panel_footer'); ?></div><?php } ?>
						</div>
          <?php elseif(get_row_layout() == "normal_tab"): ?>
						<?php if( get_sub_field('tab_image') ) { ?>
							<?php
								$attachment_id = get_sub_field('tab_image');
								$size = "full"; // (thumbnail, medium, large, full or custom size)
								 
								$image = wp_get_attachment_image_src( $attachment_id, $size );
								// url = $image[0];
								// width = $image[1];
								// height = $image[2];
								?>
								<div class="pull-right wrap-product-photo"><img class="img-thumbnail"  src="<?php echo $image[0]; ?>" /></div>
						<?php } ?>
            <?php the_sub_field("tab_content"); ?>
          <?php endif; ?>

          </div>
          
        <?php $count++; ?>
        <?php endwhile; ?>
        
        </div><!--/.tab-content -->
      </div><!--/.tabbable -->
