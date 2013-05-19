<article <?php post_class(); ?>>
  <header>
    <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
    <?php get_template_part('templates/entry-meta'); ?>
  </header>
  <div class="entry-summary">
    <?php
    do_action( 'shoestrap_pre_entry_summary' );
    if ( !has_action( 'shoestrap_do_the_excerpt' ) )
      the_excerpt();
    else
      do_action( 'shoestrap_do_the_excerpt' );

    do_action( 'shoestrap_post_entry_summary' )
    ?>
  </div>
  <footer>
    <?php
    if ( !has_action( 'shoestrap_entry_footer' ) )
      the_tags('<ul class="entry-tags"><li>','</li><li>','</li></ul>');
    else
      do_action( 'shoestrap_entry_footer' );
    ?>
  </footer>
</article>
