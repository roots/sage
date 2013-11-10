<?php if( get_field('images') ) { ?>

    <?php while( has_sub_field('images') ) { ?>

          <?php
            $attachment = get_sub_field('image');
            $size = "medium"; // (thumbnail, medium, large, full or custom size)
            $image = wp_get_attachment_image_src( $attachment, $size );
            ?>

            <a href="#modal-<?php echo $attachment; ?>" data-toggle="modal"><img class="img-responsive img-thumbnail" alt="" title="" src="<?php echo $image[0]; ?>" /></a>

            <div id="modal-<?php echo $attachment; ?>" role="dialog" class="modal fade" aria-labelledby="myModalLabel" aria-hidden="true">
						<div class="modal-dialog" style="width: 55%;">
						<div class="modal-content">
						  <div class="modal-header">
						    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						    <h4 id="" class="modal-title"><?php the_title(); ?></h4>
						  </div>
						  <div class="modal-body">
              <?php
                $attachment = get_sub_field('image');
                $size = "large"; // (thumbnail, medium, large, full or custom size)
                $image = wp_get_attachment_image_src( $attachment, $size );
                ?>
               <img src="<?php echo $image[0]; ?>" alt="" title="" class="alignnone size-full img-responsive wp-image-<?php echo $attachment; ?>" />
						  </div>
						  <div class="modal-footer">
						    <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
						  </div>
						</div>
						</div>
						</div>

    <?php } ?>

<?php } ?>