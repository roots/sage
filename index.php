<?php

if ( !has_action( 'shoestrap_page_header_override' ) )
  get_template_part('templates/page', 'header');
else
  do_action( 'shoestrap_page_header_override' );

do_action( 'shoestrap_index_begin' );

if ( !have_posts() ) :
  echo '<div class="alert alert-warning">';
  _e('Sorry, no results were found.', 'roots');
  echo '</div>';
  get_search_form();
endif;

while (have_posts()) : the_post();
  do_action( 'shoestrap_in_loop_start_action' );
  do_action( 'shoestrap_before_the_content' );

  if ( !has_action( 'shoestrap_content_override' ) )
    get_template_part('templates/content', get_post_format());
  else
    do_action( 'shoestrap_content_override' );

  do_action( 'shoestrap_after_the_content' );
endwhile;

do_action( 'shoestrap_index_end' );

if ($wp_query->max_num_pages > 1) : ?>
  <nav class="post-nav">
    <ul class="pager">
      <li class="previous"><?php next_posts_link(__('&larr; Older posts', 'roots')); ?></li>
      <li class="next"><?php previous_posts_link(__('Newer posts &rarr;', 'roots')); ?></li>
    </ul>
  </nav>
<?php
endif;
