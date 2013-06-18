<?php

while (have_posts()) : the_post(); ?>
  <article <?php post_class(); ?>>
    <header>
      <h1 class="entry-title"><?php the_title(); ?></h1>
      <?php
      if ( !has_action( 'shoestrap_entry_meta_override' ) )
        get_template_part('templates/entry-meta');
      else
        do_action( 'shoestrap_entry_meta_override' )
      ?>
    </header>
    <div class="entry-content">
      <?php the_content(); ?>
      <div class="clearfix"></div>
    </div>
    <footer>
      <?php wp_link_pages(array('before' => '<nav class="page-nav"><p>' . __('Pages:', 'roots'), 'after' => '</p></nav>')); ?>
    </footer>
    <?php
    if ( post_type_supports( 'post', 'comments' ) ):
      do_action( 'shoestrap_pre_comments' );
      if ( !has_action( 'shoestrap_comments_override' ) )
        comments_template('/templates/comments.php');
      else
        do_action( 'shoestrap_comments_override' );
      do_action( 'shoestrap_after_comments' );
    endif;
    ?>
  </article>
<?php endwhile;
