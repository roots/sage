<?php


if ( ! class_exists( 'Shoestrap_Social' ) ) {
	/**
	* The "Social" modue
	*/
	class Shoestrap_Social {

		function __construct() {
			global $ss_settings;

			// When on a BuddyPress, disable social shares.
			if ( class_exists( 'BuddyPress' ) && shoestrap_is_bp() ) {
				$ss_settings['social_sharing_single_post'] = 0;
				$ss_settings['social_sharing_single_page'] = 0;
				$ss_settings['social_sharing_archives'] = 0;
			}

			$social_sharing_location = isset( $ss_settings['social_sharing_location'] ) ? $ss_settings['social_sharing_location'] : null;

			// Social Share select
			$social_sharing_single_page = isset( $ss_settings['social_sharing_single_page'] ) ? $ss_settings['social_sharing_single_page'] : null;

			// Conditions for showing content in posts archives
			if ( isset( $ss_settings['social_sharing_archives'] ) && $ss_settings['social_sharing_archives'] == 1 ) {
				add_action( 'shoestrap_entry_footer', array( $this, 'social_sharing' ), 5 );
			}

			// Conditions for showing content in single posts
			if ( isset( $ss_settings['social_sharing_single_post'] ) && $ss_settings['social_sharing_single_post'] == 1 ) {
				if ( $ss_settings['social_sharing_location'] == 'top' ) {
					add_action( 'shoestrap_single_pre_content',   array( $this, 'social_sharing' ), 5 );
				} elseif ( $ss_settings['social_sharing_location'] == 'bottom' ) {
					add_action( 'shoestrap_single_after_content', array( $this, 'social_sharing' ), 5 );
				} elseif ( $ss_settings['social_sharing_location'] == 'both' ) {
					add_action( 'shoestrap_single_pre_content',   array( $this, 'social_sharing' ), 5 );
					add_action( 'shoestrap_single_after_content', array( $this, 'social_sharing' ), 5 );
				}
			}

			// Conditions for showing content in single pages
			if ( isset( $ss_settings['social_sharing_single_page'] ) && $ss_settings['social_sharing_single_page'] == 1 ) {
				if ( $ss_settings['social_sharing_location'] == 'top' ) {
					add_action( 'shoestrap_page_pre_content',   array( $this, 'social_sharing' ), 5 );
				} elseif ( $ss_settings['social_sharing_location'] == 'bottom' ) {
					add_action( 'shoestrap_page_after_content', array( $this, 'social_sharing' ), 5 );
				} elseif ( $ss_settings['social_sharing_location'] == 'both' ) {
					add_action( 'shoestrap_page_pre_content',   array( $this, 'social_sharing' ), 5 );
					add_action( 'shoestrap_page_after_content', array( $this, 'social_sharing' ), 5 );
				}
			}
		}

		/**
		 * Return an array of the social links the user has entered.
		 * This is simply a helper function for other functions.
		 */
		function get_social_links() {
			global $ss_settings;
			// An array of the available networks
			$networks   = array();

			// Started on the new stuff, not done yet.
			$networks[] = array( 'url' => $ss_settings['blogger_link'],      'icon' => 'blogger',    'fullname' => 'Blogger' );
			$networks[] = array( 'url' => $ss_settings['deviantart_link'],   'icon' => 'deviantart', 'fullname' => 'DeviantART' );
			$networks[] = array( 'url' => $ss_settings['digg_link'],         'icon' => 'digg',       'fullname' => 'Digg' );
			$networks[] = array( 'url' => $ss_settings['dribbble_link'],     'icon' => 'dribbble',   'fullname' => 'Dribbble' );
			$networks[] = array( 'url' => $ss_settings['facebook_link'],     'icon' => 'facebook',   'fullname' => 'Facebook' );
			$networks[] = array( 'url' => $ss_settings['flickr_link'],       'icon' => 'flickr',     'fullname' => 'Flickr' );
			$networks[] = array( 'url' => $ss_settings['github_link'],       'icon' => 'github',     'fullname' => 'GitHub' );
			$networks[] = array( 'url' => $ss_settings['google_plus_link'],  'icon' => 'googleplus', 'fullname' => 'Google+' );
			$networks[] = array( 'url' => $ss_settings['instagram_link'],    'icon' => 'instagram',  'fullname' => 'Instagram' );
			$networks[] = array( 'url' => $ss_settings['linkedin_link'],     'icon' => 'linkedin',   'fullname' => 'LinkedIn' );
			$networks[] = array( 'url' => $ss_settings['myspace_link'],      'icon' => 'myspace',    'fullname' => 'Myspace' );
			$networks[] = array( 'url' => $ss_settings['pinterest_link'],    'icon' => 'pinterest',  'fullname' => 'Pinterest' );
			$networks[] = array( 'url' => $ss_settings['reddit_link'],       'icon' => 'reddit',     'fullname' => 'Reddit' );
			$networks[] = array( 'url' => $ss_settings['rss_link'],          'icon' => 'rss',        'fullname' => 'RSS' );
			$networks[] = array( 'url' => $ss_settings['skype_link'],        'icon' => 'skype',      'fullname' => 'Skype' );
			$networks[] = array( 'url' => $ss_settings['soundcloud_link'],   'icon' => 'soundcloud', 'fullname' => 'SoundCloud' );
			$networks[] = array( 'url' => $ss_settings['tumblr_link'],       'icon' => 'tumblr',     'fullname' => 'Tumblr' );
			$networks[] = array( 'url' => $ss_settings['twitter_link'],      'icon' => 'twitter',    'fullname' => 'Twitter' );
			$networks[] = array( 'url' => $ss_settings['vimeo_link'],        'icon' => 'vimeo',      'fullname' => 'Vimeo' );
			$networks[] = array( 'url' => $ss_settings['vkontakte_link'],         'icon' => 'vkontakte',  'fullname' => 'Vkontakte' );
			$networks[] = array( 'url' => $ss_settings['youtube_link'],      'icon' => 'youtube',    'fullname' => 'YouTube' );

			return $networks;
		}

		/**
		 * Build an array of the available/enabled networks for social sharing.
		 */
		function get_social_shares() {
			global $ss_framework, $ss_settings;

			$nets   = $ss_settings['share_networks'];

			$networks = null;

			if ( $nets['fb'] == 1 ) {
				$networks['facebook'] = array(
					'icon'      => 'facebook',
					'fullname'  => 'Facebook',
					'url'       => 'http://www.facebook.com/sharer.php?u=' . get_permalink() . '&amp;title=' . urlencode( html_entity_decode( get_the_title(),ENT_QUOTES,'UTF-8' ) )
				);
			}

			if ( $nets['tw'] == 1 ) {
				$networks['twitter'] = array(
					'icon'      => 'twitter',
					'fullname'  => 'Twitter',
					'url'       => 'http://twitter.com/home/?status=' . urlencode( html_entity_decode( strip_tags( get_the_title() ),ENT_QUOTES,'UTF-8' ) ) . ' - ' . get_permalink()
				);

				$twittername = $this->get_tw_username();

				if ( $twittername != '' ) {
					$network['twitter']['username'] = $twittername;
					$networks['twitter']['url'] .= ' via @' . $twittername;
				}
			}

			if ( $nets['rd'] == 1 ) {
				$networks['reddit'] = array(
					'icon'      => 'reddit',
					'fullname'  => 'Reddit',
					'url'       => 'http://reddit.com/submit?url=' .get_permalink() . '&amp;title=' . urlencode( html_entity_decode( strip_tags( get_the_title() ),ENT_QUOTES,'UTF-8' ) )
				);
			}

			if ( $nets['li'] == 1 ) {
				$networks['linkedin'] = array(
					'icon'      => 'linkedin',
					'fullname'  => 'LinkedIn',
					'url'       => 'http://linkedin.com/shareArticle?mini=true&amp;url=' .get_permalink() . '&amp;title=' . urlencode( html_entity_decode( strip_tags( get_the_title() ),ENT_QUOTES,'UTF-8' ) )
				);
			}

			if ( $nets['gp'] == 1 ) {
				$networks['googleplus'] = array(
					'icon'      => 'googleplus',
					'fullname'  => 'Google+',
					'url'       => 'https://plus.google.com/share?url=' . get_permalink()
				);
			}

			if ( $nets['tu'] == 1 ) {
				$networks['tumblr'] = array(
					'icon'      => 'tumblr',
					'fullname'  => 'Tumblr',
					'url'       =>  'http://www.tumblr.com/share/link?url=' . urlencode( get_permalink() ) . '&amp;name=' . urlencode( html_entity_decode( strip_tags( get_the_title() ),ENT_QUOTES,'UTF-8' ) ) . "&amp;description=" . urlencode( get_the_excerpt() )
				);
			}

			if ( $nets['pi'] == 1 ) {
				$networks['pinterest'] = array(
					'icon'      => 'pinterest',
					'fullname'  => 'Pinterest',
					'url'       => 'http://pinterest.com/pin/create/button/?url=' . get_permalink()
				);
			}

			if ( $nets['em'] == 1 ) {
				$networks['email'] = array(
					'icon'      => 'envelope',
					'fullname'  => 'Email',
					'url'       => 'mailto:?subject=' . urlencode( html_entity_decode( strip_tags( get_the_title() ),ENT_QUOTES,'UTF-8' ) ) . '&amp;body=' . get_permalink()
				);
			}
			return $networks;
		}

		/**
		 * Properly parses the twitter URL if set
		 */
		function get_tw_username() {
			global $ss_settings;
			$twittername  = '';
			$twitter_link = $ss_settings['twitter_link'];

			if ( $twitter_link != "" ) {
				$twitter_link = explode( '/', rtrim( $twitter_link, '/' ) );
				$twittername = end( $twitter_link );
			}

			return $twittername;
		}


		/**
		 * Create the social sharing buttons
		 */
		function social_sharing() {
			global $ss_framework, $ss_settings;

			// The base class for icons that will be used
			$baseclass  = 'icon el-icon-';

			// Don't show by default
			$show = false;

			// Button class
			if ( isset( $ss_settings['social_sharing_button_class'] ) && ! empty( $ss_settings['social_sharing_button_class'] ) ) {
				$button_color = $ss_settings['social_sharing_button_class'];
			} else {
				$button_color = 'default';
			}

			// Button Text
			$text = $ss_settings['social_sharing_text'];

			// Build the content
			$content  = '<div class="' . $ss_framework->button_group_classes( 'small', null, 'social-share' ) . '">';
			$content .= '<button class="' . $ss_framework->button_classes( $button_color, null, null, 'social-share-main' ) . '">' . $text . '</button>';

			// An array of the available networks
			$networks = $this->get_social_shares();
			$networks = is_null( $networks ) ? array() : $networks;

			foreach ( $networks as $network ) {
				$content .= '<a class="' . $ss_framework->button_classes( $button_color, null, null, 'social-link' ) . '" href="' . $network['url'] . '" target="_blank">';
				$content .= '<i class="' . $baseclass . $network['icon'] . '"></i>';
				$content .= '</a>';
			}
			$content .= '</div>';

			// If at least ONE social share option is enabled then echo the content
			if ( ! empty( $networks ) ) {
				echo $content;
			}
		}
	}
}
