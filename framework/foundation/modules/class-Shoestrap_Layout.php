<?php


if ( !class_exists( 'Shoestrap_Layout' ) ) {

	/**
	* The "Layout Module"
	*/
	class Shoestrap_Layout {

		function __construct() {
			global $ss_settings;

			add_filter( 'redux/options/' . SHOESTRAP_OPT_NAME . '/sections', array( $this, 'options' ), 55 );
			add_filter( 'shoestrap_section_class_wrapper',   array( $this, 'apply_layout_classes_wrapper'   )     );
			add_filter( 'shoestrap_section_class_main',      array( $this, 'apply_layout_classes_main'      )     );
			add_filter( 'shoestrap_section_class_primary',   array( $this, 'apply_layout_classes_primary'   )     );
			add_filter( 'shoestrap_section_class_secondary', array( $this, 'apply_layout_classes_secondary' )     );
			add_filter( 'shoestrap_navbar_container_class',  array( $this, 'navbar_container_class'         )     );
			add_action( 'template_redirect',                 array( $this, 'content_width'                  )     );

			if ( $ss_settings['body_margin_top'] > 0 || $ss_settings['body_margin_bottom'] > 0 )
				add_action( 'wp_enqueue_scripts',            array( $this, 'body_margin'                   ), 101 );

			add_action( 'wp',                     array( $this, 'control_primary_sidebar_display'   )      );
			add_action( 'wp',                     array( $this, 'control_secondary_sidebar_display' )      );

			 // Modify the appearance of widgets based on user selection.
			$widgets_mode = $ss_settings['widgets_mode'];
			if ( $widgets_mode == 0 || $widgets_mode == 1 ) {
				add_filter( 'shoestrap_widgets_class',        array( $this, 'alter_widgets_class'        ) );
				add_filter( 'shoestrap_widgets_before_title', array( $this, 'alter_widgets_before_title' ) );
				add_filter( 'shoestrap_widgets_after_title',  array( $this, 'alter_widgets_after_title'  ) );
			}
		}

		/*
		 * The layout core options for the Shoestrap theme
		 */
		function options( $sections ) {
			global $ss_settings;

			// Layout Settings
			$section = array( 
				'title'       => __( 'Layout', 'shoestrap' ),
				'icon'        => 'el-icon-screen',
				'description' => '<p>In this area you can select your site\'s layout, the width of your sidebars, as well as other, more advanced options.</p>',
			);

			$fields[] = array( 
				'title'     => __( 'Maximum site width (px)', 'shoestrap' ),
				'desc'      => __( 'Set to maximum for fluid.', 'shoestrap' ),
				'id'        => 'max-width',
				'default'   => 1200,
				'min'       => 480,
				'step'      => 1,
				'max'       => 2560,
				'compiler'  => false,
				'type'      => 'slider'
			);

			$fields[] = array( 
				'title'     => __( 'Layout', 'shoestrap' ),
				'desc'      => __( 'Select main content and sidebar arrangement. Choose between 1, 2 or 3 column layout.', 'shoestrap' ),
				'id'        => 'layout',
				'default'   => 1,
				'type'      => 'image_select',
				'options'   => array( 
					0 => ReduxFramework::$_url . '/assets/img/1c.png',
					1 => ReduxFramework::$_url . '/assets/img/2cr.png',
					2 => ReduxFramework::$_url . '/assets/img/2cl.png',
					3 => ReduxFramework::$_url . '/assets/img/3cl.png',
					4 => ReduxFramework::$_url . '/assets/img/3cr.png',
					5 => ReduxFramework::$_url . '/assets/img/3cm.png',
				)
			);

			$fields[] = array(
				'title'     => __( 'Custom Layouts per Post Type', 'shoestrap' ),
				'desc'      => __( 'Set a default layout for each post type on your site.', 'shoestrap' ),
				'id'        => 'cpt_layout_toggle',
				'default'   => 0,
				'type'      => 'switch',
			);

			$post_types = get_post_types( array( 'public' => true ), 'names' );
			foreach ( $post_types as $post_type ) {
				$fields[] = array(
					'title'     => __( $post_type . ' Layout', 'shoestrap' ),
					'desc'      => __( 'Override your default stylings. Choose between 1, 2 or 3 column layout.', 'shoestrap' ),
					'id'        => $post_type . '_layout',
					'default'   => $ss_settings['layout'],
					'type'      => 'image_select',
					'required'  => array( 'cpt_layout_toggle','=',array( '1' ) ),
					'options'   => array(
						0         => ReduxFramework::$_url . '/assets/img/1c.png',
						1         => ReduxFramework::$_url . '/assets/img/2cr.png',
						2         => ReduxFramework::$_url . '/assets/img/2cl.png',
						3         => ReduxFramework::$_url . '/assets/img/3cl.png',
						4         => ReduxFramework::$_url . '/assets/img/3cr.png',
						5         => ReduxFramework::$_url . '/assets/img/3cm.png',
					)
				);
			}

			$fields[] = array( 
				'title'     => __( 'Primary Sidebar Width', 'shoestrap' ),
				'desc'      => __( 'Select the width of the Primary Sidebar. Please note that the values represent grid columns. The total width of the page is 12 columns, so selecting 4 here will make the primary sidebar to have a width of 1/3 ( 4/12 ) of the total page width.', 'shoestrap' ),
				'id'        => 'layout_primary_width',
				'type'      => 'button_set',
				'options'   => array(
					'1' => '1 Column',
					'2' => '2 Columns',
					'3' => '3 Columns',
					'4' => '4 Columns',
					'5' => '5 Columns'
				),
				'default' => '4'
			);

			$fields[] = array( 
				'title'     => __( 'Secondary Sidebar Width', 'shoestrap' ),
				'desc'      => __( 'Select the width of the Secondary Sidebar. Please note that the values represent grid columns. The total width of the page is 12 columns, so selecting 4 here will make the secondary sidebar to have a width of 1/3 ( 4/12 ) of the total page width.', 'shoestrap' ),
				'id'        => 'layout_secondary_width',
				'type'      => 'button_set',
				'options'   => array(
					'1' => '1 Column',
					'2' => '2 Columns',
					'3' => '3 Columns',
					'4' => '4 Columns',
					'5' => '5 Columns'
				),
				'default' => '3'
			);

			$fields[] = array( 
				'title'     => __( 'Show sidebars on the frontpage', 'shoestrap' ),
				'desc'      => __( 'OFF by default. If you want to display the sidebars in your frontpage, turn this ON.', 'shoestrap' ),
				'id'        => 'layout_sidebar_on_front',
				'default'   => 0,
				'type'      => 'switch'
			);

			$section['fields'] = $fields;

			do_action( 'shoestrap_module_layout_options_modifier' );

			$sections[] = $section;
			return $sections;

		}

		/*
		 * Get the layout value, but only set it once!
		 */
		static public function get_layout() {
			global $shoestrap_layout;
			global $ss_settings;

			if ( !isset( $shoestrap_layout ) ) {
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
					if ( !is_page_template() ) {
						$post_types = get_post_types( array( 'public' => true ), 'names' );

						foreach ( $post_types as $post_type ) {
							$shoestrap_layout = ( is_singular( $post_type ) ) ? intval( $ss_settings[$post_type . '_layout'] ) : $shoestrap_layout;
						}
					}
				}

				if ( !is_active_sidebar( 'sidebar-secondary' ) && is_active_sidebar( 'sidebar-primary' ) && $shoestrap_layout == 5 ) {
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
			global $redux, $ss_framework, $ss_settings;

			$layout = self::get_layout();
			$first  = intval( $ss_settings['layout_primary_width'] );
			$second = intval( $ss_settings['layout_secondary_width'] );

			// Set some defaults so that we can change them depending on the selected template
			$main       = 12;
			$primary    = NULL;
			$secondary  = NULL;
			$wrapper    = NULL;

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

			} elseif ( shoestrap_display_primary_sidebar() && !shoestrap_display_secondary_sidebar() ) {

				if ( $layout >= 1 ) {
					$main       = 12 - $first;
					$primary    = $first;
				}

			} elseif ( !shoestrap_display_primary_sidebar() && shoestrap_display_secondary_sidebar() ) {

				if ( $layout >= 3 ) {
					$main       = 12 - $second;
					$secondary  = $second;
				}
			}

			if ( $target == 'primary' ) {
				$class = $ss_framework->column_classes( array( 'tablet' => $primary ) );
			} elseif ( $target == 'secondary' ) {
				$class = $ss_framework->column_classes( array( 'tablet' => $secondary ) );
			} elseif ( $target == 'wrapper' ) {
				$class = $ss_framework->column_classes( array( 'tablet' => $wrapper ) );
			} else {
				$class = $ss_framework->column_classes( array( 'tablet' => $main ) );
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

		/*
		 * Calculate the width of the content area in pixels.
		 */
		public static function content_width_px( $echo = false ) {
			global $redux;
			global $ss_settings;

			$layout = self::get_layout();

			$container  = filter_var( $ss_settings['max-width'], FILTER_SANITIZE_NUMBER_INT );
			$gutter     = filter_var( $ss_settings['layout_gutter'], FILTER_SANITIZE_NUMBER_INT );

			$main_span  = filter_var( self::section_class_ext( 'main', false ), FILTER_SANITIZE_NUMBER_INT );
			$main_span  = str_replace( '-' , '', $main_span );

			// If the layout is #5, override the default function and calculate the span width of the main area again.
			if ( is_active_sidebar( 'sidebar-secondary' ) && is_active_sidebar( 'sidebar-primary' ) && $layout == 5 )
				$main_span = 12 - intval( $ss_settings['layout_primary_width'] ) - intval( $ss_settings['layout_secondary_width'] );

			if ( is_front_page() && $ss_settings['layout_sidebar_on_front'] != 1 )
				$main_span = 12;

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

		/**
		 * Modify the rules for showing up or hiding the primary sidebar
		 */
		function control_primary_sidebar_display() {
			global $ss_settings;

			$layout_sidebar_on_front = $ss_settings['layout_sidebar_on_front'];

			if ( self::get_layout() == 0 )
				add_filter( 'shoestrap_display_primary_sidebar', 'shoestrap_return_false' );

			if ( is_front_page() && $layout_sidebar_on_front == 1 && self::get_layout() != 0 )
				add_filter( 'shoestrap_display_primary_sidebar', 'shoestrap_return_true' );

			if ( ( !is_front_page() || ( is_front_page() && $layout_sidebar_on_front == 1 ) ) && self::get_layout() != 0 )
				add_filter( 'shoestrap_display_primary_sidebar', 'shoestrap_return_true' );

		}

		/**
		 * Modify the rules for showing up or hiding the secondary sidebar
		 */
		function control_secondary_sidebar_display() {
			global $ss_settings;

			$layout_sidebar_on_front = $ss_settings['layout_sidebar_on_front'];

			if ( self::get_layout() < 3 )
				add_filter( 'shoestrap_display_secondary_sidebar', 'shoestrap_return_false' );

			if ( ( !is_front_page() && shoestrap_display_secondary_sidebar() ) || ( is_front_page() && $layout_sidebar_on_front == 1 && self::get_layout() >= 3 ) )
				add_filter( 'shoestrap_display_secondary_sidebar', 'shoestrap_return_true' );

		}
	}
}

$layout = new Shoestrap_Layout();