<?php


if ( ! class_exists( 'Shoestrap_Layout' ) ) {

	/**
	* The "Layout Module"
	*/
	class Shoestrap_Layout {

		function __construct() {
			global $ss_settings;

			add_filter( 'shoestrap_section_class_wrapper',   array( $this, 'apply_layout_classes_wrapper'   )     );
			add_filter( 'shoestrap_section_class_main',      array( $this, 'apply_layout_classes_main'      )     );
			add_filter( 'shoestrap_section_class_primary',   array( $this, 'apply_layout_classes_primary'   )     );
			add_filter( 'shoestrap_section_class_secondary', array( $this, 'apply_layout_classes_secondary' )     );
			add_filter( 'shoestrap_container_class',         array( $this, 'container_class'                )     );
			add_filter( 'body_class',                        array( $this, 'layout_body_class'              )     );
			add_filter( 'shoestrap_navbar_container_class',  array( $this, 'navbar_container_class'         )     );
			add_action( 'template_redirect',                 array( $this, 'content_width'                  )     );

			if ( isset( $ss_settings['body_margin_top'] ) && ( $ss_settings['body_margin_top'] > 0 || $ss_settings['body_margin_bottom'] > 0 ) ) {
				add_action( 'wp_enqueue_scripts', array( $this, 'body_margin' ), 101 );
			}

			add_action( 'get_header',             array( $this, 'boxed_container_div_open' ), 1 );
			add_action( 'shoestrap_pre_footer',   array( $this, 'boxed_container_div_open' ), 1 );
			add_action( 'shoestrap_do_navbar',    array( $this, 'boxed_container_div_close' ), 99 );
			add_action( 'shoestrap_after_footer', array( $this, 'boxed_container_div_close' ), 899 );
			add_action( 'wp',                     array( $this, 'control_primary_sidebar_display' ) );
			add_action( 'wp',                     array( $this, 'control_secondary_sidebar_display' ) );

			 // Modify the appearance of widgets based on user selection.
			if ( isset( $ss_settings['widgets_mode'] ) ) {
				$widgets_mode = $ss_settings['widgets_mode'];
				if ( $widgets_mode == 0 || $widgets_mode == 1 ) {
					add_filter( 'shoestrap_widgets_class',        array( $this, 'alter_widgets_class'        ) );
					add_filter( 'shoestrap_widgets_before_title', array( $this, 'alter_widgets_before_title' ) );
					add_filter( 'shoestrap_widgets_after_title',  array( $this, 'alter_widgets_after_title'  ) );
				}
			}

			add_action( 'wp_head', array( $this, 'static_meta'      ) );
		}

		/*
		 * Get the layout value, but only set it once!
		 */
		static public function get_layout() {
			global $shoestrap_layout;
			global $ss_settings;

			if ( ! isset( $shoestrap_layout ) ) {
				do_action( 'shoestrap_layout_modifier' );

				$shoestrap_layout = intval( $ss_settings['layout'] );

				// Looking for a per-page template ?
				if ( is_page() && is_page_template() ) {
					if ( is_page_template( 'template-0.php' ) ) {
						$shoestrap_layout = 0;
					} elseif ( is_page_template( 'template-1.php' ) ) {
						$shoestrap_layout = 1;
					} elseif ( is_page_template( 'template-2.php' ) ) {
						$shoestrap_layout = 2;
					} elseif ( is_page_template( 'template-3.php' ) ) {
						$shoestrap_layout = 3;
					} elseif ( is_page_template( 'template-4.php' ) ) {
						$shoestrap_layout = 4;
					} elseif ( is_page_template( 'template-5.php' ) ) {
						$shoestrap_layout = 5;
					}
				}

				if ( $ss_settings['cpt_layout_toggle'] == 1 ) {
					if ( ! is_page_template() ) {
						$post_types = get_post_types( array( 'public' => true ), 'names' );
						foreach ( $post_types as $post_type ) {
							$shoestrap_layout = ( is_singular( $post_type ) ) ? intval( $ss_settings[$post_type . '_layout'] ) : $shoestrap_layout;
						}
					}
				}

				if ( ! is_active_sidebar( 'sidebar-secondary' ) && is_active_sidebar( 'sidebar-primary' ) && $shoestrap_layout == 5 ) {
					$shoestrap_layout = 3;
				}
			}
			return $shoestrap_layout;
		}

		/*
		 *Override the layout value globally
		 */
		function set_layout( $val ) {
			global $shoestrap_layout, $redux;
			$shoestrap_layout = intval( $val );
		}

		/*
		 * Calculates the classes of the main area, main sidebar and secondary sidebar
		 */
		public static function section_class_ext( $target, $echo = false ) {
			global $redux, $ss_framework;
			global $ss_settings;

			$layout = self::get_layout();
			$first  = intval( $ss_settings['layout_primary_width'] );
			$second = intval( $ss_settings['layout_secondary_width'] );

			// disable responsiveness if layout is set to non-responsive
			$width = ( $ss_settings['site_style'] == 'static' ) ? 'mobile' : 'tablet';

			// Set some defaults so that we can change them depending on the selected template
			$main       = 12;
			$primary    = NULL;
			$secondary  = NULL;
			$wrapper    = 12;

			if ( shoestrap_display_primary_sidebar() && shoestrap_display_secondary_sidebar() ) {

				if ( $layout == 5 ) {
					$main       = 12 - floor( ( 12 * $first ) / ( 12 - $second ) );
					$primary    = floor( ( 12 * $first ) / ( 12 - $second ) );
					$secondary  = $second;
					$wrapper    = 12 - $second;
				} elseif ( $layout >= 3 ) {
					$main       = 12 - $first - $second;
					$primary    = $first;
					$secondary  = $second;
				} elseif ( $layout >= 1 ) {
					$main       = 12 - $first;
					$primary    = $first;
					$secondary  = $second;
				}

			} elseif ( shoestrap_display_primary_sidebar() && ! shoestrap_display_secondary_sidebar() ) {

				if ( $layout >= 1 ) {
					$main       = 12 - $first;
					$primary    = $first;
				}

			} elseif ( ! shoestrap_display_primary_sidebar() && shoestrap_display_secondary_sidebar() ) {

				if ( $layout >= 3 ) {
					$main       = 12 - $second;
					$secondary  = $second;
				}
			}

			if ( $target == 'primary' ) {
				$class = $ss_framework->column_classes( array( $width => $primary ), 'strimg' );
			} elseif ( $target == 'secondary' ) {
				$class = $ss_framework->column_classes( array( $width => $secondary ), 'strimg' );
			} elseif ( $target == 'wrapper' ) {
				$class = $ss_framework->column_classes( array( $width => $wrapper ), 'strimg' );
			} else {
				$class = $ss_framework->column_classes( array( $width => $main ), 'strimg' );
			}

			if ( $echo ) {
				echo $class;
			} else {
				return $class;
			}
		}

		/**
		 * Helper function for layout classes
		 */
		function apply_layout_classes_wrapper() {
			return self::section_class_ext( 'wrapper' );
		}

		/**
		 * Helper function for layout classes
		 */
		function apply_layout_classes_main() {
			return self::section_class_ext( 'main' );
		}

		/**
		 * Helper function for layout classes
		 */
		function apply_layout_classes_primary() {
			return self::section_class_ext( 'primary' );
		}

		/**
		 * Helper function for layout classes
		 */
		function apply_layout_classes_secondary() {
			return self::section_class_ext( 'secondary' );
		}

		/**
		 * Add and remove body_class() classes to accomodate layouts
		 */
		function layout_body_class( $classes ) {
			global $ss_settings;

			$layout     = self::get_layout();
			$site_style = $ss_settings['site_style'];
			$margin     = $ss_settings['navbar_margin_top'];

			if ( $layout == 2 || $layout == 3 || $layout == 5 ) {
				$classes[] = 'main-float-right';
			}

			if ( $site_style == 'boxed' ) {
				$classes[] = 'boxed-style';
			} elseif ( $site_style == 'fluid' ) {
				$classes[] = 'fluid';
			}

			return $classes;
		}

		/*
		 * Return the container class
		 */
		public static function container_class() {
			global $ss_settings;
			$class    = $ss_settings['site_style'] != 'fluid' ? 'container' : 'fluid';

			// override if navbar module exists and 'navbar-toggle' is set to left.
			if ( class_exists( 'Shoestrap_Menus' ) ) {
				if ( $ss_settings['navbar_toggle'] == 'left' ) {
					$class = 'fluid';
				}
			}

			return $class;
		}

		/*
		 * Return the container class
		 */
		function navbar_container_class() {
			global $ss_settings;

			$site_style = $ss_settings['site_style'];
			$toggle     = $ss_settings['navbar_toggle'];

			if ( $toggle == 'full' ) {
				$class = 'fluid';
			} else {
				$class = ( $site_style != 'fluid' ) ? 'container' : 'fluid';
			}

			// override if navbar module exists and 'navbar-toggle' is set to left.
			if ( class_exists( 'ShoestrapMenus' ) ) {
				if ( $ss_settings['navbar_toggle'] == 'left' ) {
					$class = 'fluid';
				}
			}

			return $class;
		}

		/*
		 * Calculate the width of the content area in pixels.
		 */
		public static function content_width_px( $echo = false ) {
			global $redux;
			global $ss_settings;

			$layout = self::get_layout();

			$container  = filter_var( $ss_settings['screen_large_desktop'], FILTER_SANITIZE_NUMBER_INT );
			$gutter     = filter_var( $ss_settings['layout_gutter'], FILTER_SANITIZE_NUMBER_INT );

			$main_span  = filter_var( self::section_class_ext( 'main', false ), FILTER_SANITIZE_NUMBER_INT );
			$main_span  = str_replace( '-' , '', $main_span );

			// If the layout is #5, override the default function and calculate the span width of the main area again.
			if ( is_active_sidebar( 'sidebar-secondary' ) && is_active_sidebar( 'sidebar-primary' ) && $layout == 5 ) {
				$main_span = 12 - intval( $ss_settings['layout_primary_width'] ) - intval( $ss_settings['layout_secondary_width'] );
			}

			if ( is_front_page() && $ss_settings['layout_sidebar_on_front'] != 1 ) {
				$main_span = 12;
			}

			$width = $container * ( $main_span / 12 ) - $gutter;

			// Width should be an integer since we're talking pixels, round up!.
			$width = round( $width );

			if ( $echo ) {
				echo $width;
			} else {
				return $width;
			}
		}

		/*
		 * Set the content width
		 */
		public static function content_width() {
			global $content_width;
			$content_width = self::content_width_px();
		}

		/*
		 * Body Margins
		 */
		function body_margin() {
			global $ss_settings;

			$body_margin_top    = $ss_settings['body_margin_top'];
			$body_margin_bottom = $ss_settings['body_margin_bottom'];

			$style = 'body { margin-top:'. $body_margin_top .'px; margin-bottom:'. $body_margin_bottom .'px; }';

			wp_add_inline_style( 'shoestrap_css', $style );
		}

		/**
		 * Add a wrapper div when in "boxed" mode to disallow full-width elements
		 */
		function boxed_container_div_open() {
			global $ss_settings;

			if ( $ss_settings['site_style'] == 'boxed' ) echo '<div class="container boxed-container">';
		}

		/**
		 * Close the wrapper div that the 'boxed_container_div_open' opens when in "boxed" mode.
		 */
		function boxed_container_div_close() {
			global $ss_settings;

			if ( $ss_settings['site_style'] == 'boxed' ) echo '</div>';
		}

		/**
		 * Modify the rules for showing up or hiding the primary sidebar
		 */
		function control_primary_sidebar_display() {
			global $ss_settings;

			$layout_sidebar_on_front = $ss_settings['layout_sidebar_on_front'];

			if ( self::get_layout() == 0 ) {
				add_filter( 'shoestrap_display_primary_sidebar', '__return_false' );
			}

			if ( is_front_page() && $layout_sidebar_on_front == 1 && self::get_layout() != 0 ) {
				add_filter( 'shoestrap_display_primary_sidebar', '__return_true' );
			}

			if ( ( ! is_front_page() || ( is_front_page() && $layout_sidebar_on_front == 1 ) ) && self::get_layout() != 0 ) {
				add_filter( 'shoestrap_display_primary_sidebar', '__return_true' );
			}
		}

		/**
		 * Modify the rules for showing up or hiding the secondary sidebar
		 */
		function control_secondary_sidebar_display() {
			global $ss_settings;

			$layout_sidebar_on_front = $ss_settings['layout_sidebar_on_front'];

			if ( self::get_layout() < 3 ) {
				add_filter( 'shoestrap_display_secondary_sidebar', '__return_false' );
			}

			if ( ( ! is_front_page() && shoestrap_display_secondary_sidebar() ) || ( is_front_page() && $layout_sidebar_on_front == 1 && self::get_layout() >= 3 ) ) {
				add_filter( 'shoestrap_display_secondary_sidebar', '__return_true' );
			}
		}

		/**
		 * Get the widget class
		 */
		function alter_widgets_class() {
			global $ss_settings;
			return $ss_settings['widgets_mode'] == 0 ? 'panel panel-default' : 'well';
		}

		/**
		 * Widgets 'before_title' modifying based on widgets mode.
		 */
		function alter_widgets_before_title() {
			global $ss_settings;
			return $ss_settings['widgets_mode'] == 0 ? '<div class="panel-heading">' : '<h3 class="widget-title">';
		}

		/**
		 * Widgets 'after_title' modifying based on widgets mode.
		 */
		function alter_widgets_after_title() {
			global $ss_settings;
			return $ss_settings['widgets_mode'] == 0 ? '</div><div class="panel-body">' : '</h3>';
		}

		/**
		 * Add some metadata when users have selected a static mode for their layout (not responsive).
		 */
		function static_meta() {
			global $ss_settings;

			if ( $ss_settings['site_style'] != 'static' ) : ?>
				<meta name="viewport" content="width=device-width, initial-scale=1">
				<meta name="mobile-web-app-capable" content="yes">
				<meta name="apple-mobile-web-app-capable" content="yes">
				<meta name="apple-mobile-web-app-status-bar-style" content="black">
				<?php
			endif;
		}

		function include_wrapper() {
			global $shoestrap_layout;

			if ( $shoestrap_layout == 5 ) {
				return true;
			} else {
				return false;
			}
		}
	}
}
