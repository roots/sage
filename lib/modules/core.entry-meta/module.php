<?php
if ( ! function_exists( 'shoestrap_entry_meta' ) ) :
/**
 * Prints HTML with meta information for current post: categories, tags, permalink, author, and date.
 *
 * Create your own shoestrap_entry_meta() to override in a child theme.
 *
 * @since Twenty Thirteen 1.0
 *
 * @return void
 */
function shoestrap_entry_meta() {
	echo '<div class="row row-meta">';

	$categories_list = get_the_category_list( __( ', ', 'shoestrap' ) );
	$tag_list = get_the_tag_list( '', __( ', ', 'shoestrap' ) );
	$elementscountplus = '';

	if ( is_sticky() && is_home() && ! is_paged() )
		$elementscountplus .= '+';
	if ( ! has_post_format( 'link' ) && 'post' == get_post_type() )
		$elementscountplus .= '+';
	if ( $categories_list )
		$elementscountplus .= '+';
	if ( $tag_list )
		$elementscountplus .= '+';
	if ( 'post' == get_post_type() )
		$elementscountplus .= '+';

    $col = 12;
	if ( strlen( $elementscountplus ) == 5 )
		$col = 2;
	elseif ( strlen( $elementscountplus ) == 4 )
		$col = 3;
	elseif ( strlen( $elementscountplus ) == 3 )
		$col = 4;
	elseif ( strlen( $elementscountplus ) == 2 )
		$col = 6;
	elseif ( strlen( $elementscountplus ) == 1 )
		$col = 12;

	$colclass = 'col-sm-' . $col;


	if ( is_sticky() && is_home() && ! is_paged() )
		echo '<span class="featured-post ' . $colclass . '"><i class="icon icon-flag"></i> ' . __( 'Sticky', 'shoestrap' ) . '</span>';

	if ( ! has_post_format( 'link' ) && 'post' == get_post_type() ) {
		$format_prefix = ( has_post_format( 'chat' ) || has_post_format( 'status' ) ) ? _x( '%1$s on %2$s', '1: post format name. 2: date', 'shoestrap' ): '%2$s';

		$date = sprintf( '<span class="date ' . $colclass . '"><i class="icon icon-calendar"></i> <a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a></span>',
			esc_url( get_permalink() ),
			esc_attr( sprintf( __( 'Permalink to %s', 'shoestrap' ), the_title_attribute( 'echo=0' ) ) ),
			esc_attr( get_the_date( 'c' ) ),
			esc_html( sprintf( $format_prefix, get_post_format_string( get_post_format() ), get_the_date() ) )
		);
		echo $date;
	}

	// Translators: used between list items, there is a space after the comma.
	if ( $categories_list )
		echo '<span class="categories-links ' . $colclass . '"><i class="icon icon-folder-open"></i> ' . $categories_list . '</span>';

	// Translators: used between list items, there is a space after the comma.
	
	if ( $tag_list )
		echo '<span class="tags-links ' . $colclass . '"><i class="icon icon-tags"></i> ' . $tag_list . '</span>';

	// Post author
	if ( 'post' == get_post_type() ) {
		printf( '<span class="author vcard ' . $colclass . '"><i class="icon icon-user"></i> <a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_attr( sprintf( __( 'View all posts by %s', 'shoestrap' ), get_the_author() ) ),
			get_the_author()
		);
	}

	echo '</div>';
}
endif;
