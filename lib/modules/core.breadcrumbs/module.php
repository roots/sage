<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( !function_exists( 'shoestrap_breadcrumbs' ) ) :
function shoestrap_breadcrumbs() {
  // No breadcrumbs on the front page
  if ( is_front_page() || shoestrap_getVariable('breadcrumbs') == 0 )
    return;

  $prepend = '';
  $delimiter = '';
  $wrap_before = '<div class="breadTrail '.shoestrap_container_class().'"><ul class="breadcrumb">';
  $wrap_after = '</ul></div>';
  $before = '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">';
  $after = '</li>';
  $home = '<i class="el-icon-home"></i>';
// 'current'   => '<li class="active">%s</li>',
// 'link'      => '<a href="%s" itemprop="url title">%s</a>'
  shoestrap_breadcrumb( $prepend, $delimiter, $wrap_before, $wrap_after, $before, $after, $home );

}
endif;
add_action( 'shoestrap_breadcrumbs', 'shoestrap_breadcrumbs' );

/*
 * The breadcrumbs function.
 * Inspired by the breadcrumbs used in WooCommerce.
 */
function shoestrap_breadcrumb( $prepend = '', $delimiter = '', $wrap_before = '', $wrap_after = '', $before = '', $after = '', $home = '' ) {
  global $post, $wp_query;

  $term     = get_query_var( 'term' );
  $taxonomy = get_query_var( 'taxonomy' );

  if ( ( !is_home() && !is_front_page() ) || is_paged() ) {
    echo $wrap_before;

    // Add the home link
    echo ( !empty( $home ) ) ? $before . '<a class="home" itemprop="url title" href="' . home_url() . '">' . $home . '</a>' . $after . $delimiter : '';

    if ( class_exists( 'bbPress' ) && ( bbp_is_topic_archive() || bbp_is_search() || bbp_is_forum_archive() || bbp_is_single_view() || bbp_is_single_forum() || bbp_is_single_topic() || bbp_is_single_reply() ) ) {

      $ancestors = (array) get_post_ancestors( get_the_ID() );

      // Ancestors exist
      if ( !empty( $ancestors ) ) {
        // Loop through parents
        foreach ( (array) $ancestors as $parent_id ) {
          // Parents
          $parent = get_post( $parent_id );

          // Skip parent if empty or error
          if ( empty( $parent ) || is_wp_error( $parent ) ) continue;

          // Switch through post_type to ensure correct filters are applied
          switch ( $parent->post_type ) {

            // Forum
            case bbp_get_forum_post_type() :
              echo $before . '<a href="' . esc_url( bbp_get_forum_permalink( $parent->ID ) ) . '">' . bbp_get_forum_title( $parent->ID ) . '</a>' . $after;
            break;

            // Topic
            case bbp_get_topic_post_type() :
              echo $before . '<a href="' . esc_url( bbp_get_topic_permalink( $parent->ID ) ) . '">' . bbp_get_topic_title( $parent->ID ) . '</a>' . $after;
            break;

            // Reply
            case bbp_get_reply_post_type() :
              echo $before . '<a href="' . esc_url( bbp_get_reply_permalink( $parent->ID ) ) . '">' . bbp_get_reply_title( $parent->ID ) . '</a>' . $after;
            break;
          }
        }
      }

      // Topic archive
      if ( bbp_is_topic_archive() )
        echo $before . bbp_get_topic_archive_title() . $after;

      // Search page
      if ( bbp_is_search() )
        echo $before . bbp_get_search_title() . $after;

      // Forum archive
      if ( bbp_is_forum_archive() )
        echo $before . bbp_get_forum_archive_title() . $after;

      // View
      elseif ( bbp_is_single_view() )
        echo $before . bbp_get_view_title() . $after;

      if ( bbp_is_single_forum() )
        echo $before . bbp_get_forum_title() . $after;

      if ( bbp_is_single_topic() )
        echo $before . bbp_get_topic_title() . $after;

      if ( bbp_is_single_reply() )
        echo $before . bbp_get_reply_title() . $after;
    } else {

      // Categories
      if ( is_category() ) {
        $cat_obj = $wp_query->get_queried_object();
        $this_category = get_category( $cat_obj->term_id );

        // Add category parents
        if ( $this_category->parent != 0 )
          echo $before . get_category_parents( get_category( $this_category->parent ), TRUE, $delimiter ) . $after;

        echo $before . single_cat_title( '', false ) . $after;

      // All other taxonomies
      } elseif ( is_tax() ) {
        echo $prepend;

        $current_term      = get_term_by( 'slug', $term, $taxonomy );
        $current_term_name = $current_term->name;

        if ( is_taxonomy_hierarchical( $current_term_name ) ) {
          $ancestors = array_reverse( get_ancestors( $current_term->term_id, $taxonomy ) );

          foreach ( $ancestors as $ancestor ) {
            $ancestor = get_term( $ancestor, $taxonomy );
            echo $before .  '<a href="' . get_term_link( $ancestor->slug, $taxonomy ) . '" itemprop="url title">' . esc_html( $ancestor->name ) . '</a>' . $after . $delimiter;
          }
        }

        echo $before . esc_html( $current_term->name ) . $after;

      // Search results
      } elseif ( is_search() ) {
        echo $before . __( 'Search results for &ldquo;', 'shoestrap' ) . get_search_query() . '&rdquo;' . $after;

      // Days
      } elseif ( is_day() ) {
        echo $before . '<a href="' . get_year_link( get_the_time( 'Y' ) ) . '" itemprop="url title">' . get_the_time( 'Y' ) . '</a>' . $after . $delimiter;
        echo $before . '<a href="' . get_month_link( get_the_time( 'Y' ),get_the_time( 'm' ) ) . '">' . get_the_time( 'F' ) . '</a>' . $after . $delimiter;
        echo $before . get_the_time( 'd' ) . $after;

      // Months
      } elseif ( is_month() ) {
        echo $before . '<a href="' . get_year_link( get_the_time( 'Y' ) ) . '" itemprop="url title">' . get_the_time( 'Y' ) . '</a>' . $after . $delimiter;
        echo $before . get_the_time( 'F' ) . $after;
      
      // Years
      } elseif ( is_year() ) {
        echo $before . get_the_time( 'Y' ) . $after;

      // Single
      } elseif ( is_single() && !is_attachment() ) {

        // get the taxonomy names of this object
        $taxonomy_names = get_object_taxonomies( get_post_type() );

        // Detect any hierarchical taxonomies that might exist on this post type
        $hierarchical = false;
        foreach ( $taxonomy_names as $taxonomy_name ) {
          if ( !$hierarchical ) {
            $hierarchical = ( is_taxonomy_hierarchical( $taxonomy_name ) ) ? true : $hierarchical;
            $tn = $taxonomy_name;
          }
        }

        echo $prepend;

        $args = ( is_taxonomy_hierarchical( $tn ) ) ? array( 'orderby' => 'parent', 'order' => 'DESC' ) : '';

        if ( $terms = wp_get_post_terms( $post->ID, $tn, $args ) ) {
          $main_term = $terms[0];

          if ( is_taxonomy_hierarchical( $tn ) ) {
            $ancestors = get_ancestors( $main_term->term_id, $tn );
            $ancestors = array_reverse( $ancestors );

            foreach ( $ancestors as $ancestor ) {
              $ancestor = get_term( $ancestor, $tn );
              echo $before . '<a href="' . get_term_link( $ancestor->slug, $tn ) . '" itemprop="url title">' . $ancestor->name . '</a>' . $after . $delimiter;
            }
          }
          echo $before . '<a href="' . get_term_link( $main_term->slug, $tn ) . '" itemprop="url title">' . $main_term->name . '</a>' . $after . $delimiter;
        }

        echo $before . get_the_title() . $after;

      // 404 breadcrumbs
      } elseif ( is_404() ) {
        echo $before . __( 'Error 404', 'shoestrap' ) . $after;

      // General
      } elseif ( !is_single() && !is_page() && get_post_type() != 'post' ) {
        $post_type = get_post_type_object( get_post_type() );

        if ( $post_type ) {
          echo $before . $post_type->labels->singular_name . $after;
        }

      // Attachments
      } elseif ( is_attachment() ) {
        $parent = get_post( $post->post_parent );
        $cat = get_the_category( $parent->ID );
        $cat = $cat[0];
        echo get_category_parents( $cat, true, '' . $delimiter );
        echo $before . '<a href="' . get_permalink( $parent ) . '" itemprop="url title">' . $parent->post_title . '</a>' . $after . $delimiter;
        echo $before . get_the_title() . $after;

      // Page without any parents
      } elseif ( is_page() && !$post->post_parent ) {
        echo $before . get_the_title() . $after;

      // Page with parents
      } elseif ( is_page() && $post->post_parent ) {
        $parent_id   = $post->post_parent;
        $breadcrumbs = array();

        while ( $parent_id ) {
          $page = get_page( $parent_id );
          $breadcrumbs[] = $before . '<a href="' . get_permalink( $page->ID ) . '" itemprop="url title">' . get_the_title( $page->ID ) . '</a>' . $after;
          $parent_id  = $page->post_parent;
        }

        $breadcrumbs = array_reverse( $breadcrumbs );

        foreach ( $breadcrumbs as $crumb ) {
          echo $crumb . '' . $delimiter;
        }

        echo $before . get_the_title() . $after;

      // Authors
      } elseif ( is_author() ) {
        $userdata = get_userdata( $author );
        echo $before . __( 'Author:', 'shoestrap' ) . ' ' . $userdata->display_name . $after;
      }
    }

    if ( get_query_var( 'paged' ) )
      echo ' ( ' . __( 'Page', 'shoestrap' ) . ' ' . get_query_var( 'paged' ) . ' )';

    echo $wrap_after;
  }
}