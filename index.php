<?php

if ( !has_action( 'shoestrap_page_header_override' ) )
  get_template_part('templates/page', 'header');
else
  do_action( 'shoestrap_page_header_override' );

do_action( 'shoestrap_index_begin' );

if ( !have_posts() ) :
  echo '<div class="alert alert-warning">';
  _e('Sorry, no results were found.', 'shoestrap');
  echo '</div>';
  get_search_form();
endif;

if ( !has_action( 'shoestrap_override_index_loop' ) ) :
  while (have_posts()) : the_post();
    do_action( 'shoestrap_in_loop_start_action' );
    do_action( 'shoestrap_before_the_content' );

    if ( !has_action( 'shoestrap_content_override' ) )
      get_template_part('templates/content', get_post_format());
    else
      do_action( 'shoestrap_content_override' );

    do_action( 'shoestrap_after_the_content' );
  endwhile;
else :
  do_action( 'shoestrap_override_index_loop' );
endif;

do_action( 'shoestrap_index_end' );

$pagination = shoestrap_getVariable( 'pagination' );
if ( $pagination == 'pager' ) :
  if ($wp_query->max_num_pages > 1) : ?>
    <nav class="post-nav">
      <ul class="pager">
        <li class="previous"><?php next_posts_link(__('&larr; Older posts', 'shoestrap')); ?></li>
        <li class="next"><?php previous_posts_link(__('Newer posts &rarr;', 'shoestrap')); ?></li>
      </ul>
    </nav>
  <?php
  endif;
else :
  global $wp_query;
  if ( $wp_query->max_num_pages <= 1 )
    return;
?>
  <nav class="pagination">
  <?php
    echo shoestrap_paginate_links( apply_filters( 'pagination_args', array(
      'base'      => str_replace( 999999999, '%#%', get_pagenum_link( 999999999 ) ),
      'format'    => '',
      'current'     => max( 1, get_query_var('paged') ),
      'total'     => $wp_query->max_num_pages,
      'prev_text'   => '<i class="el-icon-chevron-left"></i>',
      'next_text'   => '<i class="el-icon-chevron-right"></i>',
      'type'      => 'list',
      'end_size'    => 3,
      'mid_size'    => 3
    ) ) );
  ?>
  </nav>
<?php
endif;