<?php while (have_posts()) : the_post(); ?>
  
  <article <?php post_class(); ?>>
    <header>
      <?php get_template_part('templates/page', 'header'); ?>
    </header>
    <div class="entry-content">
      <?php the_content(); ?>
    </div>
    <footer>
      <?php wp_link_pages(array('before' => '<nav class="page-nav"><p>' . __('Pages:', 'roots'), 'after' => '</p></nav>')); ?>
      <?php // the_tags('<ul class="entry-tags"><li>','</li><li>','</li></ul>'); ?>
      
      <?php
      //for use in the loop, list 5 post titles related to first tag on current post
      $tags = wp_get_post_tags($post->ID);
      if ($tags) {
      echo '<div class="well">';
      echo '<h4>Related Resources</h4>';
      $first_tag = $tags[0]->term_id;
      $args=array(
      'tag__in'           => array($first_tag),
      'post_type'         => array('resource'),
      'post__not_in'      => array($post->ID),
      'posts_per_page'    => 5,
      'orderby'           => 'rand',
      'caller_get_posts'  =>1
      );
      $rel_posts_query = new WP_Query($args);
      if( $rel_posts_query->have_posts() ) {
      while ($rel_posts_query->have_posts()) : $rel_posts_query->the_post(); ?>
      <a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a>
      
      <?php
      endwhile;
      }
      wp_reset_query();
      }
      echo '</div>';
      ?>
      
    </footer>
  </article>
<?php endwhile; ?>