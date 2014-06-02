<?php


if ( ! class_exists( 'Shoestrap_Blog' ) ) {

	/**
	* The "Blog" module
	*/
	class Shoestrap_Blog {

		function __construct() {

			global $ss_settings;

			if ( ! class_exists( 'BuddyPress' ) || ( class_exists( 'BuddyPress' ) && ! shoestrap_is_bp() ) ) {
				add_action( 'shoestrap_entry_meta', array( $this, 'meta_custom_render' ) );
			}
			add_filter( 'excerpt_more', array( $this, 'excerpt_more' ) );
			add_action( 'wp', array( $this, 'remove_featured_image_per_post_type' ) );

			// Add featured images
			if ( ! is_singular() ) {
				add_action( 'shoestrap_entry_meta', array( $this, 'featured_image' ) );
			}

			// Chamnge the excerpt length
			if ( isset( $ss_settings['post_excerpt_length'] ) ) {
				add_filter( 'excerpt_length', array( $this, 'excerpt_length' ) );
			}

			// Show full content instead of excerpt
			if ( isset( $ss_settings['blog_post_mode'] ) && 'full' == $ss_settings['blog_post_mode'] ) {
				add_filter( 'shoestrap_do_the_excerpt', 'get_the_content' );
				add_filter( 'shoestrap_do_the_excerpt', 'do_shortcode', 99 );
				add_action( 'shoestrap_entry_footer', array( $this, 'archives_full_footer' ) );
			}

			// Hide post meta data in footer of single posts
			if ( isset( $ss_settings['single_meta'] ) && $ss_settings['single_meta'] == 0 ) {
				add_filter( 'shoestrap_the_tags', '__return_null' );
				add_filter( 'shoestrap_the_cats', '__return_null' );
			}
		}

		/**
		 * Footer for full-content posts.
		 * Used on archives when 'blog_post_mode' == full
		 */
		function archives_full_footer() { ?>
			<footer style="margin-top: 2em;">
				<i class="el-icon-tag"></i> <?php _e( 'Categories: ', 'shoestrap' ); ?>
				<span class="label label-tag">
					<?php echo get_the_category_list( '</span> ' . '<span class="label label-tag">' ); ?>
				</span>

				<?php echo get_the_tag_list( '<i class="el-icon-tags"></i> ' . __( 'Tags: ', 'shoestrap' ) . '<span class="label label-tag">', '</span> ' . '<span class="label label-tag">', '</span>' ); ?>

				<?php wp_link_pages( array(
					'before' => '<nav class="page-nav"><p>' . __( 'Pages:', 'shoestrap' ),
					'after'  => '</p></nav>'
				) ); ?>
			</footer>
			<?php
		}

		/**
		 * Output of meta information for current post: categories, tags, permalink, author, and date.
		 */
		function meta_custom_render() {
			global $ss_framework, $ss_settings, $post;

			// get config and data
			$metas = $ss_settings['shoestrap_entry_meta_config'];
			$date_format = $ss_settings['date_meta_format'];

			$categories_list = get_the_category_list( __( ', ', 'shoestrap' ) );
			$tag_list        = get_the_tag_list( '', __( ', ', 'shoestrap' ) );

			$i = 0;
			if ( is_array( $metas ) ) {
				foreach ( $metas as $meta => $value ) {
					if ( $meta == 'sticky' ) {
						if ( ! empty( $value ) && is_sticky() ) {
							$i++;
						}
					} elseif ( $meta == 'date' ) {
						if ( ! empty( $value ) ) {
							$i++;
						}
					} elseif ( $meta == 'category' ) {
						if ( ! empty( $value ) && has_category() ) {
							$i++;
						}
					} elseif ( $meta == 'tags' ) {
						if ( ! empty( $value ) && has_tag() ) {
							$i++;
						}
					} elseif ( $meta == 'author' ) {
						if ( ! empty( $value ) ) {
							$i++;
						}
					} elseif ( $meta == 'comment-count' && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
						if ( ! empty( $value ) ) {
							$i++;
						}
					}  elseif ( $meta == 'post-format' ) {
						if ( ! empty( $value ) ) {
							$i++;
						}
					}
				}
			}

			$col = ( $i >= 2 ) ? round( ( 12 / ( $i ) ), 0) : 12;

			$content = '';
			if ( is_array( $metas ) ) {
				foreach ( $metas as $meta => $value ) {
					// output sticky element
					if ( $meta == 'sticky' && ! empty( $value ) && is_sticky() ) {
						$content .= $ss_framework->open_col( 'span', array( 'medium' => $col ), null, 'featured-post' ) . '<i class="el-icon-flag icon"></i> ' . __( 'Sticky', 'shoestrap' ) . $ss_framework->close_col( 'span' );
					}

					// output post format element
					if ( $meta == 'post-format' && ! empty( $value ) ) {
						if ( get_post_format( $post->ID ) === 'gallery' ) {
						  $content .= $ss_framework->open_col( 'span', array( 'medium' => $col ), null, 'post-format' ) . '<i class="el-icon-picture"></i> <a href="' . esc_url( get_post_format_link( 'gallery' ) ) . '">' . __('Gallery','shoestrap') . '</a>' . $ss_framework->close_col( 'span' );
						}
						if ( get_post_format( $post->ID ) === 'aside' ) {
						  $content .= $ss_framework->open_col( 'span', array( 'medium' => $col ), null, 'post-format' ) . '<i class="el-icon-chevron-right"></i> <a href="' . esc_url( get_post_format_link( 'aside' ) ) . '">' . __('Aside','shoestrap') . '</a>' . $ss_framework->close_col( 'span' );
						}
						if ( get_post_format( $post->ID ) === 'link' ) {
						  $content .= $ss_framework->open_col( 'span', array( 'medium' => $col ), null, 'post-format' ) . '<i class="el-icon-link"></i> <a href="' . esc_url( get_post_format_link( 'link' ) ) . '">' . __('Link','shoestrap') . '</a>' . $ss_framework->close_col( 'span' );
						}
						if ( get_post_format( $post->ID ) === 'image' ) {
						  $content .= $ss_framework->open_col( 'span', array( 'medium' => $col ), null, 'post-format' ) . '<i class="el-icon-picture"></i> <a href="' . esc_url( get_post_format_link( 'image' ) ) . '">' . __('Image','shoestrap') . '</a>' . $ss_framework->close_col( 'span' );
						}
						if ( get_post_format( $post->ID ) === 'quote' ) {
						  $content .= $ss_framework->open_col( 'span', array( 'medium' => $col ), null, 'post-format' ) . '<i class="el-icon-quotes-alt"></i> <a href="' . esc_url( get_post_format_link( 'quote' ) ) . '">' . __('Quote','shoestrap') . '</a>' . $ss_framework->close_col( 'span' );
						}
						if ( get_post_format( $post->ID ) === 'status' ) {
						  $content .= $ss_framework->open_col( 'span', array( 'medium' => $col ), null, 'post-format' ) . '<i class="el-icon-comment"></i> <a href="' . esc_url( get_post_format_link( 'status' ) ) . '">' . __('Status','shoestrap') . '</a>' . $ss_framework->close_col( 'span' );
						}
						if ( get_post_format( $post->ID ) === 'video' ) {
						  $content .= $ss_framework->open_col( 'span', array( 'medium' => $col ), null, 'post-format' ) . '<i class="el-icon-video"></i> <a href="' . esc_url( get_post_format_link( 'video' ) ) . '">' . __('Video','shoestrap') . '</a>' . $ss_framework->close_col( 'span' );
						}
						if ( get_post_format( $post->ID ) === 'audio' ) {
						  $content .= $ss_framework->open_col( 'span', array( 'medium' => $col ), null, 'post-format' ) . '<i class="el-icon-volume-up"></i> <a href="' . esc_url( get_post_format_link( 'audio' ) ) . '">' . __('Audio','shoestrap') . '</a>' . $ss_framework->close_col( 'span' );
						}
						if ( get_post_format( $post->ID ) === 'chat' ) {
						  $content .= $ss_framework->open_col( 'span', array( 'medium' => $col ), null, 'post-format' ) . '<i class="el-icon-comment-alt"></i> <a href="' . esc_url( get_post_format_link( 'chat' ) ) . '">' . __('Chat','shoestrap') . '</a>' . $ss_framework->close_col( 'span' );
						}
					}

					// output date element
					if ( $meta == 'date' && ! empty( $value ) ) {
						if ( ! has_post_format( 'link' ) ) {
							$format_prefix = ( has_post_format( 'chat' ) || has_post_format( 'status' ) ) ? _x( '%1$s on %2$s', '1: post format name. 2: date', 'shoestrap' ): '%2$s';

							if ( $date_format == 0 ) {
								$text = esc_html( sprintf( $format_prefix, get_post_format_string( get_post_format() ), get_the_date() ) );
								$icon = "el-icon-calendar icon";
							}
							elseif ( $date_format == 1 ) {
								$text = sprintf( human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ago');
								$icon = "el-icon-time icon";
							}

							$content .= sprintf( $ss_framework->open_col( 'span', array( 'medium' => $col ), null, 'date' ) . '<i class="' . $icon . '"></i> <a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a>' . $ss_framework->close_col( 'span' ),
								esc_url( get_permalink() ),
								esc_attr( sprintf( __( 'Permalink to %s', 'shoestrap' ), the_title_attribute( 'echo=0' ) ) ),
								esc_attr( get_the_date( 'c' ) ),
								$text
							);
						}
					}

					// output category element
					if ( $meta == 'category' && ! empty( $value ) ) {
						if ( $categories_list ) {
							$content .= $ss_framework->open_col( 'span', array( 'medium' => $col ), null, 'categories-links' ) . '<i class="el-icon-folder-open icon"></i> ' . $categories_list . $ss_framework->close_col( 'span' );
						}
					}

					// output tag element
					if ( $meta == 'tags' && ! empty( $value ) ) {
						if ( $tag_list ) {
							$content .= $ss_framework->open_col( 'span', array( 'medium' => $col ), null, 'tags-links' ) . '<i class="el-icon-tags icon"></i> ' . $tag_list . $ss_framework->close_col( 'span' );
						}
					}

					// output author element
					if ( $meta == 'author' && ! empty( $value ) ) {
						$content .= sprintf( $ss_framework->open_col( 'span', array( 'medium' => $col ), null, 'author vcard' ) . '<i class="el-icon-user icon"></i> <a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a>' . $ss_framework->close_col( 'span' ),
							esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
							esc_attr( sprintf( __( 'View all posts by %s', 'shoestrap' ), get_the_author() ) ),
							get_the_author()
						);
					}

					// output comment count element
					if ( $meta == 'comment-count' && ! empty( $value ) ) {
						if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
							$content .= $ss_framework->open_col( 'span', array( 'medium' => $col ), null, 'comments-link' ) . '<i class="el-icon-comment icon"></i> <a href="' . get_comments_link( $post->ID ) . '">' . get_comments_number( $post->ID ) . ' ' . __( 'Comments', 'shoestrap' ) . '</a>' . $ss_framework->close_col( 'span' );
						}
					}

					// Output author meta but do not display it if user has selected not to show it.
					if ( $meta == 'author' && empty( $value ) ) {
						$content .= sprintf( $ss_framework->open_col( 'span', array( 'medium' => $col ), null, 'author vcard' ) . '<a class="url fn n" href="%1$s" title="%2$s" rel="author" style="display:none;">%3$s</a>' . $ss_framework->close_col( 'span' ),
							esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
							esc_attr( sprintf( __( 'View all posts by %s', 'shoestrap' ), get_the_author() ) ),
							get_the_author()
						);
					}
				}
			}

			if ( ! empty( $content ) ) {
				echo $ss_framework->open_row( 'div', null, 'row-meta' ) . $content . $ss_framework->close_row( 'div' );
			}
		}

		/**
		 * The "more" text
		 */
		function excerpt_more( $more ) {
			global $ss_settings;

			$continue_text = $ss_settings['post_excerpt_link_text'];
			return ' &hellip; <a href="' . get_permalink() . '">' . $continue_text . '</a>';
		}

		/**
		 * Excerpt length
		 */
		function excerpt_length($length) {
			global $ss_settings;

			$excerpt_length = $ss_settings['post_excerpt_length'];
			return $excerpt_length;
		}

		/*
		 * Display featured images on individual posts
		 */
		function featured_image() {
			global $ss_framework, $ss_settings;

			$data = array();

			if ( ! has_post_thumbnail() || '' == get_the_post_thumbnail() ) {
				return;
			}

			$data['width']  = Shoestrap_Layout::content_width_px();

			if ( is_singular() ) {
				// Do not process if we don't want images on single posts
				if ( $ss_settings['feat_img_post'] != 1 ) {
					return;
				}

				$data['url'] = wp_get_attachment_url( get_post_thumbnail_id() );

				if ( $ss_settings['feat_img_post_custom_toggle'] == 1 ) {
					$data['width']  = $ss_settings['feat_img_post_width'];
				}

				$data['height'] = $ss_settings['feat_img_post_height'];
				
			} else {
				// Do not process if we don't want images on post archives
				if ( $ss_settings['feat_img_archive'] != 1 ) {
					return;
				}

				$data['url'] = wp_get_attachment_url( get_post_thumbnail_id() );

				if ( $ss_settings['feat_img_archive_custom_toggle'] == 1 ) {
					$data['width']  = $ss_settings['feat_img_archive_width'];
				}

				$data['height'] = $ss_settings['feat_img_archive_height'];

			}

			$image = Shoestrap_Image::image_resize( $data );

			echo $ss_framework->clearfix() . '<a href="' . get_permalink() . '"><img class="featured-image ' . $ss_framework->float_class('left') . '" src="' . $image['url'] . '" /></a>';
		}

		/**
		 * Users can remove featured images per-post-type using the 'feat_img_per_post_type' control.
		 * This function makes sure that images are not added based on the user's selections.
		 */
		function remove_featured_image_per_post_type() {
			global $ss_settings;

			$post_types = get_post_types( array( 'public' => true ), 'names' );
			$post_type_options = (array) $ss_settings['feat_img_per_post_type'];

			foreach ( $post_types as $post_type ) {
				// Simply prevents "illegal string offset" messages
				if ( ! isset( $post_type_options[$post_type] ) ) {
					$post_type_options[$post_type] = 0;
				}

				if ( isset( $post_type ) && is_singular( $post_type ) ) {
					add_action( 'shoestrap_entry_meta', array( $this, 'featured_image' ) );
				}
			}
		}
	}
}
