<?php


if ( ! class_exists( 'Shoestrap_Breadcrumbs' ) ) {

	/**
	 * This class handles the Breadcrumbs generation and display
	 */
	class Shoestrap_Breadcrumbs {

		/**
		 * Class constructor
		 */
		function __construct() {
			add_filter( 'bbp_get_breadcrumb', '__return_false' );
		}

		/**
		 * Get a term's parents.
		 *
		 * @param object $term Term to get the parents for
		 * @return array
		 */
		function get_term_parents( $term ) {
			$tax     = $term->taxonomy;
			$parents = array();
			while ( $term->parent != 0 ) {
				$term      = get_term( $term->parent, $tax );
				$parents[] = $term;
			}
			return array_reverse( $parents );
		}

		/**
		 * Display or return the full breadcrumb path.
		 *
		 * @param string $before  The prefix for the breadcrumb, usually something like "You're here".
		 * @param string $after   The suffix for the breadcrumb.
		 * @param bool   $display When true, echo the breadcrumb, if not, return it as a string.
		 * @return string
		 */
		function breadcrumb( $display = true ) {
			// $options = get_wpseo_options();

			global $wp_query, $post;

			$on_front  = get_option( 'show_on_front' );
			$blog_page = get_option( 'page_for_posts' );

			$links = array(
				array(
					'url'  => get_home_url(),
					'text' => '<i class="el-icon-home"></i>'
				)
			);

			if ( is_singular() ) {
				if ( get_post_type_archive_link( $post->post_type ) ) {
					$links[] = array( 'post_type_archive' => $post->post_type );
				}

				if ( 0 == $post->post_parent ) {
					// get the taxonomy names of this object
					$taxonomy_names = get_object_taxonomies( get_post_type() );

					// Detect any hierarchical taxonomies that might exist on this post type
					$hierarchical = false;
					foreach ( $taxonomy_names as $taxonomy_name ) {
						if ( ! $hierarchical ) {
							$hierarchical = ( is_taxonomy_hierarchical( $taxonomy_name ) ) ? true : $hierarchical;
							$tn = $taxonomy_name;
						}
					}
					$main_tax = isset( $tn ) ? $tn : 'category';
					$terms    = wp_get_object_terms( $post->ID, $main_tax );

					if ( count( $terms ) > 0 ) {
						// Let's find the deepest term in this array, by looping through and then unsetting every term that is used as a parent by another one in the array.
						$terms_by_id = array();
						foreach ( $terms as $term ) {
							$terms_by_id[$term->term_id] = $term;
						}
						foreach ( $terms as $term ) {
							unset( $terms_by_id[$term->parent] );
						}

						// As we could still have two subcategories, from different parent categories, let's pick the one with the lowest ordered ancestor.
                        $parents_count = 0;
                        $term_order    = 9999; //because ASC
                        reset( $terms_by_id );
                        $deepest_term  = current($terms_by_id);
                        foreach ( $terms_by_id as $term ) {
                            $parents   = $this->get_term_parents( $term );

                            if ( sizeof( $parents ) >= $parents_count ) {
                                $parents_count = sizeof( $parents );

                                //if higher count
                                if ( sizeof( $parents ) > $parents_count ) {
                                    //reset order
                                    $term_order = 9999;
                                }

                                $parent_order = 9999; //set default order
                                foreach ( $parents as $parent ) {
                                    if ( $parent->parent == 0 && isset( $parent->term_order ) ) {
                                        $parent_order = $parent->term_order;
                                    }
                                }

                                //check if parent has lowest order
                                if ( $parent_order < $term_order ) {
                                    $term_order = $parent_order;

                                    $deepest_term = $term;
                                }
                            }
                        }

						if ( is_taxonomy_hierarchical( $main_tax ) && $deepest_term->parent != 0 ) {
							foreach ( $this->get_term_parents( $deepest_term ) as $parent_term ) {
								$links[] = array( 'term' => $parent_term );
							}
						}
						$links[] = array( 'term' => $deepest_term );
					}
				} else {
					if ( isset( $post->ancestors ) ) {
						if ( is_array( $post->ancestors ) ) {
							$ancestors = array_values( $post->ancestors );
						} else {
							$ancestors = array( $post->ancestors );
						}
					} else {
						$ancestors = array( $post->post_parent );
					}

					// Reverse the order so it's oldest to newest
					$ancestors = array_reverse( $ancestors );

					foreach ( $ancestors as $ancestor ) {
						$links[] = array( 'id' => $ancestor );
					}
				}
				$links[] = array( 'id' => $post->ID );
			} else {
				if ( is_post_type_archive() ) {
					$links[] = array( 'post_type_archive' => $wp_query->query['post_type'] );
				} else if ( is_tax() || is_tag() || is_category() ) {
					$term = $wp_query->get_queried_object();

					if ( is_taxonomy_hierarchical( $term->taxonomy ) && $term->parent != 0 ) {
						foreach ( $this->get_term_parents( $term ) as $parent_term ) {
							$links[] = array( 'term' => $parent_term );
						}
					}

					$links[] = array( 'term' => $term );
				} else if ( is_date() ) {
					$bc = __( 'Archives for', 'shoestrap' );

					if ( is_day() ) {
						global $wp_locale;
						$links[] = array(
							'url'  => get_month_link( get_query_var( 'year' ), get_query_var( 'monthnum' ) ),
							'text' => $wp_locale->get_month( get_query_var( 'monthnum' ) ) . ' ' . get_query_var( 'year' )
						);
						$links[] = array( 'text' => $bc . " " . get_the_date() );
					} else if ( is_month() ) {
						$links[] = array( 'text' => $bc . " " . single_month_title( ' ', false ) );
					} else if ( is_year() ) {
						$links[] = array( 'text' => $bc . " " . get_query_var( 'year' ) );
					}
				} elseif ( is_author() ) {
					$bc      = __( 'Archives for', 'shoestrap' );
					$user    = $wp_query->get_queried_object();
					$links[] = array( 'text' => $bc . " " . esc_html( $user->display_name ) );
				} elseif ( is_search() ) {
					$bc      = __( 'You searched for', 'shoestrap' );
					$links[] = array( 'text' => $bc . ' "' . esc_html( get_search_query() ) . '"' );
				} elseif ( is_404() ) {

					if ( 0 !== get_query_var( 'year' ) || ( 0 !== get_query_var( 'monthnum' ) || 0 !== get_query_var( 'day' ) ) ) {

						if ( 'page' == $on_front && ! is_home() ) {
							$links[] = array( 'id' => $blog_page );
						}

						$bc = __( 'Archives for', 'shoestrap' );


						if ( 0 !== get_query_var( 'day' ) ) {
							$links[] = array(
								'url'  => get_month_link( get_query_var( 'year' ), get_query_var( 'monthnum' ) ),
								'text' => $GLOBALS['wp_locale']->get_month( get_query_var( 'monthnum' ) ) . ' ' . get_query_var( 'year' )
							);
							global $post;
							$original_p = $post;
							$post->post_date = sprintf( "%04d-%02d-%02d 00:00:00", get_query_var( 'year' ), get_query_var( 'monthnum' ), get_query_var( 'day' ) );
							$links[] = array( 'text' => $bc . ' ' . get_the_date() );
							$post = $original_p;

						} else if ( 0 !== get_query_var( 'monthnum' ) ) {
							$links[] = array( 'text' => $bc . ' ' . single_month_title( ' ', false ) );
						} else if ( 0 !== get_query_var( 'year' ) ) {
							$links[] = array( 'text' => $bc . ' ' . get_query_var( 'year' ) );
						}
					}
					else {
						$crumb404 = __( 'Error 404: Page not found', 'shoestrap' );
						$links[] = array( 'text' => $crumb404 );
					}
				}
			}

			$output = $this->create_breadcrumbs_string( $links );

			if ( $display ) {
				echo $output;
				return true;
			} else {
				return $output;
			}
		}

		/**
		 * Take the links array and return a full breadcrumb string.
		 *
		 * Each element of the links array can either have one of these keys:
		 *       "id"            for post types;
		 *    "post_type_archive"  for a post type archive;
		 *    "term"         for a taxonomy term.
		 * If either of these 3 are set, the url and text are retrieved. If not, url and text have to be set.
		 *
		 * @link http://support.google.com/webmasters/bin/answer.py?hl=en&answer=185417 Google documentation on RDFA
		 *
		 * @param array  $links   The links that should be contained in the breadcrumb.
		 * @param string $wrapper The wrapping element for the entire breadcrumb path.
		 * @param string $element The wrapping element for each individual link.
		 * @return string
		 */
		function create_breadcrumbs_string( $links, $wrapper = 'span', $element = 'span' ) {
			global $paged;

			$sep    = '';
			$output = '';

			foreach ( $links as $i => $link ) {

				if ( isset( $link['id'] ) ) {
					$link['url']  = get_permalink( $link['id'] );
					$link['text'] = get_the_title( $link['id'] );
				}

				if ( isset( $link['term'] ) ) {
					$bctitle      = $link['term']->name;
					$link['url']  = get_term_link( $link['term'] );
					$link['text'] = $bctitle;
				}

				if ( isset( $link['post_type_archive'] ) ) {
					/* @todo add something along the lines of the below to make it work with WooCommerce.. ?
					if( false === $link['post_type_archive'] && true === is_post_type_archive( 'product' ) ) {
						$link['post_type_archive'] = 'product'; // translate ?
					}*/
					$post_type_obj = get_post_type_object( $link['post_type_archive'] );

					if( isset( $post_type_obj->label ) && $post_type_obj->label !== '' ) {
						$archive_title = $post_type_obj->label;
					} else {
						$archive_title = $post_type_obj->labels->menu_name;
					}

					$link['url']  = get_post_type_archive_link( $link['post_type_archive'] );
					$link['text'] = $archive_title;
				}

				$element     = esc_attr( $element );
				$link_output = '<li>';

				if ( isset( $link['url'] ) && ( $i < ( count( $links ) - 1 ) || $paged ) ) {
					$link_output .= '<a href="' . esc_url( $link['url'] ) . '" rel="v:url" property="v:title">' . $link['text'] . '</a>';
				} else {
					$link_output .= '<span class="breadcrumb_last" property="v:title">' . $link['text'] . '</span>';
				}

				$link_output .= '</li>';

				$link_sep = ( ! empty( $output ) ? " $sep " : '' );
				$output .= apply_filters( 'wpseo_breadcrumb_single_link_with_sep', $link_sep . $link_output, $link );
			}

			return '<ol class="breadcrumb">' . $output . '</ol>';
		}
	}
}
