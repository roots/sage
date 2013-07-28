<?php
/************************************************************

	Author: Andy Weisman
			aweisman@matchboxdesigngroup.com

	This generic class is really just designed to hold random functions/methods.
	By putting them in this generic class, we will avoid collisions and
	make these functions easier to find.  Since you will be forced to instantiate
	this class before you can use these functions, that instantiation will
	tell you (and others) that the function lives here.

************************************************************/

class MDG_Generic {

	public function add_this_code() {
		$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_id() ), 'thumbnail' );
		$url = $thumb['0'];

		return '<!-- AddThis Button BEGIN -->
				<div class="addthis_toolbox addthis_default_style ">
				<a class="addthis_button_preferred_1"></a>
				<a class="addthis_button_preferred_2"></a>
				<a class="addthis_button_preferred_3"></a>
				<a class="addthis_button_preferred_4"></a>
				<a class="addthis_button_compact"></a>
				</div>

				<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=xa-512a374f6d8b031b"></script>
				<meta property="og:image" content="'.$url.'"/>
				<meta property="og:site_name" content="StoneBridge Community Church"/>
				<!-- AddThis Button END -->';
	}

	public function truncate_string( $string, $limit, $break=".", $pad="..." ) {

		// sample use...
		//mdg_truncate($string, 30, " ")

		// return with no change if string is shorter than $limit
		if ( strlen( $string ) <= $limit ) return $string;

		// our first test
		$test1 = strpos( $string, $break, $limit );

		// second test to make sure we didn't land on a break (won't truncate)
		$test2 = strpos( $string, $break, $limit -1 );

		// is $break present between $limit and the end of the string?
		if ( false !== ( $breakpoint = $test1 ) || false !== ( $breakpoint = $test2 ) ) {
			if ( $breakpoint < strlen( $string ) - 1 ) {
				$string = substr( $string, 0, $breakpoint ) . $pad;
			}
		}

		return $string;

	}

	public function kriesi_pagination( $pages = '', $range = 2 ) {
		$showitems = ( $range * 2 )+1;

		global $paged;
		if ( empty( $paged ) ) $paged = 1;

		if ( $pages == '' ) {
			global $wp_query;
			$pages = $wp_query->max_num_pages;
			if ( !$pages ) {
				$pages = 1;
			}
		}

		if ( 1 != $pages ) {
			echo "<div class='pagination'>";
			if ( $paged > 2 && $paged > $range+1 && $showitems < $pages ) echo "<a href='".get_pagenum_link( 1 )."'>&laquo;</a>";
			if ( $paged > 1 && $showitems < $pages ) echo "<a href='".get_pagenum_link( $paged - 1 )."'>&lsaquo;</a>";

			for ( $i=1; $i <= $pages; $i++ ) {
				if ( 1 != $pages &&( !( $i >= $paged+$range+1 || $i <= $paged-$range-1 ) || $pages <= $showitems ) ) {
					echo ( $paged == $i )? "<span class='current'>".$i."</span>":"<a href='".get_pagenum_link( $i )."' class='inactive' >".$i."</a>";
				}
			}

			if ( $paged < $pages && $showitems < $pages ) echo "<a href='".get_pagenum_link( $paged + 1 )."'>&rsaquo;</a>";
			if ( $paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages ) echo "<a href='".get_pagenum_link( $pages )."'>&raquo;</a>";
			echo "</div>\n";
		}
	}

	public function get_the_excerpt( $id=false, $allowable_tags = array() ) {
		$post = get_post( $id );
		$excerpt = trim( $post->post_excerpt );
		if ( !$excerpt ) {
			$excerpt = $post->post_content;

			$excerpt = strip_shortcodes( $excerpt );
			$excerpt = apply_filters( 'the_content', $excerpt );
			$excerpt = str_replace( ']]>', ']]&gt;', $excerpt );
			$excerpt = strip_tags( $excerpt, $allowable_tags );
			$excerpt_length = apply_filters( 'excerpt_length', 55 );
			// $excerpt_more = '... <br><a href="'. get_permalink($post->ID) . '" class="more-link lato-bold-italic">Read More</a>';

			$words = preg_split( "/[\n\r\t ]+/", $excerpt, $excerpt_length + 1, PREG_SPLIT_NO_EMPTY );
			if ( count( $words ) > $excerpt_length ) {
				array_pop( $words );
				$excerpt = implode( ' ', $words );
				$excerpt = $excerpt . $excerpt_more;
			} else {
				$excerpt = implode( ' ', $words );
			}
		}

		return $excerpt;
	}

	public function output_prev_next_nav( $post = array() ) {

		$post_type = get_post_type( $post );

		// have to get the url for the next post to use in our faux link

		// first let's be sure that everything is getting reset properly
		$nepo              = '';
		$prepo             = '';
		$nepoid            = '';
		$prepoid           = '';
		$next_post_url     = '';
		$previous_post_url = '';
		$prev_and_next     = '';

		if ( $post_type == 'post' ) {

			// we can use some built in stuff for regular posts

			if ( get_next_post() ) {

				$nepo          = get_next_post();
				$nepoid        = $nepo->ID;
				$next_post_url = get_permalink( $nepoid );

			}

			if ( get_previous_post() ) {

				$prepo          = get_previous_post();
				$prepoid        = $nepo->ID;
				$previous_post_url = get_permalink( $nepoid );

			}

		} else {

			// looks like we have to use some more grown up stuff for
			// custom post types
			// so get the previous and next post ids from this method

			$prev_and_next = $this->mdg_next_post( $post );

			$next_post_url = !empty( $prev_and_next['next'] ) ? get_permalink( $prev_and_next['next'] ) : '';
			$prev_post_url = !empty( $prev_and_next['prev'] ) ? get_permalink( $prev_and_next['prev'] ) : '';

		}

		echo '<section class="span6 trans-block content-block underline direction-nav">';

		echo '<div class="inner-pad-all">';

		if ( $post_type == 'post' ) {

			if ( get_previous_post() ) {
				previous_post_link(
					'%link',
					'<span class="title1">&lt; Previous article</span>' );

			}

			if ( get_next_post() ) {
				next_post_link(
					'%link',
					'<span class="title1 fr">Next article &gt;</span>' );
			}

		} else {
			// again, need to account for custom post types here
			if ( $prev_post_url ) {
				echo '<a href="'.$prev_post_url.'"><span class="title1">&lt; Previous '.$post_type.' </span></a>';
			}

			if ( $next_post_url ) {
				echo '<a href="'.$next_post_url.'"><span class="title1 fr">Next '.$post_type.' &gt;</span></a>';
			}

		}
		echo '<div class="cl"></div>';
		echo '</div>';

		echo '</section>';
	}

	public function mdg_next_post( $post = array() ) {
		// appearently there are some issues with next_post_link() and custom post types
		// so i guess we're gonna make our own :)

		// please pass me a post object and i'll return an array of
		// the previuos and next posts

		// attention!!! notice that i'm flipping previous and next so an older post will
		// be previous etc... (that happens in the return)
		$query_args = array(
			'post_type'      => $post->post_type,
			'post_status'    => 'publish',
			'posts_per_page' => -1        // TODO: leaving this unbounded is bad news bears
		);

		// adjust order for service post type
		if ( get_post_type( get_the_id() ) == 'service' ) {
			$query_args['orderby'] = 'title';
			$query_args['order']   = 'ASC';
		}

		$query = new WP_Query( $query_args );

		$posts = $query->get_posts();
		$ids   = array();

		foreach ( $posts as $item ) {
			array_push( $ids, $item->ID );
		}

		$current = $post->ID;
		$nextkey = array_search( $current, $ids, true ) + 1;
		$prevkey = array_search( $current, $ids, true ) - 1;

		if ( $nextkey == count( $sorting ) ) {
			// reached end of array, reset
			$nextkey = 0;
		}

		if ( $prevkey == 1 ) {
			// beginning of array, reset
			$prevkey = 0;
		}

		$next = $ids[$nextkey];
		$prev = $ids[$prevkey];

		return array(
			'prev' => $next,
			'next' => $prev
		);
	}

	public function get_attachments( $post = array(), $args = array() ) {
		// pass me a post array and i'll return an array of it's attachements

		// try to get the global post in case it wasn't passed
		if ( empty( $post ) ) {
			global $post;
		}

		$limit = isset( $args['limit'] ) ? $args['limit'] : 99;

		$args     = array(
			'post_type'  => 'attachment',
			'numberposts'  => $limit,
			'post_status'  => null,
			'order'   => 'ASC',
			'orderby'   => 'menu_order',
			'post_parent'  => $post->ID
		);

		$attachments   = get_posts( $args );

		return $attachments;

	}

	public function print_attachments( $args = array() ) {

		$limit        = isset( $args['limit'] )        ? $args['limit']        : '';
		$post         = isset( $args['post'] )         ? $args['post']         : '';
		$gallery_type = isset( $args['gallery_type'] ) ? $args['gallery_type'] : 'gallery';

		// get the attachments if they weren't passed
		$attachments = isset( $args['attachments'] ) ? $args['attachments'] : '';

		if ( empty( $attachments ) ) {
			$attachments = $this->get_attachments( $post, array( 'limit' => $limit ) );
		}

		// attachments have to be greater than 1 to
		// be sure that we're not grabbing only
		// the featured image
		if ( count( $attachments ) > 1 ) {

			echo '<div class="slider '.$gallery_type.'">';
			echo '<ul class="slides">';

			$i = 0;
			$end_i = 2;
			foreach ( $attachments as $attachment ) {

				$li_class = $i < 1 ? 'current-slide' : '';

				// for the thumb
				$attachment_url      = wp_get_attachment_image_src( $attachment->ID, '60x60' );

				// for the large gallery image
				$attachment_full_url = wp_get_attachment_image_src( $attachment->ID, 'x475' );

				// for the large fancybox (lightbox) image
				$attachment_large_url= wp_get_attachment_image_src( $attachment->ID, 'large' );

				if ( ! $attachment_url )
					continue;

				// replace quotes so excerpts pass properly
				$excerpt = !empty( $attachment->post_content ) ? str_replace( '"', '&quot;', $attachment->post_content ) : str_replace( '"', '&quot;', $attachment->post_excerpt );

				$excerpt = empty( $excerpt ) ? $attachment->post_title : $excerpt;


				// let's try to keep the gallery-trigger class as just a js trigger...
				// try not to attach styles to it

				echo '<li
						class="span1_5 gallery-trigger '.$li_class.'"
						data-image-url="'.$attachment_full_url[0].'"
						data-thumb="'.$attachment_url[0].'"
						data-full-image-url="'.$attachment_full_url[0].'"
						data-image-caption="'.$attachment->post_excerpt.'">';

				echo '<img src="'.$attachment_full_url[0].'">';
				echo '</li>';

				$i++;
			}

			echo '</ul>';
			echo '</div>';

		} // end if $attachments

	}

	public function roll_template( $posts = array() ) {

		// pass me an array of posts, and i'll return
		// the html of the layout (list)
		$html = '';

		foreach ( $posts as $post ) {
			if ( get_post_type( $post->ID ) == 'toolbox_talk' ) {
				// link to pdf instead of post for toolbox talks
				// so first, get the pdf attachements (although we'll only use the first

				$args = array(
					'post_mime_type' => 'application/pdf',
					'post_type'      => 'attachment',
					'numberposts'    => 1,
					'post_status'    => null,
					'post_parent'    => $post->ID
				);

				$attachments = get_posts( $args );
				$target      = '_self';

				if ( $attachments ) {
					$link_to_post = $attachments[0]->guid;
					$target       = '_blank';
				} else {
					$link_to_post = '';
				}

			} else {
				// link to the post for everything else
				$link_to_post = get_permalink( $post->ID );
			}
			$html .= '<li class="clickable-mobile" data-faux-link="'.$link_to_post.'">';

			if ( has_post_thumbnail( $post->ID ) ) {

				$src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), '210x165', false, '' );

			} else {

				$src = array( '/assets/img/site/place-holder.png' );

			}
			$html .= '<div class="lazy-image small-featured fl" data-image-url="'.$src[0].'">';
			$html .= '<a href="'.$link_to_post.'" target="'.$target.'">';
			$html .= '<noscript><img src="'.$src[0].'" alt="'.$link_to_post.'" /></noscript>';
			$html .= '</a>';
			$html .= '</div>';

			$html .= '<div class="copy">';
			$html .= '<h2 class="title2">';
			$html .= '<a href="'.$link_to_post.'" target="'.$target.'">';
			$html .= $post->post_title;
			$html .= '</a>';
			$html .= '</h2>';
			$html .= $this->determine_teaser_format( $post );
			$html .= '</div>';
			$html .= '<a href="'.$link_to_post.'" class="more-link fr" target="'.$target.'">more &gt;</a>';
			$html .= '</li>';
		}

		return $html;
	}

	public function determine_teaser_format( $post = array() ) {
		// pass a post object and this guy will return the html for the teaser
		// based on things like title lenght etc...
		$title_length = strlen( $post->post_title );

		// only show excert if titles are shorter than this
		$excerpt_threshold = 50;
		$excerpt = $this->get_the_excerpt( $post );

		$html  = '';

		// disabling this since we made the font size smaller
		// if( $title_length > $excerpt_threshold ){
		// return;
		// } else {
		$html .= !empty( $excerpt ) ? '<p>'.$excerpt.'</p>' : '';

		// }

		return $html;
	}

	public function get_youtube_id( $embed ) {

		// pass me a link or an embed code and I'll return the youtube id for the video
		preg_match( '#(\.be/|/embed/|/v/|/watch\?v=)([A-Za-z0-9_-]{5,11})#', $embed, $matches );
		if ( isset( $matches[2] ) && $matches[2] != '' ) {
			$youtube_id = $matches[2];
		}

		return $youtube_id;
	}

	public function get_video( $post = array() ) {
		// pass me a post array, and i'll return the html
		// for the video (if one exists
		$embed = get_post_meta( $post->ID, 'videoEmbed', true );
		$html = '';

		if ( !empty( $embed ) ) {
			$youtube_id = $this->get_youtube_id( $embed );

			$html .= '<section class="span6 trans-block content-block">';
			$html .= '<div class="inner-pad-all">';

			$html .= '<iframe width="100%" height="315" src="http://www.youtube.com/embed/'.$youtube_id.'?rel=0" frameborder="0" allowfullscreen></iframe>';

			$html .= '</div>';
			$html .= '</section>';
		}

		return $html;

	}

	public function clean_awards( $awards = '' ) {
		// this converts the awards from it's saved state (fake sorta json object thingy)
		// to a php array

		// make it a valid json object
		$awards = str_replace( '|', '"', $awards );

		// decode to get make it php friendly array
		$awards = json_decode( $awards );

		return $awards;
	}

	public function group_awards( $awards = '' ) {

		// this method will get the awards, clean them via this->clean_awards, and return them in a grouped array

		// clean/format awards
		$awards = $this->clean_awards( $awards );

		$i      = 1;
		$awards_fields_count= 3; // this is the number of fields for each group of rewards
		$tracker    = 1;
		$grouped_array  = array();

		foreach ( $awards as $award ) {

			// iterate through awards, building an award (item) with each field
			if ( $tracker == 1 ) {
				//first in group
				$item = array();
			}

			array_push( $item, $award );

			if ( $tracker == $awards_fields_count ) {
				// last in group

				array_push( $grouped_array, $item );

				$tracker = 1; // reset tracker

			} else {
				$tracker++;
			}

			$i++;
		}

		return $grouped_array;

	}

	public function get_img_urls( $attachment_id = '' ) {
		// pass id of attachment
		// return array of image urls for different sizes

		// for the thumb
		$attachment_url      = wp_get_attachment_image_src( $attachment_id, '60x60' );

		// for the large gallery image
		$attachment_full_url = wp_get_attachment_image_src( $attachment_id, 'x475' );

		// for the large fancybox (lightbox) image
		$attachment_large_url= wp_get_attachment_image_src( $attachment_id, 'large' );

		if ( ! $attachment_url )
			return false;

		return array(
			'small'   => $attachment_url[0],
			'medium'  => $attachment_full_url[0],
			'large'   => $attachment_large_url[0]
		);
	}

}

$GLOBALS['MDG_Generic'] = new MDG_Generic();
