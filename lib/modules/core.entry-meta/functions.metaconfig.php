<?php
/*
* TODO: Make code more effective, escpecially the way meta elements are unset.
*/


add_action( 'init', function() { add_action( 'shoestrap_entry_meta_override','shoestrap_meta_custom_render' ); }, 20 );


if ( !function_exists( 'shoestrap_meta_custom_render' ) ) :
/**
 * Shortcode based output of meta information for current post: categories, tags, permalink, author, and date.
 * Create your own shoestrap_meta_custom_render() to override in a child theme.
 */
function shoestrap_meta_custom_render() {
  // get config and data
  $metaconfig = shoestrap_getVariable( 'shoestrap_entry_meta_config' );

  // early return if no need to proceed
  if ( !is_array( $metaconfig ) || !isset( $metaconfig['enabled'] ) || empty( $metaconfig ) ) :
    return;
  else :
    $metaelements = $metaconfig['enabled'];
    // remove the weird placebo element if it exists
    unset( $metaelements['placebo'] );
  endif;

  // check if anything is left after we remove the weird placebo element
  if ( empty( $metaelements ) ) :
    return;
  endif;

  $meta_html = $metaelements;

  $categories_list    = get_the_category_list( __( ', ', 'shoestrap' ) );
  $tag_list           = get_the_tag_list( '', __( ', ', 'shoestrap' ) );
  $elementscountplus  = '';

  if ( isset( $metaelements['sticky'] ) && is_sticky() && is_home() && ! is_paged() ) :
    $elementscountplus .= '+';
  endif;

  if ( ! has_post_format( 'link' ) && 'post' == get_post_type() ) :
    $elementscountplus .= '+';
  endif;

  if ( isset( $metaelements['category'] ) && $categories_list ) :
    $elementscountplus .= '+';
  endif;

  if ( isset( $metaelements['tags'] ) && $tag_list ) :
    $elementscountplus .= '+';
  endif;

  if ( isset( $metaelements['author'] ) && ( 'post' == get_post_type() ) ) :
    $elementscountplus .= '+';
  endif;

  // Distribute meta elements over 12 columns
  $col = 12;
  if ( strlen( $elementscountplus ) == 5 ) :
    $col = 2;
  elseif ( strlen( $elementscountplus ) == 4 ) :
    $col = 3;
  elseif ( strlen( $elementscountplus ) == 3 ) :
    $col = 4;
  elseif ( strlen( $elementscountplus ) == 2 ) :
    $col = 6;
  elseif ( strlen( $elementscountplus ) == 1 ) :
    $col = 12;
  endif;

  $colclass = 'col-md-' . $col;

  // output sticky element
  if ( isset( $metaelements['sticky'] ) && is_sticky() && is_home() && ! is_paged() ) :
    $meta_html['sticky'] = '<span class="featured-post ' . $colclass . '"><i class="el-icon-flag icon"></i> ' . __( 'Sticky', 'shoestrap' ) . '</span>';
  endif;

  // unset sticky element if set but not being used
  if ( isset( $metaelements['sticky'] ) && !( is_sticky() && is_home() && ! is_paged()) ) :
    unset( $meta_html['sticky'] );
  endif;

  // output date element
  if ( !has_post_format( 'link' ) && 'post' == get_post_type() ) :
    $format_prefix = ( has_post_format( 'chat' ) || has_post_format( 'status' ) ) ? _x( '%1$s on %2$s', '1: post format name. 2: date', 'shoestrap' ): '%2$s';

    if ( isset( $metaelements['date'] ) ) :
      $meta_html['date'] = sprintf( '<span class="date ' . $colclass . '"><i class="el-icon-time icon"></i> <a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a></span>',
        esc_url( get_permalink() ),
        esc_attr( sprintf( __( 'Permalink to %s', 'shoestrap' ), the_title_attribute( 'echo=0' ) ) ),
        esc_attr( get_the_date( 'c' ) ),
        esc_html( sprintf( $format_prefix, get_post_format_string( get_post_format() ), get_the_date() ) )
      );
    endif;
  endif;

  // output category element
  if ( isset( $metaelements['category'] ) && $categories_list ) :
    $meta_html['category'] = '<span class="categories-links ' . $colclass . '"><i class="el-icon-folder-open icon"></i> ' . $categories_list . '</span>';
  endif;
  
  // unset category element if set but not being used
  if ( isset( $metaelements['category'] ) && !$categories_list ) :
    unset( $meta_html['category'] );
  endif;

  // output tag element
  if ( isset( $metaelements['tags'] ) && $tag_list ) :
    $meta_html['tags'] = '<span class="tags-links ' . $colclass . '"><i class="el-icon-tags icon"></i> ' . $tag_list . '</span>';
  endif;

  // unset tag element if set but not being used
  if ( isset( $metaelements['tags'] ) && !$tag_list ) :
    unset( $meta_html['tags'] );
  endif;

  // output author element
  if ( isset( $metaelements['author'] ) && ('post' == get_post_type()) ) :
    $meta_html['author'] = sprintf( '<span class="author vcard ' . $colclass . '"><i class="el-icon-user icon"></i> <a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
      esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
      esc_attr( sprintf( __( 'View all posts by %s', 'shoestrap' ), get_the_author() ) ),
      get_the_author()
    );
  endif;

  // unset author element if set but not being used
  if ( isset( $metaelements['author'] ) && ( 'post' != get_post_type() ) ) :
    unset( $meta_html['author'] );
  endif;

  if ( !empty( $metaelements ) ) :
    echo '<div class="row row-meta">';
    foreach ( $meta_html as $el => $html ) :
      echo $html;
    endforeach;
    echo '</div>';
  endif;
}
endif;
