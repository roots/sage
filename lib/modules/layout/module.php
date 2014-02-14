<?php


if ( !class_exists( 'ShoestrapLayout' ) ) {

	/**
	* The "Layout Module"
	*/
	class ShoestrapLayout {

		function __construct() {
			add_filter( 'redux/options/' . SHOESTRAP_OPT_NAME . '/sections', array( $this, 'options' ), 55 );
			add_filter( 'shoestrap_section_class_wrapper', array( $this, 'apply_layout_classes_wrapper' ) );
			add_filter( 'shoestrap_section_class_main', array( $this, 'apply_layout_classes_main' ) );
			add_filter( 'shoestrap_section_class_primary', array( $this, 'apply_layout_classes_primary' ) );
			add_filter( 'shoestrap_section_class_secondary', array( $this, 'apply_layout_classes_secondary' ) );
			add_filter( 'shoestrap_container_class', array( $this, 'container_class' ) );
			add_filter( 'body_class', array( $this, 'layout_body_class' ) );
			add_filter( 'shoestrap_navbar_container_class', array( $this, 'navbar_container_class' ) );
			add_action( 'template_redirect', array( $this, 'content_width' ) );
			if ( ( shoestrap_getVariable( 'body_margin_top' ) != '0' ) || ( shoestrap_getVariable( 'body_margin_bottom' ) != '0' ) )
				add_action( 'wp_enqueue_scripts', array( $this, 'body_margin' ), 101 );

			add_action( 'get_header', array( $this, 'boxed_container_div_open' ), 1 );
			add_action( 'shoestrap_pre_footer', array( $this, 'boxed_container_div_open' ), 1 );
			add_action( 'shoestrap_do_navbar', array( $this, 'boxed_container_div_close' ), 99 );
			add_action( 'shoestrap_after_footer', array( $this, 'boxed_container_div_close' ), 899 );
			add_action( 'wp', array( $this, 'control_primary_sidebar_display' ) );
			add_action( 'wp', array( $this, 'control_secondary_sidebar_display' ) );

			 // Modify the appearance of widgets based on user selection.
			$widgets_mode = shoestrap_getVariable( 'widgets_mode' );
			if ( $widgets_mode == 0 || $widgets_mode == 1 ) {
				add_filter( 'shoestrap_widgets_class', array( $this, 'alter_widgets_class' ) );
				add_filter( 'shoestrap_widgets_before_title', array( $this, 'alter_widgets_before_title' ) );
				add_filter( 'shoestrap_widgets_after_title', array( $this, 'alter_widgets_after_title' ) );
			}

			add_action( 'wp_head', array( $this, 'static_meta' ) );

			add_filter( 'shoestrap_compiler', array( $this, 'variables_filter' ) );
			add_filter( 'shoestrap_compiler', array( $this, 'styles' ) );
		}

		/*
		 * The layout core options for the Shoestrap theme
		 */
		function options( $sections ) {

			// Layout Settings
			$section = array( 
				'title'       => __( 'Layout', 'shoestrap' ),
				'icon'        => 'el-icon-screen icon-large',
				'description' => '<p>In this area you can select your site\'s layout, the width of your sidebars, as well as other, more advanced options.</p>',
			);

			$fields[] = array( 
				'title'     => __( 'Site Style', 'shoestrap' ),
				'desc'      => __( 'Select the default site layout. Default: Wide', 'shoestrap' ),
				'id'        => 'site_style',
				'default'   => 'wide',
				'type'      => 'select',
				'customizer'=> array(),
				'options'   => array( 
					'static'  => __( 'Static (Non-Responsive)', 'shoestrap' ),
					'wide'    => __( 'Wide', 'shoestrap' ),
					'boxed'   => __( 'Boxed', 'shoestrap' ),
					'fluid'   => __( 'Fluid', 'shoestrap' ),
				),
				'compiler'  => true,
			);

			$fields[] = array( 
				'title'     => __( 'Layout', 'shoestrap' ),
				'desc'      => __( 'Select main content and sidebar arrangement. Choose between 1, 2 or 3 column layout.', 'shoestrap' ),
				'id'        => 'layout',
				'default'   => 1,
				'type'      => 'image_select',
				'customizer'=> array(),
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
				'customizer'=> array(),
			);

			$post_types = get_post_types( array( 'public' => true ), 'names' );
			foreach ( $post_types as $post_type ) {
				$fields[] = array(
					'title'     => __( $post_type . ' Layout', 'shoestrap' ),
					'desc'      => __( 'Override your default stylings. Choose between 1, 2 or 3 column layout.', 'shoestrap' ),
					'id'        => $post_type . '_layout',
					'default'   => shoestrap_getVariable( 'layout' ),
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
				'customizer'=> array(),
				'default'   => 0,
				'type'      => 'switch'
			);

			$fields[] = array( 
				'title'     => __( 'Margin from top ( Works only in \'Boxed\' mode )', 'shoestrap' ),
				'desc'      => __( 'This will add a margin above the navbar. Useful if you\'ve enabled the \'Boxed\' mode above. Default: 0px', 'shoestrap' ),
				'id'        => 'navbar_margin_top',
				'required'  => array('navbar_boxed','=',array('1')),
				'default'   => 0,
				'min'       => 0,
				'step'      => 1,
				'max'       => 120,
				'compiler'  => true,
				'type'      => 'slider'
			);

			$fields[] = array( 
				'title'     => __( 'Widgets mode', 'shoestrap' ),
				'desc'      => __( 'How do you want your widgets to be displayed?', 'shoestrap' ),
				'id'        => 'widgets_mode',
				'default'   => 1,
				'options'   => array(
					0           => __( 'Panel', 'shoestrap' ),
					1           => __( 'Well', 'shoestrap' ),
					2           => __( 'None', 'shoestrap' ),
				),
				'type'      => 'button_set',
				'customizer'=> array(),
			);

			$fields[] = array( 
				'title'     => __( 'Body Top Margin', 'shoestrap' ),
				'desc'      => __( 'Select the top margin of body element in pixels. Default: 0px.', 'shoestrap' ),
				'id'        => 'body_margin_top',
				'default'   => 0,
				'min'       => 0,
				'step'      => 1,
				'max'       => 200,
				'edit'      => 1,
				'type'      => 'slider'
			);

			$fields[] = array( 
				'title'     => __( 'Body Bottom Margin', 'shoestrap' ),
				'desc'      => __( 'Select the bottom margin of body element in pixels. Default: 0px.', 'shoestrap' ),
				'id'        => 'body_margin_bottom',
				'default'   => 0,
				'min'       => 0,
				'step'      => 1,
				'max'       => 200,
				'edit'      => 1,
				'type'      => 'slider',
			);

			$fields[] = array( 
				'title'     => __( 'Custom Grid', 'shoestrap' ),
				'desc'      => '<strong>' . __( 'CAUTION:', 'shoestrap' ) . '</strong> ' . __( 'Only use this if you know what you are doing, as changing these values might break the way your site looks on some devices. The default settings should be fine for the vast majority of sites.', 'shoestrap' ),
				'id'        => 'custom_grid',
				'default'   => 0,
				'type'      => 'switch',
			);

			$fields[] = array( 
				'title'     => __( 'Small Screen / Tablet view', 'shoestrap' ),
				'desc'      => __( 'The width of Tablet screens. Default: 768px', 'shoestrap' ),
				'id'        => 'screen_tablet',
				'required'  => array('custom_grid','=',array('1')),
				'default'   => 768,
				'min'       => 620,
				'step'      => 2,
				'max'       => 2100,
				'advanced'  => true,
				'compiler'  => true,
				'type'      => 'slider'
			);

			$fields[] = array( 
				'title'     => __( 'Desktop Container Width', 'shoestrap' ),
				'desc'      => __( 'The width of normal screens. Default: 992px', 'shoestrap' ),
				'id'        => 'screen_desktop',
				'required'  => array('custom_grid','=',array('1')),
				'default'   => 992,
				'min'       => 620,
				'step'      => 2,
				'max'       => 2100,
				'advanced'  => true,
				'compiler'  => true,
				'type'      => 'slider',

			);

			$fields[] = array( 
				'title'     => __( 'Large Desktop Container Width', 'shoestrap' ),
				'desc'      => __( 'The width of Large Desktop screens. Default: 1200px', 'shoestrap' ),
				'id'        => 'screen_large_desktop',
				'required'  => array('custom_grid','=',array('1')),
				'default'   => 1200,
				'min'       => 620,
				'step'      => 2,
				'max'       => 2100,
				'advanced'  => true,
				'compiler'  => true,
				'type'      => 'slider'
			);

			$fields[] = array( 
				'title'     => __( 'Columns Gutter', 'shoestrap' ),
				'desc'      => __( 'The space between the columns in your grid. Default: 30px', 'shoestrap' ),
				'id'        => 'layout_gutter',
				'required'  => array('custom_grid','=',array('1')),
				'default'   => 30,
				'min'       => 2,
				'step'      => 2,
				'max'       => 100,
				'advanced'  => true,
				'compiler'  => true,
				'type'      => 'slider',
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

			if ( !isset( $shoestrap_layout ) ) {
				do_action( 'shoestrap_layout_modifier' );
				
				$shoestrap_layout = intval( shoestrap_getVariable( 'layout' ) );

				// Looking for a per-page template ?
				if ( is_page() && is_page_template() ) {
					if ( is_page_template( 'template-0.php' ) )
						$shoestrap_layout = 0;
					elseif ( is_page_template( 'template-1.php' ) )
						$shoestrap_layout = 1;
					elseif ( is_page_template( 'template-2.php' ) )
						$shoestrap_layout = 2;
					elseif ( is_page_template( 'template-3.php' ) )
						$shoestrap_layout = 3;
					elseif ( is_page_template( 'template-4.php' ) )
						$shoestrap_layout = 4;
					elseif ( is_page_template( 'template-5.php' ) )
						$shoestrap_layout = 5;
				}

				if ( shoestrap_getVariable( 'cpt_layout_toggle' ) == 1 ) {
					if ( !is_page_template() ) {
						$post_types = get_post_types( array( 'public' => true ), 'names' );
						foreach ( $post_types as $post_type ) {
							$shoestrap_layout = ( is_singular( $post_type ) ) ? intval( shoestrap_getVariable( $post_type . '_layout' ) ) : $shoestrap_layout;
						}
					}
				}

				if ( !is_active_sidebar( 'sidebar-secondary' ) && is_active_sidebar( 'sidebar-primary' ) && $shoestrap_layout == 5 )
					$shoestrap_layout = 3;
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
			global $redux;
			
			$layout = self::get_layout();
			$first  = intval( shoestrap_getVariable( 'layout_primary_width' ) );
			$second = intval( shoestrap_getVariable( 'layout_secondary_width' ) );
			
			// disable responsiveness if layout is set to non-responsive
			$base = ( shoestrap_getVariable( 'site_style' ) == 'static' ) ? 'col-xs-' : 'col-sm-';
			
			// Set some defaults so that we can change them depending on the selected template
			$main       = $base . 12;
			$primary    = NULL;
			$secondary  = NULL;
			$wrapper    = NULL;

			if ( shoestrap_display_primary_sidebar() && shoestrap_display_secondary_sidebar() ) {

				if ( $layout == 5 ) {
					$main       = $base . ( 12 - floor( ( 12 * $first ) / ( 12 - $second ) ) );
					$primary    = $base . floor( ( 12 * $first ) / ( 12 - $second ) );
					$secondary  = $base . $second;
					$wrapper    = $base . ( 12 - $second );
				} elseif ( $layout >= 3 ) {
					$main       = $base . ( 12 - $first - $second );
					$primary    = $base . $first;
					$secondary  = $base . $second;
				} elseif ( $layout >= 1 ) {
					$main       = $base . ( 12 - $first );
					$primary    = $base . $first;
					$secondary  = $base . $second;
				}

			} elseif ( shoestrap_display_primary_sidebar() && !shoestrap_display_secondary_sidebar() ) {

				if ( $layout >= 1 ) {
					$main       = $base . ( 12 - $first );
					$primary    = $base . $first;
				}

			} elseif ( !shoestrap_display_primary_sidebar() && shoestrap_display_secondary_sidebar() ) {

				if ( $layout >= 3 ) {
					$main       = $base . ( 12 - $second );
					$secondary  = $base . $second;
				}
			}

			if ( $target == 'primary' )
				$class = $primary;
			elseif ( $target == 'secondary' )
				$class = $secondary;
			elseif ( $target == 'wrapper' )
				$class = $wrapper;
			else
				$class = $main;

			if ( $echo )
				echo $class;
			else
				return $class;

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
			$layout     = self::get_layout();
			$site_style = shoestrap_getVariable( 'site_style' );
			$margin     = shoestrap_getVariable( 'navbar_margin_top' );
			$style      = '';

			$classes[] = ( $layout == 2 || $layout == 3 || $layout == 5 ) ? 'main-float-right' : '';
			$classes[] = ( $site_style == 'boxed' && $margin != 0 ) ? 'boxed-style' : '';

			// Remove unnecessary classes
			$remove_classes = array();
			$classes = array_diff( $classes, $remove_classes );

			return $classes;
		}

		/*
		 * Return the container class
		 */
		function container_class() {
			$class = shoestrap_getVariable( 'site_style' ) != 'fluid' ? 'container' : 'fluid';

			return $class;
		}

		/*
		 * Return the container class
		 */
		function navbar_container_class() {
			$site_style = shoestrap_getVariable( 'site_style' );
			$toggle     = shoestrap_getVariable( 'navbar_toggle' );

			if ( $toggle == 'full' )
				$class = 'fluid';
			else
				$class = ( $site_style != 'fluid' ) ? 'container' : 'fluid';

			return $class;
		}

		/*
		 * Calculate the width of the content area in pixels.
		 */
		public static function content_width_px( $echo = false ) {
			global $redux;

			$layout = self::get_layout();

			$container  = filter_var( shoestrap_getVariable( 'screen_large_desktop' ), FILTER_SANITIZE_NUMBER_INT );
			$gutter     = filter_var( shoestrap_getVariable( 'layout_gutter' ), FILTER_SANITIZE_NUMBER_INT );

			$main_span  = filter_var( self::section_class_ext( 'main', false ), FILTER_SANITIZE_NUMBER_INT );
			$main_span  = str_replace( '-' , '', $main_span );

			// If the layout is #5, override the default function and calculate the span width of the main area again.
			if ( is_active_sidebar( 'sidebar-secondary' ) && is_active_sidebar( 'sidebar-primary' ) && $layout == 5 )
				$main_span = 12 - intval( shoestrap_getVariable( 'layout_primary_width' ) ) - intval( shoestrap_getVariable( 'layout_secondary_width' ) );

			if ( is_front_page() && shoestrap_getVariable( 'layout_sidebar_on_front' ) != 1 )
				$main_span = 12;

			$width = $container * ( $main_span / 12 ) - $gutter;

			// Width should be an integer since we're talking pixels, round up!.
			$width = round( $width );

			if ( $echo )
				echo $width;
			else
				return $width;
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
			$body_margin_top = shoestrap_getVariable( 'body_margin_top' );
			$body_margin_bottom = shoestrap_getVariable( 'body_margin_bottom' );

			$style = 'body { margin-top:'. $body_margin_top .'px; margin-bottom:'. $body_margin_bottom .'px; }';

			wp_add_inline_style( 'shoestrap_css', $style );
		}

		/**
		 * Add a wrapper div when in "boxed" mode to disallow full-width elements
		 */
		function boxed_container_div_open() {
			if ( shoestrap_getVariable( 'site_style' ) == 'boxed' ) echo '<div class="container boxed-container">';
		}

		/**
		 * Close the wrapper div that the 'boxed_container_div_open' opens when in "boxed" mode.
		 */
		function boxed_container_div_close() {
			if ( shoestrap_getVariable( 'site_style' ) == 'boxed' ) echo '</div>';
		}

		/**
		 * Modify the rules for showing up or hiding the primary sidebar
		 */
		function control_primary_sidebar_display() {
			$layout_sidebar_on_front = shoestrap_getVariable( 'layout_sidebar_on_front' );

			if ( self::get_layout() == 0 )
				add_filter( 'shoestrap_display_primary_sidebar', 'shoestrap_return_false' );

			if ( is_front_page() && $layout_sidebar_on_front == 1 && self::get_layout() != 0 )
				add_filter( 'shoestrap_display_primary_sidebar', 'shoestrap_return_true' );

			if ( !is_front_page() || ( is_front_page() && $layout_sidebar_on_front == 1 ) )
				add_filter( 'shoestrap_display_primary_sidebar', 'shoestrap_return_true' );

		}

		/**
		 * Modify the rules for showing up or hiding the secondary sidebar
		 */
		function control_secondary_sidebar_display() {
			$layout_sidebar_on_front = shoestrap_getVariable( 'layout_sidebar_on_front' );

			if ( self::get_layout() < 3 )
				add_filter( 'shoestrap_display_secondary_sidebar', 'shoestrap_return_false' );

			if ( ( !is_front_page() && shoestrap_display_secondary_sidebar() ) || ( is_front_page() && $layout_sidebar_on_front == 1 && self::get_layout() >= 3 ) )
				add_filter( 'shoestrap_display_secondary_sidebar', 'shoestrap_return_true' );

		}

		/**
		 * Get the widget class
		 */
		function alter_widgets_class() {
			return shoestrap_getVariable( 'widgets_mode' ) == 0 ? 'panel panel-default' : 'well';
		}

		/**
		 * Widgets 'before_title' modifying based on widgets mode.
		 */
		function alter_widgets_before_title() {
			return shoestrap_getVariable( 'widgets_mode' ) == 0 ? '<div class="panel-heading">' : '<h3 class="widget-title">';
		}

		/**
		 * Widgets 'after_title' modifying based on widgets mode.
		 */
		function alter_widgets_after_title() {
			return shoestrap_getVariable( 'widgets_mode' ) == 0 ? '</div><div class="panel-body">' : '</h3>';
		}

		/**
		 * Add some metadata when users have selected a static mode for their layout (not responsive).
		 */
		function static_meta() {
			if ( shoestrap_getVariable( 'site_style' ) != 'static' ) : ?>
				<meta name="viewport" content="width=device-width, initial-scale=1">
				<meta name="mobile-web-app-capable" content="yes">
				<meta name="apple-mobile-web-app-capable" content="yes">
				<meta name="apple-mobile-web-app-status-bar-style" content="black">
				<?php
			endif;
		}

		/**
		 * Variables to use for the compiler.
		 * These override the default Bootstrap Variables.
		 */
		function variables() {
			$screen_sm = filter_var( shoestrap_getVariable( 'screen_tablet', true ), FILTER_SANITIZE_NUMBER_INT );
			$screen_md = filter_var( shoestrap_getVariable( 'screen_desktop', true ), FILTER_SANITIZE_NUMBER_INT );
			$screen_lg = filter_var( shoestrap_getVariable( 'screen_large_desktop', true ), FILTER_SANITIZE_NUMBER_INT );
			$gutter    = filter_var( shoestrap_getVariable( 'layout_gutter', true ), FILTER_SANITIZE_NUMBER_INT );
			$gutter    = ( $gutter < 2 ) ? 2 : $gutter;

			$site_style = shoestrap_getVariable( 'site_style' );

			$screen_xs = ( $site_style == 'static' ) ? '50px' : '480px';
			$screen_sm = ( $site_style == 'static' ) ? '50px' : $screen_sm;
			$screen_md = ( $site_style == 'static' ) ? '50px' : $screen_md;

			$variables = '';

			$variables .= '@screen-sm: ' . $screen_sm . 'px;';
			$variables .= '@screen-md: ' . $screen_md . 'px;';
			$variables .= '@screen-lg: ' . $screen_lg . 'px;';

			$variables .= '@grid-gutter-width: ' . $gutter . 'px;';

			$variables .= '@jumbotron-padding: @grid-gutter-width;';

			$variables .= '@modal-inner-padding: ' . round( $gutter * 20 / 30 ) . 'px;';
			$variables .= '@modal-title-padding: ' . round( $gutter * 15 / 30 ) . 'px;';

			$variables .= '@modal-lg: ' . round( $screen_md - ( 3 * $gutter ) ) . 'px;';
			$variables .= '@modal-md: ' . round( $screen_sm - ( 3 * $gutter ) ) . 'px;';
			$variables .= '@modal-sm: ' . round( $screen_xs - ( 3 * $gutter ) ) . 'px;';

			$variables .= '@panel-body-padding: @modal-title-padding;';

			$variables .= '@container-tablet:        ' . ( $screen_sm - ( $gutter / 2 ) ). 'px;';
			$variables .= '@container-desktop:       ' . ( $screen_md - ( $gutter / 2 ) ). 'px;';
			$variables .= '@container-large-desktop: ' . ( $screen_lg - $gutter ). 'px;';

			if ( $site_style == 'static' ) {
				// disable responsiveness
				$variables .= '@screen-xs-max: 0 !important;
				.container { max-width: none !important; width: @container-large-desktop; }
				html { overflow-x: auto !important; }';
			}

			return $variables;
		}


		/**
		 * Add the variables to the compiler
		 */
		function variables_filter( $variables ) {
			return $variables . self::variables();
		}

		function styles( $bootstrap ) {
			return $bootstrap . '
			@import "' . SHOESTRAP_MODULES_PATH . '/layout/assets/less/styles.less";';
		}
	}
}

$layout = new ShoestrapLayout();