        <div class="media" <?php post_class(); ?>>
        
          <div class="pull-left"><?php get_template_part('templates/content', 'calendar-icon'); ?></div>
        
          <?php if ( has_post_thumbnail()) : ?>
           <a class="pull-right" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" >
           <?php the_post_thumbnail('thumbnail', array('class' => 'media-object img-thumbnail')); ?>
           </a>
         <?php endif; ?>
          <div class="media-body">
            <a href="<?php the_permalink(); ?>"><h4 class="media-heading"><?php the_title(); ?></h4></a>
              <?php the_excerpt(); ?>
          </div>
        </div>
