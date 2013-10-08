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
          <?php if(get_row_layout() == "tab_normal"): ?>
              <?php if($count == 1 && has_post_thumbnail()) { ?>
           <div class="pull-right wrap-featured-image"><?php the_post_thumbnail('medium', array('class' => 'img-thumbnail')); ?></div>
            <?php } ?>
            	<?php the_sub_field("tab_content"); ?>
          <?php endif; ?>
          </div>
          
        <?php $count++; ?>
        <?php endwhile; ?>
        
        </div><!--/.tab-content -->
      </div><!--/.tabbable -->