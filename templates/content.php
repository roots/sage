<article <?php post_class(); ?>>
  <header>
      <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
      <a href="<?php the_permalink(); ?>">
        <div class="overlay"></div>
      </a>
      <?php the_post_thumbnail(); ?>
  </header>
  <div class="entry-summary">
    <?php the_excerpt(); ?>
  </div>
</article>
