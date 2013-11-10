<?php if( get_field('images') ) { ?>

    <?php while( has_sub_field('images') ) { ?>

          <?php
            $attachment = get_sub_field('image');
            $size = "medium"; // (thumbnail, medium, large, full or custom size)
            $image = wp_get_attachment_image_src( $attachment, $size );
            ?>

            <a href="#modal-<?php echo $attachment; ?>" data-toggle="modal"><img class="img-responsive img-thumbnail" alt="" title="" src="<?php echo $image[0]; ?>" /></a>

            <div id="modal-<?php echo $attachment; ?>" class="modal fade">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              </div>
              <?php
                $attachment = get_sub_field('image');
                $size = "large"; // (thumbnail, medium, large, full or custom size)
                $image = wp_get_attachment_image_src( $attachment, $size );
                ?>
              <div class="modal-body">
                <img src="<?php echo $image[0]; ?>" alt="" title="" class="alignnone size-full wp-image-<?php echo $attachment; ?>" />
              </div>

              <div class="modal-footer">
                  <a href="#" data-dismiss="modal" class="btn">Close</a>
              </div>
            </div>

    <?php } ?>

<?php } ?>