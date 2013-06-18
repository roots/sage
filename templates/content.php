
<?php
if ( !has_action( 'shoestrap_content_override' ) ) { ?>
  <article <?php post_class(); ?>>
    <header>
      <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
      <?php
      if ( !has_action( 'shoestrap_entry_meta_override' ) )
        get_template_part( 'templates/entry-meta' );
      else
        do_action( 'shoestrap_entry_meta_override' );
      ?>
    </header>
    <div class="entry-summary">
      <?php
      do_action( 'shoestrap_before_the_content' );
      if ( !has_action( 'shoestrap_do_the_excerpt' ) )
        the_excerpt();
      else
        do_action( 'shoestrap_do_the_excerpt' );

      do_action( 'shoestrap_after_the_content' )
      ?>
      <div class="clearfix"></div>
    </div>
    <?php if ( has_action( 'shoestrap_entry_footer' ) ) : ?>
    <footer>
      <?php do_action( 'shoestrap_entry_footer' ); ?>
    </footer>
    <?php endif; ?>
  </article>
<?php } else { do_action( 'shoestrap_content_override' ); } ?>
