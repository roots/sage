<?php


if ( !function_exists( 'shoestrap_meta_custom_render' ) ) :
/**
 * Shortcode based output of meta information for current post: categories, tags, permalink, author, and date.
 * Create your own shoestrap_meta_custom_render() to override in a child theme.
 */
function shoestrap_meta_custom_render() {
	// get config and data
	$metas = shoestrap_getVariable( 'shoestrap_entry_meta_config' );

	$categories_list = get_the_category_list( __( ', ', 'shoestrap' ) );
	$tag_list        = get_the_tag_list( '', __( ', ', 'shoestrap' ) );

	$i = 0;
	if ( is_array( $metas ) ) {
		foreach ( $metas as $meta => $value ) {
			if ( $meta == 'sticky'   && is_sticky() )      $i++;
			if ( $meta == 'date'     && !empty( $value ) ) $i++;
			if ( $meta == 'category' && !empty( $value ) ) $i++;
			if ( $meta == 'tags'     && !empty( $value ) ) $i++;
			if ( $meta == 'author'   && !empty( $value ) ) $i++;
		}
	}

	$col = ( $i >= 2 ) ? round( ( 12 / ( $i ) ), 0) : 12;
	$colclass = 'col-md-' . $col;

	$content = '';
	if ( is_array( $metas ) ) {
		foreach ( $metas as $meta => $value ) {
			// output sticky element
			if ( $meta == 'sticky' && is_sticky() ) {
				$content .= '<span class="featured-post ' . $colclass . '"><i class="el-icon-flag icon"></i> ' . __( 'Sticky', 'shoestrap' ) . '</span>';
			}

			// output date element
			if ( $meta == 'date' && !empty( $value ) ) {
				if ( !has_post_format( 'link' ) ) {
					$format_prefix = ( has_post_format( 'chat' ) || has_post_format( 'status' ) ) ? _x( '%1$s on %2$s', '1: post format name. 2: date', 'shoestrap' ): '%2$s';

					$content .= sprintf( '<span class="date ' . $colclass . '"><i class="el-icon-time icon"></i> <a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a></span>',
						esc_url( get_permalink() ),
						esc_attr( sprintf( __( 'Permalink to %s', 'shoestrap' ), the_title_attribute( 'echo=0' ) ) ),
						esc_attr( get_the_date( 'c' ) ),
						esc_html( sprintf( $format_prefix, get_post_format_string( get_post_format() ), get_the_date() ) )
					);
				}
			}

			// output category element
			if ( $meta == 'category' && !empty( $value ) ) {
				if ( $categories_list )
					$content .= '<span class="categories-links ' . $colclass . '"><i class="el-icon-folder-open icon"></i> ' . $categories_list . '</span>';
			}

			// output tag element
			if ( $meta == 'tags' && !empty( $value ) ) {
				if ( $tag_list )
					$content .= '<span class="tags-links ' . $colclass . '"><i class="el-icon-tags icon"></i> ' . $tag_list . '</span>';
			}

			// output author element
			if ( $meta == 'author' && !empty( $value ) ) {
				$content .= sprintf( '<span class="author vcard ' . $colclass . '"><i class="el-icon-user icon"></i> <a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
					esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
					esc_attr( sprintf( __( 'View all posts by %s', 'shoestrap' ), get_the_author() ) ),
					get_the_author()
				);
			}
		}
	}

	if ( !empty( $content ) )
		echo '<div class="row row-meta">' . $content . '</div>';
}
endif;
add_action( 'shoestrap_entry_meta_override','shoestrap_meta_custom_render' );