<?php


if ( ! class_exists( 'Shoestrap_Social' ) ) {
	/**
	* The "Social" modue
	*/
	class Shoestrap_Social {

		function __construct() {
			global $ss_settings;

			add_filter( 'redux/options/' . SHOESTRAP_OPT_NAME . '/sections', array( $this, 'options' ), 140 );

			$social_sharing_location = $ss_settings['social_sharing_location'];

			// Social Share select
			$social_sharing_single_page = $ss_settings['social_sharing_single_page'];

			// Conditions for showing content in posts archives
			if ( $ss_settings['social_sharing_archives'] == 1 ) {
				add_action( 'shoestrap_entry_footer', array( $this, 'social_sharing' ), 5 );
			}

			// Conditions for showing content in single posts
			if ( $ss_settings['social_sharing_single_post'] == 1 ) {
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
			if ( $ss_settings['social_sharing_single_page'] == 1 ) {
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

		/*
		 * The social core options for the Shoestrap theme
		 */
		function options( $sections ) {

			$section = array(
				'title'     => __( 'Social', 'shoestrap' ),
				'icon'      => 'el-icon-group',
			);

			$fields[] = array(
				'id'        => 'social_sharing_help_1',
				'title'     => __( 'Social Sharing', 'shoestrap' ),
				'type'      => 'info'
			);

			$fields[] = array(
				'title'     => __( 'Button Text', 'shoestrap' ),
				'desc'      => __( 'Select the text for the social sharing button.', 'shoestrap' ),
				'id'        => 'social_sharing_text',
				'default'   => 'Share',
				'type'      => 'text'
			);

			$fields[] = array(
				'title'     => __( 'Button Location', 'shoestrap' ),
				'desc'      => __( 'Select between NONE, TOP, BOTTOM & BOTH. For archives, "BOTH" fallbacks to "BOTTOM" only.', 'shoestrap' ),
				'id'        => 'social_sharing_location',
				'default'   => 'top',
				'type'      => 'select',
				'options'   => array(
					'none'    =>'None',
					'top'     =>'Top',
					'bottom'  =>'Bottom',
					'both'    =>'Both',
				)
			);

			$fields[] = array(
				'title'     => __( 'Button Styling', 'shoestrap' ),
				'desc'      => __( 'Select between standard Bootstrap\'s button classes', 'shoestrap' ),
				'id'        => 'social_sharing_button_class',
				'default'   => 'default',
				'type'      => 'select',
				'options'   => array(
					'default'    => 'Default',
					'primary'    => 'Primary',
					'success'    => 'Success',
					'warning'    => 'Warning',
					'danger'     => 'Danger',
				)
			);

			$fields[] = array(
				'title'     => __( 'Show in Posts Archives', 'shoestrap' ),
				'desc'      => __( 'Show the sharing button in posts archives.', 'shoestrap' ),
				'id'        => 'social_sharing_archives',
				'default'   => '',
				'type'      => 'switch'
			);

			$fields[] = array(
				'title'     => __( 'Show in Single Post', 'shoestrap' ),
				'desc'      => __( 'Show the sharing button in single post.', 'shoestrap' ),
				'id'        => 'social_sharing_single_post',
				'default'   => '1',
				'type'      => 'switch'
			);

			$fields[] = array(
				'title'     => __( 'Show in Single Page', 'shoestrap' ),
				'desc'      => __( 'Show the sharing button in single page.', 'shoestrap' ),
				'id'        => 'social_sharing_single_page',
				'default'   => '1',
				'type'      => 'switch'
			);

			$fields[] = array(
				'id'        => 'share_networks',
				'type'      => 'checkbox',
				'title'     => __( 'Social Share Networks', 'shoestrap' ),
				'desc'      => __( 'Select the Social Networks you want to enable for social shares', 'shoestrap' ),

				'options'   => array(
					'fb'    => __( 'Facebook', 'shoestrap' ),
					'gp'    => __( 'Google+', 'shoestrap' ),
					'li'    => __( 'LinkedIn', 'shoestrap' ),
					'pi'    => __( 'Pinterest', 'shoestrap' ),
					'rd'    => __( 'Reddit', 'shoestrap' ),
					'tu'    => __( 'Tumblr', 'shoestrap' ),
					'tw'    => __( 'Twitter', 'shoestrap' ),
					'em'    => __( 'Email', 'shoestrap' ),
				)
			);

			$fields[] = array(
				'id'        => 'social_sharing_help_3',
				'title'     => __( 'Social Links used in Menus && Footer', 'shoestrap' ),
				'type'      => 'info'
			);

			$fields[] = array(
				'title'     => __( 'Blogger', 'shoestrap' ),
				'desc'      => __( 'Provide the link you desire and the Blogger icon will appear. To remove it, just leave it blank.', 'shoestrap' ),
				'id'        => 'blogger_link',
				'validate'  => 'url',
				'default'   => '',
				'type'      => 'text'
			);

			$fields[] = array(
				'title'     => __( 'DeviantART', 'shoestrap' ),
				'desc'      => __( 'Provide the link you desire and the DeviantART icon will appear. To remove it, just leave it blank.', 'shoestrap' ),
				'id'        => 'deviantart_link',
				'validate'  => 'url',
				'default'   => '',
				'type'      => 'text'
			);

			$fields[] = array(
				'title'     => __( 'Digg', 'shoestrap' ),
				'desc'      => __( 'Provide the link you desire and the Digg icon will appear. To remove it, just leave it blank.', 'shoestrap' ),
				'id'        => 'digg_link',
				'validate'  => 'url',
				'default'   => '',
				'type'      => 'text'
			);

			$fields[] = array(
				'title'     => __( 'Dribbble', 'shoestrap' ),
				'desc'      => __( 'Provide the link you desire and the Dribbble icon will appear. To remove it, just leave it blank.', 'shoestrap' ),
				'id'        => 'dribbble_link',
				'validate'  => 'url',
				'default'   => '',
				'type'      => 'text'
			);

			$fields[] = array(
				'title'     => __( 'Facebook', 'shoestrap' ),
				'desc'      => __( 'Provide the link you desire and the Facebook icon will appear. To remove it, just leave it blank.', 'shoestrap' ),
				'id'        => 'facebook_link',
				'validate'  => 'url',
				'default'   => '',
				'type'      => 'text'
			);

			$fields[] = array(
				'title'     => __( 'Flickr', 'shoestrap' ),
				'desc'      => __( 'Provide the link you desire and the Flickr icon will appear. To remove it, just leave it blank.', 'shoestrap' ),
				'id'        => 'flickr_link',
				'validate'  => 'url',
				'default'   => '',
				'type'      => 'text'
			);

			$fields[] = array(
				'title'     => __( 'GitHub', 'shoestrap' ),
				'desc'      => __( 'Provide the link you desire and the GitHub icon will appear. To remove it, just leave it blank.', 'shoestrap' ),
				'id'        => 'github_link',
				'validate'  => 'url',
				'default'   => '',
				'type'      => 'text'
			);

			$fields[] = array(
				'title'     => __( 'Google+', 'shoestrap' ),
				'desc'      => __( 'Provide the link you desire and the Google+ icon will appear. To remove it, just leave it blank.', 'shoestrap' ),
				'id'        => 'google_plus_link',
				'validate'  => 'url',
				'default'   => '',
				'type'      => 'text'
			);

			$fields[] = array(
				'title'     => __( 'Instagram', 'shoestrap' ),
				'desc'      => __( 'Provide the link you desire and the Instagram icon will appear. To remove it, just leave it blank.', 'shoestrap' ),
				'id'        => 'instagram_link',
				'validate'  => 'url',
				'default'   => '',
				'type'      => 'text'
			);

			$fields[] = array(
				'title'     => __( 'LinkedIn', 'shoestrap' ),
				'desc'      => __( 'Provide the link you desire and the LinkedIn icon will appear. To remove it, just leave it blank.', 'shoestrap' ),
				'id'        => 'linkedin_link',
				'validate'  => 'url',
				'default'   => '',
				'type'      => 'text'
			);

			$fields[] = array(
				'title'     => __( 'MySpace', 'shoestrap' ),
				'desc'      => __( 'Provide the link you desire and the MySpace icon will appear. To remove it, just leave it blank.', 'shoestrap' ),
				'id'        => 'myspace_link',
				'validate'  => 'url',
				'default'   => '',
				'type'      => 'text'
			);

			$fields[] = array(
				'title'     => __( 'Pinterest', 'shoestrap' ),
				'desc'      => __( 'Provide the link you desire and the Pinterest icon will appear. To remove it, just leave it blank.', 'shoestrap' ),
				'id'        => 'pinterest_link',
				'validate'  => 'url',
				'default'   => '',
				'type'      => 'text'
			);

			$fields[] = array(
				'title'     => __( 'Reddit', 'shoestrap' ),
				'desc'      => __( 'Provide the link you desire and the Reddit icon will appear. To remove it, just leave it blank.', 'shoestrap' ),
				'id'        => 'reddit_link',
				'validate'  => 'url',
				'default'   => '',
				'type'      => 'text'
			);

			$fields[] = array(
				'title'     => __( 'RSS', 'shoestrap' ),
				'desc'      => __( 'Provide the link you desire and the RSS icon will appear. To remove it, just leave it blank.', 'shoestrap' ),
				'id'        => 'rss_link',
				'validate'  => 'url',
				'default'   => '',
				'type'      => 'text'
			);

			$fields[] = array(
				'title'     => __( 'Skype', 'shoestrap' ),
				'desc'      => __( 'Provide the link you desire and the Skype icon will appear. To remove it, just leave it blank.', 'shoestrap' ),
				'id'        => 'skype_link',
				'validate'  => 'url',
				'default'   => '',
				'type'      => 'text'
			);

			$fields[] = array(
				'title'     => __( 'SoundCloud', 'shoestrap' ),
				'desc'      => __( 'Provide the link you desire and the SoundCloud icon will appear. To remove it, just leave it blank.', 'shoestrap' ),
				'id'        => 'soundcloud_link',
				'validate'  => 'url',
				'default'   => '',
				'type'      => 'text'
			);

			$fields[] = array(
				'title'     => __( 'Tumblr', 'shoestrap' ),
				'desc'      => __( 'Provide the link you desire and the Tumblr icon will appear. To remove it, just leave it blank.', 'shoestrap' ),
				'id'        => 'tumblr_link',
				'validate'  => 'url',
				'default'   => '',
				'type'      => 'text'
			);

			$fields[] = array(
				'title'     => __( 'Twitter', 'shoestrap' ),
				'desc'      => __( 'Provide the link you desire and the Twitter icon will appear. To remove it, just leave it blank.', 'shoestrap' ),
				'id'        => 'twitter_link',
				'validate'  => 'url',
				'default'   => '',
				'type'      => 'text'
			);

			$fields[] = array(
				'title'     => __( 'Vimeo', 'shoestrap' ),
				'desc'      => __( 'Provide the link you desire and the Vimeo icon will appear. To remove it, just leave it blank.', 'shoestrap' ),
				'id'        => 'vimeo_link',
				'validate'  => 'url',
				'default'   => '',
				'type'      => 'text'
			);


			$fields[] = array(
				'title'     => 'Vkontakte',
				'desc'      => 'Provide the link you desire and the Vkontakte icon will appear. To remove it, just leave it blank.',
				'id'        => 'vkontakte_link',
				'validate'  => 'url',
				'default'   => '',
				'type'      => 'text'
			);

			$fields[] = array(
				'title'     => 'YouTube Link',
				'desc'      => 'Provide the link you desire and the YouTube icon will appear. To remove it, just leave it blank.',
				'id'        => 'youtube_link',
				'validate'  => 'url',
				'default'   => '',
				'type'      => 'text'
			);

			$section['fields'] = $fields;

			$section = apply_filters( 'shoestrap_module_socials_options_modifier', $section );

			$sections[] = $section;
			return $sections;

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

			if ( isset( $nets['fb'] ) ) {
				$networks['facebook'] = array(
					'icon'      => 'facebook',
					'fullname'  => 'Facebook',
					'url'       => 'http://www.facebook.com/sharer.php?u=' . get_permalink() . '&amp;title=' . get_the_title()
				);
			}

			if ( isset( $nets['tw'] ) ) {
				$networks['twitter'] = array(
					'icon'      => 'twitter',
					'fullname'  => 'Twitter',
					'url'       => 'http://twitter.com/home/?status=' . get_the_title() . ' - ' . get_permalink()
				);

				$twittername = $this->get_tw_username();

				if ( $twittername != '' ) {
					$network['twitter']['username'] = $twittername;
					$networks['twitter']['url'] .= ' via @' . $twittername;
				}
			}

			if ( isset( $nets['rd'] ) ) {
				$networks['reddit'] = array(
					'icon'      => 'reddit',
					'fullname'  => 'Reddit',
					'url'       => 'http://reddit.com/submit?url=' .get_permalink() . '&amp;title=' . get_the_title()
				);
			}

			if ( isset( $nets['li'] ) ) {
				$networks['linkedin'] = array(
					'icon'      => 'linkedin',
					'fullname'  => 'LinkedIn',
					'url'       => 'http://linkedin.com/shareArticle?mini=true&amp;url=' .get_permalink() . '&amp;title=' . get_the_title()
				);
			}

			if ( isset( $nets['gp'] ) ) {
				$networks['googleplus'] = array(
					'icon'      => 'googleplus',
					'fullname'  => 'Google+',
					'url'       => 'https://plus.google.com/share?url=' . get_permalink()
				);
			}

			if ( isset( $nets['tu'] ) ) {
				$networks['tumblr'] = array(
					'icon'      => 'tumblr',
					'fullname'  => 'Tumblr',
					'url'       =>  'http://www.tumblr.com/share/link?url=' . urlencode( get_permalink() ) . '&amp;name=' . urlencode( get_the_title() ) . "&amp;description=" . urlencode( get_the_excerpt() )
				);
			}

			if ( isset( $nets['pi'] ) ) {
				$networks['pinterest'] = array(
					'icon'      => 'pinterest',
					'fullname'  => 'Pinterest',
					'url'       => 'http://pinterest.com/pin/create/button/?url=' . get_permalink()
				);
			}

			if ( isset( $nets['em'] ) ) {
				$networks['email'] = array(
					'icon'      => 'envelope',
					'fullname'  => 'Email',
					'url'       => 'mailto:?subject=' .get_the_title() . '&amp;body=' . get_permalink()
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
