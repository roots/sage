      <?php if( get_field('specifications')) { ?>
      <div class="well specifications">
      <?php $posts = get_field('specifications');
      if( $posts ): ?>
        <h4>Specifications</h4>
      	<ul class="row">
      	<?php foreach( $posts as $post): // variable must be called $post (IMPORTANT) ?>
      		<?php setup_postdata($post); ?>
      	     <li class="col-xs-4 col-sm-3 col-md-2 col-lg-2">
      	    	<a title="<?php echo get_the_title($post->ID); ?>" class="thumbnail thumbnail-<?php echo get_the_id(); ?>" id="archive-<?php echo get_the_id(); ?>" href="<?php echo get_permalink($post->ID);?>">
				          <div class="entry-summary">
				              <?php if ( has_post_thumbnail( get_the_id($post->ID) ) ) { the_post_thumbnail( 'mini' ); } else { ?><img src="http://placehold.it/180x210"/><?php } ?>
				          </div>
								</a>
      	    	<a title="<?php echo get_the_title($post->ID); ?>" class="related-<?php echo get_the_id(); ?>" id="archive-<?php echo get_the_id(); ?>" href="<?php echo get_permalink($post->ID);?>"><?php the_title(); ?></a>
      	    	
      	    </li>
      	<?php endforeach; ?>
      	</ul>
      	<?php wp_reset_postdata(); // IMPORTANT - reset the $post object so the rest of the page works correctly ?>
      <?php endif; ?>
      </div><?php // end .well ?>

      <?php } ?>