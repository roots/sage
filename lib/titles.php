<?php
/**
 * Page titles
 */
function shoestrap_title() {
	if ( is_home() ) {
		if ( get_option( 'page_for_posts', true ) )
			$title = get_the_title(get_option( 'page_for_posts', true ) );
		else
			$title = __( 'Latest Posts', 'shoestrap' );

	} elseif ( is_archive() ) {
		$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );

		if ( $term ) {
			$title = apply_filters( 'single_term_title', $term->name );
		} elseif ( is_post_type_archive() ) {
			$title = apply_filters( 'the_title', get_queried_object()->labels->name );
		} elseif ( is_day() ) {
			$title = sprintf(__( 'Daily Archives: %s', 'shoestrap' ), get_the_date() );
		} elseif ( is_month() ) {
			$title = sprintf(__( 'Monthly Archives: %s', 'shoestrap' ), get_the_date( 'F Y' ) );
		} elseif ( is_year() ) {
			$title = sprintf(__( 'Yearly Archives: %s', 'shoestrap' ), get_the_date( 'Y' ) );
		} elseif ( is_author() ) {
			$title = sprintf( __( 'Author Archives: %s', 'shoestrap' ), get_queried_object()->display_name );
		} else {
			$title = single_cat_title( '', false );
		}

	} elseif ( is_search() ) {
		$title = sprintf( __( 'Search Results for %s', 'shoestrap' ), get_search_query() );
	} elseif ( is_404() ) {
		$title = __( 'Not Found', 'shoestrap' );
	} else {
		$title = get_the_title();
	}

	return apply_filters( 'shoestrap_title', $title );
}

/**
 * The title secion.
 * Includes a <head> element and link.
 */
function shoestrap_title_section( $header = true, $element = 'h1', $link = false, $class = 'entry-title' ) {
	$content  = $header ? '<header>' : '';
	$content .= '<title>' . get_the_title() . '</title>';
	$content .= '<' . $element . ' class="' . $class . '">';
	$content .= $link ? '<a href="' . get_permalink() . '">' : '';
	$content .= is_singular() ? shoestrap_title() : apply_filters( 'shoestrap_title', get_the_title() );
	$content .= $link ? '</a>' : '';
	$content .= '</' . $element . '>';
	$content .= $header ? '</header>' : '';

	echo apply_filters( 'shoestrap_title_section', $content );
}
