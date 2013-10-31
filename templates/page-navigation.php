      
      <?php
				$pagelist = get_pages('sort_column=menu_order&sort_order=asc&post_type=product');
				$pages = array();
				foreach ($pagelist as $page) {
				   $pages[] += $page->ID;
				}
				
				$current = array_search(get_the_ID(), $pages);
				$prevID = $pages[$current-1];
				$nextID = $pages[$current+1];
				?>
				
				<div class="navigation">
				<?php if (!empty($prevID)) { ?>
				<div class="alignleft">
				<a href="<?php echo get_permalink($prevID); ?>"
				  title="<?php echo get_the_title($prevID); ?>">Previous</a>
				</div>
				<?php }
				if (!empty($nextID)) { ?>
				<div class="alignright">
				<a href="<?php echo get_permalink($nextID); ?>" 
				 title="<?php echo get_the_title($nextID); ?>">Next</a>
				</div>
				<?php } ?>
				</div><!-- .navigation -->