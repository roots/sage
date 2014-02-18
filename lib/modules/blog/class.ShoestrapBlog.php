<?php


if ( !class_exists( 'ShoestrapBlog' ) ) {

	/**
	* The "Blog" module
	*/
	class ShoestrapBlog {

		function __construct() {
			add_filter( 'redux/options/' . SHOESTRAP_OPT_NAME . '/sections', array( $this, 'options' ), 75 );
			add_filter( 'shoestrap_compiler',       array( $this, 'styles'                              ) );
			add_action( 'shoestrap_entry_meta',     array( $this, 'meta_custom_render'                  ) );
			add_filter( 'excerpt_more',             array( $this, 'excerpt_more'                        ) );
			add_filter( 'excerpt_length',           array( $this, 'excerpt_length'                      ) );
			add_action( 'shoestrap_in_article_top', array( $this, 'featured_image'                      ) );
			add_action( 'wp',                       array( $this, 'remove_featured_image_per_post_type' ) );

			if ( shoestrap_getVariable( 'pagination' ) != 'pager' )
				add_filter( 'shoestrap_pagination_format', array( $this, 'pagination_toggler' ) );

		 	// Hide post meta data in footer of single posts
			if ( shoestrap_getVariable( 'single_meta' ) == 0 ) {
				add_filter( 'shoestrap_the_tags', 'shoestrap_blank' );
				add_filter( 'shoestrap_the_cats', 'shoestrap_blank' );
			}
		}

		function options( $sections ) {

			// Post Meta Options
			$section = array(
				'title' => __( 'Blog', 'shoestrap' ),
				'icon'  => 'el-icon-wordpress icon-large'
			);

			$fields[] = array(
				'id'          => 'shoestrap_entry_meta_config',
				'title'       => __( 'Activate and order Post Meta elements', 'shoestrap' ),
				'options'     => array(
					'tags'    => 'Tags',
					'date'    => 'Date',
					'category'=> 'Category',
					'author'  => 'Author',
					'sticky'  => 'Sticky'
				),
				'type'        => 'sortable',
				'mode'        => 'checkbox'
			);

			$fields[] = array( 
				'title'     => __( 'Switch Date Meta in time_diff mode', 'shoestrap' ),
				'desc'      => __( 'Replace Date Meta element by displaying the difference between post creation timestamp and current timestamp. Default: OFF.', 'shoestrap' ),
				'id'        => 'date_meta_format',
				'default'   => 0,
				'type'      => 'switch',
			);

			// Featured Images Options
			$settings  = get_option( SHOESTRAP_OPT_NAME );
			$screen_large_desktop = filter_var( $settings[ 'screen_large_desktop' ], FILTER_SANITIZE_NUMBER_INT );

			$fields[] = array( 
				'id'        => 'help3',
				'title'     => __( 'Featured Images', 'shoestrap' ),
				'desc'      => __( 'Here you can select if you want to display the featured images in post archives and individual posts.
												Please note that these apply to posts, pages, as well as custom post types.
												You can select image sizes independently for archives and individual posts view.', 'shoestrap' ),
				'type'      => 'info',
			);

			$fields[] = array( 
				'title'     => __( 'Featured Images on Archives', 'shoestrap' ),
				'desc'      => __( 'Display featured Images on post archives ( such as categories, tags, month view etc ). Default: OFF.', 'shoestrap' ),
				'id'        => 'feat_img_archive',
				'default'   => 0,
				'type'      => 'switch',
				'customizer'=> true,
			);


			$fields[] = array( 
				'title'     => __( 'Width of Featured Images on Archives', 'shoestrap' ),
				'desc'      => __( 'Set dimensions of featured Images on Archives. Default: Full Width', 'shoestrap' ),
				'id'        => 'feat_img_archive_custom_toggle',
				'default'   => 0,
				'required'  => array('feat_img_archive','=',array('1')),
				'off'       => __( 'Full Width', 'shoestrap' ),
				'on'        => __( 'Custom Dimensions', 'shoestrap' ),
				'type'      => 'switch',
				'customizer'=> true,
			);

			$fields[] = array( 
				'title'     => __( 'Archives Featured Image Custom Width', 'shoestrap' ),
				'desc'      => __( 'Select the width of your featured images on single posts. Default: 550px', 'shoestrap' ),
				'id'        => 'feat_img_archive_width',
				'default'   => 550,
				'min'       => 100,
				'step'      => 1,
				'max'       => $screen_large_desktop,
				'required'  => array('feat_img_archive','=',array('1')),
				'edit'      => 1,
				'type'      => 'slider'
			);

			$fields[] = array( 
				'title'     => __( 'Archives Featured Image Custom Height', 'shoestrap' ),
				'desc'      => __( 'Select the height of your featured images on post archives. Default: 300px', 'shoestrap' ),
				'id'        => 'feat_img_archive_height',
				'default'   => 300,
				'min'       => 50,
				'step'      => 1,
				'edit'      => 1,
				'max'       => $screen_large_desktop,
				'required'  => array('feat_img_archive','=',array('1')),
				'type'      => 'slider'
			);

			$fields[] = array( 
				'title'     => __( 'Featured Images on Posts', 'shoestrap' ),
				'desc'      => __( 'Display featured Images on posts. Default: OFF.', 'shoestrap' ),
				'id'        => 'feat_img_post',
				'default'   => 0,
				'type'      => 'switch',
				'customizer'=> true,
			);

			$fields[] = array( 
				'title'     => __( 'Width of Featured Images on Posts', 'shoestrap' ),
				'desc'      => __( 'Set dimensions of featured Images on Posts. Default: Full Width', 'shoestrap' ),
				'id'        => 'feat_img_post_custom_toggle',
				'default'   => 0,
				'off'       => __( 'Full Width', 'shoestrap' ),
				'on'        => __( 'Custom Dimensions', 'shoestrap' ),
				'type'      => 'switch',
				'required'  => array('feat_img_post','=',array('1')),
				'customizer'=> true,
			);

			$fields[] = array( 
				'title'     => __( 'Posts Featured Image Custom Width', 'shoestrap' ),
				'desc'      => __( 'Select the width of your featured images on single posts. Default: 550px', 'shoestrap' ),
				'id'        => 'feat_img_post_width',
				'default'   => 550,
				'min'       => 100,
				'step'      => 1,
				'max'       => $screen_large_desktop,
				'edit'      => 1,
				'required'  => array('feat_img_post','=',array('1')),
				'type'      => 'slider'
			);

			$fields[] = array( 
				'title'     => __( 'Posts Featured Image Custom Height', 'shoestrap' ),
				'desc'      => __( 'Select the height of your featured images on single posts. Default: 330px', 'shoestrap' ),
				'id'        => 'feat_img_post_height',
				'default'   => 330,
				'min'       => 50,
				'step'      => 1,
				'max'       => $screen_large_desktop,
				'edit'      => 1,
				'required'  => array('feat_img_post','=',array('1')),
				'type'      => 'slider'
			);

			$post_types = get_post_types( array( 'public' => true ), 'names' );
			$post_type_options  = array();
			$post_type_defaults = array();

			foreach ( $post_types as $post_type ) {
				$post_type_options[$post_type]  = $post_type;
				$post_type_defaults[$post_type] = 0;
			}

			$fields[] = array(
				'title'     => __( 'Disable featured images on single post types', 'shoestrap' ),
				'id'        => 'feat_img_per_post_type',
				'type'      => 'checkbox',
				'options'   => $post_type_options,
				'default'   => $post_type_defaults,
			);

			$fields[] = array( 
				'title'     => __( 'Post excerpt length', 'shoestrap' ),
				'desc'      => __( 'Choose how many words should be used for post excerpt. Default: 40', 'shoestrap' ),
				'id'        => 'post_excerpt_length',
				'default'   => 40,
				'min'       => 10,
				'step'      => 1,
				'max'       => 1000,
				'edit'      => 1,
				'type'      => 'slider'
			);
			
			$fields[] = array( 
				'title'     => __( '"more" text', 'shoestrap' ),
				'desc'      => __( 'Text to display in case of excerpt too long. Default: Continued', 'shoestrap' ),
				'id'        => 'post_excerpt_link_text',
				'default'   => __( 'Continued', 'shoestrap' ),
				'type'      => 'text'
			);

			$fields[] = array( 
				'title'     => __( 'Select pagination style', 'shoestrap' ),
				'desc'      => __( 'Switch between default pager or default pagination. Default: Pager.', 'shoestrap' ),
				'id'        => 'pagination',
				'type'      => 'button_set',
				'options'   => array(
					'pager'       => 'Default Pager',
					'pagination'  => 'Default Pagination'
				),
				'default'   => 'pager',
				'customizer'=> array()
			);

			$fields[] = array( 
				'title'     => __( 'Show Breadcrumbs', 'shoestrap' ),
				'desc'      => __( 'Display Breadcrumbs. Default: OFF.', 'shoestrap' ),
				'id'        => 'breadcrumbs',
				'default'   => 0,
				'type'      => 'switch',
				'customizer'=> array(),
			);

			$fields[] = array( 
				'title'     => __( 'Show Post Meta in single posts', 'shoestrap' ),
				'desc'      => __( 'Toggle Post Meta showing in the footer of single posts. Default: ON.', 'shoestrap' ),
				'id'        => 'single_meta',
				'default'   => 1,
				'type'      => 'switch',
				'customizer'=> array(),
			);

			$section['fields'] = $fields;
			$section = apply_filters( 'shoestrap_module_blog_modifier', $section );
			$sections[] = $section;

			return $sections;
		}


		function styles( $bootstrap ) {
			return $bootstrap . '
			@import "' . SHOESTRAP_MODULES_PATH . '/blog/assets/less/styles.less";';
		}

		/**
		 * Output of meta information for current post: categories, tags, permalink, author, and date.
		 */
		function meta_custom_render() {
			// get config and data
			$metas = shoestrap_getVariable( 'shoestrap_entry_meta_config' );
			$date_format = shoestrap_getVariable( 'date_meta_format' );

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
					if ( $meta == 'sticky' && !empty( $value ) && is_sticky() ) {
						$content .= '<span class="featured-post ' . $colclass . '"><i class="el-icon-flag icon"></i> ' . __( 'Sticky', 'shoestrap' ) . '</span>';
					}

					// output date element
					if ( $meta == 'date' && !empty( $value ) ) {
						if ( !has_post_format( 'link' ) ) {
							$format_prefix = ( has_post_format( 'chat' ) || has_post_format( 'status' ) ) ? _x( '%1$s on %2$s', '1: post format name. 2: date', 'shoestrap' ): '%2$s';

							if ( $date_format == 0 ) {
								$text = esc_html( sprintf( $format_prefix, get_post_format_string( get_post_format() ), get_the_date() ) );
								$icon = "el-icon-calendar icon";
							} 
							elseif ( $date_format == 1 ) {
								$text = sprintf( human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ago');
								$icon = "el-icon-time icon";
							}

							$content .= sprintf( '<span class="date ' . $colclass . '"><i class="' . $icon . '"></i> <a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a></span>',
								esc_url( get_permalink() ),
								esc_attr( sprintf( __( 'Permalink to %s', 'shoestrap' ), the_title_attribute( 'echo=0' ) ) ),
								esc_attr( get_the_date( 'c' ) ),
								$text
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

					// Output author meta but do not display it if user has selected not to show it.
					if ( $meta == 'author' && empty( $value ) ) {
						$content .= sprintf( '<span class="sr-only author vcard ' . $colclass . '"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
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

		/**
		 * The "more" text
		 */
		function excerpt_more( $more ) {
			$continue_text = shoestrap_getVariable( 'post_excerpt_link_text' );
			return ' &hellip; <a href="' . get_permalink() . '">' . $continue_text . '</a>';
		}

		/**
		 * Excerpt length
		 */
		function excerpt_length($length) {
			$excerpt_length = shoestrap_getVariable( 'post_excerpt_length' );
			return $excerpt_length;
		}
		

		/**
		 * Retrieve paginated link for archive post pages.
		 *
		 * Technically, the function can be used to create paginated link list for any
		 * area. The 'base' argument is used to reference the url, which will be used to
		 * create the paginated links. The 'format' argument is then used for replacing
		 * the page number. It is however, most likely and by default, to be used on the
		 * archive post pages.
		 *
		 * The 'type' argument controls format of the returned value. The default is
		 * 'plain', which is just a string with the links separated by a newline
		 * character. The other possible values are either 'array' or 'list'. The
		 * 'array' value will return an array of the paginated link list to offer full
		 * control of display. The 'list' value will place all of the paginated links in
		 * an unordered HTML list.
		 *
		 * The 'total' argument is the total amount of pages and is an integer. The
		 * 'current' argument is the current page number and is also an integer.
		 *
		 * An example of the 'base' argument is "http://example.com/all_posts.php%_%"
		 * and the '%_%' is required. The '%_%' will be replaced by the contents of in
		 * the 'format' argument. An example for the 'format' argument is "?page=%#%"
		 * and the '%#%' is also required. The '%#%' will be replaced with the page
		 * number.
		 *
		 * You can include the previous and next links in the list by setting the
		 * 'prev_next' argument to true, which it is by default. You can set the
		 * previous text, by using the 'prev_text' argument. You can set the next text
		 * by setting the 'next_text' argument.
		 *
		 * If the 'show_all' argument is set to true, then it will show all of the pages
		 * instead of a short list of the pages near the current page. By default, the
		 * 'show_all' is set to false and controlled by the 'end_size' and 'mid_size'
		 * arguments. The 'end_size' argument is how many numbers on either the start
		 * and the end list edges, by default is 1. The 'mid_size' argument is how many
		 * numbers to either side of current page, but not including current page.
		 *
		 * It is possible to add query vars to the link by using the 'add_args' argument
		 * and see {@link add_query_arg()} for more information.
		 *
		 * @since 2.1.0
		 *
		 * @param string|array $args Optional. Override defaults.
		 * @return array|string String of page links or array of page links.
		 */
		public static function paginate_links( $args = '' ) {
			$defaults = array(
				'base' => '%_%', // http://example.com/all_posts.php%_% : %_% is replaced by format (below)
				'format' => '?page=%#%', // ?page=%#% : %#% is replaced by the page number
				'total' => 1,
				'current' => 0,
				'show_all' => false,
				'prev_next' => true,
				'prev_text' => __('&laquo; Previous'),
				'next_text' => __('Next &raquo;'),
				'end_size' => 1,
				'mid_size' => 2,
				'type' => 'plain',
				'add_args' => false, // array of query args to add
				'add_fragment' => ''
			);

			$args = wp_parse_args( $args, $defaults );
			extract($args, EXTR_SKIP);

			// Who knows what else people pass in $args
			$total = (int) $total;
			if ( $total < 2 )
				return;

			$current  = (int) $current;
			$end_size = 0  < (int) $end_size ? (int) $end_size : 1; // Out of bounds?  Make it the default.
			$mid_size = 0 <= (int) $mid_size ? (int) $mid_size : 2;
			$add_args = is_array($add_args) ? $add_args : false;
			$r = '';
			$page_links = array();
			$n = 0;
			$dots = false;

			if ( $prev_next && $current && 1 < $current ) {
				$link = str_replace('%_%', 2 == $current ? '' : $format, $base);
				$link = str_replace('%#%', $current - 1, $link);
				if ( $add_args )
					$link = add_query_arg( $add_args, $link );
				$link .= $add_fragment;
				$page_links[] = '<li><a class="prev page-numbers" href="' . esc_url( apply_filters( 'paginate_links', $link ) ) . '">' . $prev_text . '</a></li>';
			}
			for ( $n = 1; $n <= $total; $n++ ) {
				$n_display = number_format_i18n($n);
				if ( $n == $current ) {
					$page_links[] = "<li class='active'><span class='page-numbers current'>$n_display</span></li>";
					$dots = true;
				} else {
					if ( $show_all || ( $n <= $end_size || ( $current && $n >= $current - $mid_size && $n <= $current + $mid_size ) || $n > $total - $end_size ) ) {
						$link = str_replace('%_%', 1 == $n ? '' : $format, $base);
						$link = str_replace('%#%', $n, $link);

						if ( $add_args )
							$link = add_query_arg( $add_args, $link );

						$link .= $add_fragment;
						$page_links[] = "<li><a class='page-numbers' href='" . esc_url( apply_filters( 'paginate_links', $link ) ) . "'>$n_display</a></li>";
						$dots = true;
					} elseif ( $dots && !$show_all ) {
						$page_links[] = '<li><span class="page-numbers dots">' . __( '&hellip;' ) . '</span></li>';
						$dots = false;
					}
				}
			}

			if ( $prev_next && $current && ( $current < $total || -1 == $total ) ) {
				$link = str_replace('%_%', $format, $base);
				$link = str_replace('%#%', $current + 1, $link);

				if ( $add_args )
					$link = add_query_arg( $add_args, $link );

				$link .= $add_fragment;
				$page_links[] = '<li><a class="next page-numbers" href="' . esc_url( apply_filters( 'paginate_links', $link ) ) . '">' . $next_text . '</a></li>';
			}
			switch ( $type ) :
				case 'array' :
					return $page_links;
					break;
				case 'list' :
					$r .= "<ul class='page-numbers pagination'>\n\t";
					$r .= join("\n\t", $page_links);
					$r .= "\n</ul>\n";
					break;
				default :
					$r = join("\n", $page_links);
					break;
			endswitch;
			return $r;
		}

		/**
		 * Use pagination instead of pagers
		 */
		function pagination_toggler() {
			global $wp_query;

			if ( $wp_query->max_num_pages <= 1 )
				return;

			$nav = '<nav class="pagination">';
			$nav .= self::paginate_links(
				apply_filters( 'pagination_args', array(
					'base'      => str_replace( 999999999, '%#%', get_pagenum_link( 999999999 ) ),
					'format'    => '',
					'current'     => max( 1, get_query_var('paged') ),
					'total'     => $wp_query->max_num_pages,
					'prev_text'   => '<i class="el-icon-chevron-left"></i>',
					'next_text'   => '<i class="el-icon-chevron-right"></i>',
					'type'      => 'list',
					'end_size'    => 3,
					'mid_size'    => 3
				) )
			);
			$nav .= '</nav>';

			return $nav;
		}

		/*
		 * Display featured images on individual posts
		 */
		function featured_image() {

			$data = array();

			if ( !has_post_thumbnail() || '' == get_the_post_thumbnail() )
				return;

			$data['width']  = ShoestrapLayout::content_width_px();

			if ( is_singular() ) {
				if ( shoestrap_getVariable( 'feat_img_post' ) != 1 )
					return; // Do not process if we don't want images on single posts

				$data['url'] = wp_get_attachment_url( get_post_thumbnail_id() );
				
				if ( shoestrap_getVariable( 'feat_img_post_custom_toggle' ) == 1 ) {
					$data['width']  = shoestrap_getVariable( 'feat_img_post_width' );
					$data['height'] = shoestrap_getVariable( 'feat_img_post_height' );
				}
			} else {
				if ( shoestrap_getVariable( 'feat_img_archive' ) == 0 )
					return; // Do not process if we don't want images on post archives

				$data['url'] = wp_get_attachment_url( get_post_thumbnail_id() );
				
				if (shoestrap_getVariable( 'feat_img_archive_custom_toggle' ) == 1) {
					$data['width']  = shoestrap_getVariable( 'feat_img_archive_width' );
					$data['height'] = shoestrap_getVariable( 'feat_img_archive_height' );
				}
			}
			
			$image = ShoestrapImage::image_resize( $data );

			echo shoestrap_clearfix() . '<a href="' . get_permalink() . '"><img class="featured-image" src="' . $image['url'] . '" /></a>' . shoestrap_clearfix();
		}

		/**
		 * Users can remove featured images per-post-type using the 'feat_img_per_post_type' control.
		 * This function makes sure that images are not added based on the user's selections.
		 */
		function remove_featured_image_per_post_type() {
			$post_types = get_post_types( array( 'public' => true ), 'names' );
			$post_type_options = shoestrap_getVariable( 'feat_img_per_post_type' );

			foreach ( $post_types as $post_type ) {
				// Simply prevents "illegal string offset" messages
				if ( !isset( $post_type_options[$post_type] ) )
					$post_type_options[$post_type] = 0;

				if ( isset( $post_type ) && is_singular( $post_type ) ) {
					add_action( 'shoestrap_page_pre_content', array( $this, 'featured_image' ) );
					add_action( 'shoestrap_single_pre_content', array( $this, 'featured_image' ) );
				}
			}
		}
	}
}

$blog = new ShoestrapBlog();

include_once( dirname( __FILE__ ) . '/includes/class.ShoestrapBreadcrumbs.php' );