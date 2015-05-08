<article <?php post_class(); ?>>
  <header>
    <?php the_post_thumbnail(); ?>
    <div class="overlay"></div>
    <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
  </header>
  <div class="entry-summary">
    <?php the_excerpt(); ?>
  </div>
</article>
