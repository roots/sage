<?php


if( ! class_exists( 'Shoestrap_Footer' ) ) {
	/**
	* Build the Shoestrap Footer module class.
	*/
	class Shoestrap_Footer {

		function __construct() {
			add_action( 'wp_enqueue_scripts',    array( $this, 'css' ), 101 );
			add_action( 'shoestrap_footer_html', array( $this, 'html' ) );
			add_action( 'widgets_init',          array( $this, 'widgets_init' ) );
		}

		/**
		 * Register sidebars and widgets
		 */
		function widgets_init() {
			$class        = apply_filters( 'shoestrap_widgets_class', '' );
			$before_title = apply_filters( 'shoestrap_widgets_before_title', '<h3 class="widget-title">' );
			$after_title  = apply_filters( 'shoestrap_widgets_after_title', '</h3>' );

			// Sidebars
			register_sidebar( array(
				'name'          => __( 'Primary Sidebar', 'shoestrap' ),
				'id'            => 'sidebar-primary',
				'before_widget' => '<section id="%1$s" class="' . $class . ' widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => $before_title,
				'after_title'   => $after_title,
			));

			register_sidebar( array(
				'name'          => __( 'Secondary Sidebar', 'shoestrap' ),
				'id'            => 'sidebar-secondary',
				'before_widget' => '<section id="%1$s" class="' . $class . ' widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => $before_title,
				'after_title'   => $after_title,
			));

			register_sidebar( array(
				'name'          => __( 'Footer Widget Area 1', 'shoestrap' ),
				'id'            => 'sidebar-footer-1',
				'before_widget' => '<section id="%1$s" class="' . $class . ' widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => $before_title,
				'after_title'   => $after_title,
			));

			register_sidebar( array(
				'name'          => __( 'Footer Widget Area 2', 'shoestrap' ),
				'id'            => 'sidebar-footer-2',
				'before_widget' => '<section id="%1$s" class="' . $class . ' widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => $before_title,
				'after_title'   => $after_title,
			));

			register_sidebar( array(
				'name'          => __( 'Footer Widget Area 3', 'shoestrap' ),
				'id'            => 'sidebar-footer-3',
				'before_widget' => '<section id="%1$s" class="' . $class . ' widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => $before_title,
				'after_title'   => $after_title,
			));

			register_sidebar( array(
				'name'          => __( 'Footer Widget Area 4', 'shoestrap' ),
				'id'            => 'sidebar-footer-4',
				'before_widget' => '<section id="%1$s" class="' . $class . ' widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => $before_title,
				'after_title'   => $after_title,
			));
		}

		/**
		 * If the options selected require the insertion of some custom CSS to the document head, generate that CSS here
		 */

		function css() {
			global $ss_settings;

			$bg         = $ss_settings['footer_background'];
			$cl         = $ss_settings['footer_color'];
			$cl_brand   = $ss_settings['color_brand_primary'];
			$opacity    = ( intval( $ss_settings['footer_opacity'] ) ) / 100;
			$rgb        = Shoestrap_Color::get_rgb( $bg, true );
			$border     = $ss_settings['footer_border'];
			$top_margin = $ss_settings['footer_top_margin'];

			$container_margin = $top_margin * 0.381966011;

			$style = 'footer.content-info {';
				$style .= 'color:' . $cl . ';';

				$style .= ( $opacity != 1 && $opacity != "" ) ? 'background: rgba(' . $rgb . ',' . $opacity . ');' : 'background:' . $bg . ';';
				$style .= ( ! empty($border) && $border['border-top'] > 0 && ! empty($border['border-color']) ) ? 'border-top:' . $border['border-top'] . ' ' . $border['border-style'] . ' ' . $border['border-color'] . ';' : '';
				if ( isset( $ss_settings['layout_gutter'] ) ) {
					$style .= 'padding-top:' . $ss_settings['layout_gutter'] / 2 . 'px;';
					$style .= 'padding-bottom:' . $ss_settings['layout_gutter'] / 2 . 'px;';
				}

				$style .= ( ! empty($top_margin) ) ? 'margin-top:'. $top_margin .'px;' : '';
			$style .= '}';

			$style .= 'footer div.container { margin-top:'. $container_margin .'px; }';
			$style .= '#copyright-bar { line-height: 30px; }';
			$style .= '#footer_social_bar { line-height: 30px; font-size: 16px; text-align: right; }';
			$style .= '#footer_social_bar a { margin-left: 9px; padding: 3px; color:' . $cl . '; }';
			$style .= '#footer_social_bar a:hover, #footer_social_bar a:active { color:' . $cl_brand . ' !important; text-decoration:none; }';

			wp_add_inline_style( 'shoestrap_css', $style );
		}

		function html() {
			global $ss_framework, $ss_social, $ss_settings;

			// The blogname for use in the copyright section
			$blog_name  = get_bloginfo( 'name', 'display' );

			// The copyright section contents
			if ( isset( $ss_settings['footer_text'] ) ) {
				$ftext = $ss_settings['footer_text'];
			} else {
				$ftext = '&copy; [year] [sitename]';
			}

			// Replace [year] and [sitename] with meaninful content
			$ftext = str_replace( '[year]', date( 'Y' ), $ftext );
			$ftext = str_replace( '[sitename]', $blog_name, $ftext );

			// Do we want to display social links?
			if ( isset( $ss_settings['footer_social_toggle'] ) && $ss_settings['footer_social_toggle'] == 1 ) {
				$social = true;
			} else {
				$social = false;
			}

			// How many columns wide should social links be?
			if ( $social && isset( $ss_settings['footer_social_width'] ) ) {
				$social_width = $ss_settings['footer_social_width'];
			} else {
				$social_width = false;
			}

			// Social is enabled, we're modifying the width!
			if ( $social_width && $social && intval( $social_width ) > 0 ) {
				$width = 12 - intval( $social_width );
			} else {
				$width = 12;
			}

			if ( isset( $ss_settings['footer_social_new_window_toggle'] ) && ! empty( $ss_settings['footer_social_new_window_toggle'] ) ) {
				$blank = ' target="_blank"';
			} else {
				$blank = null;
			}

			$networks = $ss_social->get_social_links();

			do_action( 'shoestrap_footer_before_copyright' );

			echo '<div id="footer-copyright">';
				echo $ss_framework->open_row( 'div' );
					echo $ss_framework->open_col( 'div', array( 'large' => $width ), 'copyright-bar' ) . $ftext . '</div>';

						if ( $social && ! is_null( $networks ) && count( $networks ) > 0 ) {
							echo $ss_framework->open_col( 'div', array( 'large' => $social_width ), 'footer_social_bar' );

								foreach ( $networks as $network ) {
									// Check if the social network URL has been defined
									if ( isset( $network['url'] ) && ! empty( $network['url'] ) && strlen( $network['url'] ) > 7 ) {
										echo '<a href="' . $network['url'] . '"' . $blank . ' title="' . $network['icon'] . '"><span class="el-icon-' . $network['icon'] . '"></span></a>';
									}
								}

							echo $ss_framework->close_col( 'div' );
						}

					echo $ss_framework->close_col( 'div' );

					echo $ss_framework->clearfix();
				echo $ss_framework->close_row( 'div' );
			echo '</div>';
		}
	}
}
