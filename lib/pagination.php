<?php
/**
 * Numbered Pagination
 * @source awtheme/reactor
 */

function roots_numbered_pagination() {
  global $wp_query;

  $big = 999999999; // need an unlikely integer
  $count = 0;
  $base = str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) );
  $total = $wp_query->max_num_pages;
  $current = max( 1, get_query_var('paged') );

  $defaults = array(
    'base' => $base,
    'format' => '?page=%#%',
    'total' => $total,
    'current' => $current,
    'show_all' => false,
    'prev_next' => true,
    'prev_text' => __('&laquo; Previous', 'reactor'),
    'next_text' => __('Next &raquo;', 'reactor'),
    'end_size' => 2,
    'mid_size' => 3,
    'add_args' => false,
    'add_fragment' => ''
  );

  extract($defaults, EXTR_SKIP);

  $current  = (int) $current;
  $end_size = 0  < (int) $end_size ? (int) $end_size : 1; // Out of bounds?  Make it the default.
  $mid_size = 0 <= (int) $mid_size ? (int) $mid_size : 2;
  $add_args = is_array($add_args) ? $add_args : false;
  $r = '';
  $page_links = array();
  $n = 0;
  $dots = false;

  $output = "<ul class='pagination'>";

  if ( $prev_next && $current && 1 < $current ) :
    $link = str_replace('%_%', 2 == $current ? '' : $format, $base);
    $link = str_replace('%#%', $current - 1, $link);
    if ( $add_args )
      $link = add_query_arg( $add_args, $link );
    $link .= $add_fragment;
    $page_links[] = '<li><a class="prev page-numbers" href="' . esc_url( $link ) . '">' . $prev_text . '</a></li>';
  endif;
  for ( $n = 1; $n <= $total; $n++ ) :
    $n_display = number_format_i18n($n);
    if ( $n == $current ) :
      $page_links[] = "<li class='current'><a class='page-numbers current'>$n_display</a></li>";
      $dots = true;
    else :
      if ( $show_all || ( $n <= $end_size || ( $current && $n >= $current - $mid_size && $n <= $current + $mid_size ) || $n > $total - $end_size ) ) :
        $link = str_replace('%_%', 1 == $n ? '' : $format, $base);
        $link = str_replace('%#%', $n, $link);
        if ( $add_args )
          $link = add_query_arg( $add_args, $link );
        $link .= $add_fragment;
        $page_links[] = "<li><a class='page-numbers' href='" . esc_url( $link ) . "'>$n_display</a></li>";
        $dots = true;
      elseif ( $dots && !$show_all ) :
        $page_links[] = '<li><a class="page-numbers dots">' . __('&hellip;', 'reactor') . '</a></li>';
        $dots = false;
      endif;
    endif;
  endfor;
  if ( $prev_next && $current && ( $current < $total || -1 == $total ) ) :
    $link = str_replace('%_%', $format, $base);
    $link = str_replace('%#%', $current + 1, $link);
    if ( $add_args )
      $link = add_query_arg( $add_args, $link );
    $link .= $add_fragment;
    $page_links[] = '<li><a class="next page-numbers" href="' . esc_url( $link ) . '">' . $next_text . '</a></li>';
  endif;

  $output .= join("\n", $page_links);
  $output .= "</ul>";
  return $output;
}
