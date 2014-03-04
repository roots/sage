<?php


if ( ! class_exists( 'Shoestrap_Menus' ) ) {

	/**
	* The "Menus" module
	*/
	class Shoestrap_Menus {

		function __construct() {
			global $ss_settings;

			add_filter( 'redux/options/' . SHOESTRAP_OPT_NAME . '/sections', array( $this, 'options' ), 70 );
			add_filter( 'shoestrap_nav_class',        array( $this, 'nav_class' ) );
			add_action( 'shoestrap_inside_nav_begin', array( $this, 'navbar_pre_searchbox' ), 11 );
			add_filter( 'shoestrap_navbar_class',     array( $this, 'navbar_class' ) );
			add_action( 'wp_enqueue_scripts',         array( $this, 'navbar_css' ), 101 );
			add_action( 'shoestrap_do_navbar',        array( $this, 'do_navbar' ) );
			add_filter( 'shoestrap_navbar_brand',     array( $this, 'navbar_brand' ) );
			add_filter( 'body_class',                 array( $this, 'navbar_body_class' ) );
			add_action( 'widgets_init',               array( $this, 'sl_widgets_init' ), 40 );
			add_action( 'shoestrap_post_main_nav',    array( $this, 'navbar_sidebar' ) );
			add_action( 'shoestrap_pre_wrap',         array( $this, 'secondary_navbar' ) );
			add_action( 'widgets_init',               array( $this, 'slidedown_widgets_init' ), 40 );
			add_action( 'shoestrap_do_navbar',        array( $this, 'navbar_slidedown_content' ), 99 );
			add_action( 'wp_enqueue_scripts',         array( $this, 'megadrop_script' ), 200 );
			add_action( 'shoestrap_pre_wrap',         array( $this, 'content_wrapper_static_left_open' ) );
			add_action( 'shoestrap_after_footer',     array( $this, 'content_wrapper_static_left_close' ), 1 );

			if ( $ss_settings['secondary_navbar_margin'] != 0 ) {
				add_action( 'wp_enqueue_scripts', array( $this, 'secondary_navbar_margin' ), 101 );
			}

			$hook = ( $ss_settings['navbar_toggle'] == 'left' ) ? 'shoestrap_do_navbar' : 'shoestrap_inside_nav_begin';
			add_action( $hook, array( $this, 'navbar_slidedown_toggle' ) );
		}

		/*
		 * The header core options for the Shoestrap theme
		 */
		function options( $sections ) {

			// Branding Options
			$section = array(
				'title' => __( 'Menus', 'shoestrap' ),
				'icon'  => 'el-icon-lines'
			);

			$fields[] = array(
				'id'          => 'help7',
				'title'       => __( 'Advanced NavBar Options', 'shoestrap' ),
				'desc'        => __( "You can activate or deactivate your Primary NavBar here, and define its properties. Please note that you might have to manually create a menu if it doesn't already exist.", 'shoestrap' ),
				'type'        => 'info'
			);

			$fields[] = array(
				'title'       => __( 'Type of NavBar', 'shoestrap' ),
				'desc'        => __( 'Choose the type of Navbar you want. Off completely hides the navbar, Alternative uses an alternative walker for the navigation menus. See <a target="_blank"href="https://github.com/twittem/wp-bootstrap-navwalker">here</a> for more details.', 'shoestrap' ) . '<br>' . __( '<strong>WARNING:</strong> The "Static-Left" option is ONLY compatible with fluid layouts. The width of the static-left navbar is controlled by the secondary sidebar width.', 'shoestrap' ),
				'id'          => 'navbar_toggle',
				'default'     => 'normal',
				'options'     => array(
					'none'    => __( 'Off', 'shoestrap' ),
					'normal'  => __( 'Normal', 'shoestrap' ),
					// 'pills'   => __( 'Pills', 'shoestrap' ),
					'full'    => __( 'Full-Width', 'shoestrap' ),
					'left'    => __( 'Static-Left', 'shoestrap' ),
				),
				'type'        => 'button_set'
			);

			$fields[] = array(
				'id'          => 'helpnavbarbg',
				'title'       => __( 'NavBar Styling Options', 'shoestrap' ),
				'desc'   	  => __( 'Customize the look and feel of your navbar below.', 'shoestrap' ),
				'type'        => 'info'
			);

			$fields[] = array(
				'title'       => __( 'NavBar Background Color', 'shoestrap' ),
				'desc'        => __( 'Pick a background color for the NavBar. Default: #eeeeee.', 'shoestrap' ),
				'id'          => 'navbar_bg',
				'default'     => '#f8f8f8',
				'compiler'    => true,
				'transparent' => false,
				'type'        => 'color'
			);

			$fields[] = array(
				'title'       => __( 'NavBar Background Opacity', 'shoestrap' ),
				'desc'        => __( 'Pick a background opacity for the NavBar. Default: 100%.', 'shoestrap' ),
				'id'          => 'navbar_bg_opacity',
				'default'     => 100,
				'min'         => 0,
				'step'        => 1,
				'max'         => 100,
				'type'        => 'slider',
			);

			$fields[] = array(
				'title'       => __( 'NavBar Menu Style', 'shoestrap' ),
				'desc'        => __( 'You can use an alternative menu style for your NavBars.', 'shoestrap' ),
				'id'          => 'navbar_style',
				'default'     => 'default',
				'type'        => 'select',
				'options'     => array(
					'default' => __( 'Default', 'shoestrap' ),
					'style1'  => __( 'Style', 'shoestrap' ) . ' 1',
					'style2'  => __( 'Style', 'shoestrap' ) . ' 2',
					'style3'  => __( 'Style', 'shoestrap' ) . ' 3',
					'style4'  => __( 'Style', 'shoestrap' ) . ' 4',
					'style5'  => __( 'Style', 'shoestrap' ) . ' 5',
					'style6'  => __( 'Style', 'shoestrap' ) . ' 6',
					'metro'   => __( 'Metro', 'shoestrap' ),
				)
			);

			$fields[] = array(
				'title'       => __( 'Display Branding ( Sitename or Logo ) on the NavBar', 'shoestrap' ),
				'desc'        => __( 'Default: ON', 'shoestrap' ),
				'id'          => 'navbar_brand',
				'default'     => 1,
				'type'        => 'switch'
			);

			$fields[] = array(
				'title'       => __( 'Use Logo ( if available ) for branding on the NavBar', 'shoestrap' ),
				'desc'        => __( 'If this option is OFF, or there is no logo available, then the sitename will be displayed instead. Default: ON', 'shoestrap' ),
				'id'          => 'navbar_logo',
				'default'     => 1,
				'type'        => 'switch'
			);

			$fields[] = array(
				'title'       => __( 'NavBar Positioning', 'shoestrap' ),
				'desc'        => __( 'Using this option you can set the navbar to be fixed to top, fixed to bottom or normal. When you\'re using one of the \'fixed\' options, the navbar will stay fixed on the top or bottom of the page. Default: Normal', 'shoestrap' ),
				'id'          => 'navbar_fixed',
				'default'     => 0,
				'on'          => __( 'Fixed', 'shoestrap' ),
				'off'         => __( 'Scroll', 'shoestrap' ),
				'type'        => 'switch'
			);

			$fields[] = array(
				'title'       => __( 'Fixed NavBar Position', 'shoestrap' ),
				'desc'        => __( 'Using this option you can set the navbar to be fixed to top, fixed to bottom or normal. When you\'re using one of the \'fixed\' options, the navbar will stay fixed on the top or bottom of the page. Default: Normal', 'shoestrap' ),
				'id'          => 'navbar_fixed_position',
				'required'    => array('navbar_fixed','=',array('1')),
				'default'     => 0,
				'on'          => __( 'Bottom', 'shoestrap' ),
				'off'         => __( 'Top', 'shoestrap' ),
				'type'        => 'switch'
			);

			$fields[] = array(
				'title'       => __( 'NavBar Height', 'shoestrap' ),
				'desc'        => __( 'Select the height of the NavBar in pixels. Should be equal or greater than the height of your logo if you\'ve added one.', 'shoestrap' ),
				'id'          => 'navbar_height',
				'default'     => 50,
				'min'         => 38,
				'step'        => 1,
				'max'         => 200,
				'compiler'    => true,
				'type'        => 'slider'
			);

			$fields[] = array(
				'title'       => __( 'Navbar Font', 'shoestrap' ),
				'desc'        => __( 'The font used in navbars.', 'shoestrap' ),
				'id'          => 'font_navbar',
				'compiler'    => true,
				'default'     => array(
					'font-family' => 'Arial, Helvetica, sans-serif',
					'font-size'   => 14,
					'color'       => '#333333',
					'google'      => 'false',
				),
				'preview'     => array(
					'text'    => __( 'This is my preview text!', 'shoestrap' ), //this is the text from preview box
					'size'    => 30 //this is the text size from preview box
				),
				'type'        => 'typography',
			);

			$fields[] = array(
				'title'       => __( 'Branding Font', 'shoestrap' ),
				'desc'        => __( 'The branding font for your site.', 'shoestrap' ),
				'id'          => 'font_brand',
				'compiler'    => true,
				'default'     => array(
					'font-family' => 'Arial, Helvetica, sans-serif',
					'font-size'   => 18,
					'google'      => 'false',
					'color'       => '#333333',
				),
				'preview'     => array(
					'text'    => __( 'This is my preview text!', 'shoestrap' ), //this is the text from preview box
					'size'    => 30 //this is the text size from preview box
				),
				'type'        => 'typography',
			);

			$fields[] = array(
				'title'     => __( 'Responsive NavBar Threshold', 'shoestrap' ),
				'desc'      => __( 'Point at which the navbar becomes uncollapsed', 'shoestrap' ),
				'id'        => 'grid_float_breakpoint',
				'type'      => 'button_set',
				'options'   => array(
					'min'           => __( 'Never', 'shoestrap' ),
					'screen_xs_min' => __( 'Extra Small', 'shoestrap' ),
					'screen_sm_min' => __( 'Small', 'shoestrap' ),
					'screen_md_min' => __( 'Desktop', 'shoestrap' ),
					'screen_lg_min' => __( 'Large Desktop', 'shoestrap' ),
					'max'           => __( 'Always', 'shoestrap' ),
				),
				'default'   => 'screen_sm_min',
				'compiler'  => true,
			);

			$fields[] = array(
				'title'       => __( 'NavBar Margin', 'shoestrap' ),
				'desc'        => __( 'Select the top and bottom margin of the NavBar in pixels. Applies only in static top navbar ( scroll condition ). Default: 0px.', 'shoestrap' ),
				'id'          => 'navbar_margin',
				'default'     => 0,
				'min'         => 0,
				'step'        => 1,
				'max'         => 200,
				'type'        => 'slider',
			);

			$fields[] = array(
				'title'       => __( 'Display social links in the NavBar.', 'shoestrap' ),
				'desc'        => __( 'Display social links in the NavBar. These can be setup in the \'Social\' section on the left. Default: OFF', 'shoestrap' ),
				'id'          => 'navbar_social',
				'default'     => 0,
				'type'        => 'switch'
			);

			$fields[] = array(
				'title'       => __( 'Display social links as a Dropdown list or an Inline list.', 'shoestrap' ),
				'desc'        => __( 'How to display social links. Default: Dropdown list', 'shoestrap' ),
				'id'          => 'navbar_social_style',
				'default'     => 0,
				'on'          => __( 'Inline', 'shoestrap' ),
				'off'         => __( 'Dropdown', 'shoestrap' ),
				'type'        => 'switch',
				'required'    => array('navbar_social','=',array('1')),
			);

			$fields[] = array(
				'title'       => __( 'Search form on the NavBar', 'shoestrap' ),
				'desc'        => __( 'Display a search form in the NavBar. Default: On', 'shoestrap' ),
				'id'          => 'navbar_search',
				'default'     => 1,
				'type'        => 'switch'
			);

			$fields[] = array(
				'title'       => __( 'Float NavBar menu to the right', 'shoestrap' ),
				'desc'        => __( 'Floats the primary navigation to the right. Default: On', 'shoestrap' ),
				'id'          => 'navbar_nav_right',
				'default'     => 1,
				'type'        => 'switch'
			);

			$fields[] = array(
				'id'          => 'help9',
				'title'       => __( 'Secondary Navbar', 'shoestrap' ),
				'desc'        => __( 'The secondary navbar is a 2nd navbar, located right above the main wrapper. You can show a menu there, by assigning it from Appearance -> Menus.', 'shoestrap' ),
				'type'        => 'info',
			);

			$fields[] = array(
				'title'       => __( 'Display social networks in the secondary navigation bar.', 'shoestrap' ),
				'desc'        => __( 'Enable this option to display your social networks as a dropdown menu on the seondary navbar.', 'shoestrap' ),
				'id'          => 'navbar_secondary_social',
				'default'     => 0,
				'type'        => 'switch',
			);

			$fields[] = array(
				'title'       => __( 'Secondary NavBar Margin', 'shoestrap' ),
				'desc'        => __( 'Select the top and bottom margin of header in pixels. Default: 0px.', 'shoestrap' ),
				'id'          => 'secondary_navbar_margin',
				'default'     => 0,
				'min'         => 0,
				'max'         => 200,
				'type'        => 'slider',
			);

			$fields[] = array(
				'id'          => 'helpsidebarmenus',
				'title'       => __( 'Sidebar Menus', 'shoestrap' ),
				'desc'        => __( 'If you\'re using the "Custom Menu" widgets in your sidebars, you can control their styling here', 'shoestrap' ),
				'type'        => 'info',
			);

			$fields[] = array(
				'title'       => __( 'Color for sidebar menus', 'shoestrap' ),
				'desc'        => __( 'Select a style for menus added to your sidebars using the custom menu widget', 'shoestrap' ),
				'id'          => 'menus_class',
				'default'     => 1,
				'type'        => 'select',
				'options'     => array(
					'default' => __( 'Default', 'shoestrap' ),
					'primary' => __( 'Branding-Primary', 'shoestrap' ),
					'success' => __( 'Branding-Success', 'shoestrap' ),
					'warning' => __( 'Branding-Warning', 'shoestrap' ),
					'info'    => __( 'Branding-Info', 'shoestrap' ),
					'danger'  => __( 'Branding-Danger', 'shoestrap' ),
				),
			);

			$fields[] = array(
				'title'       => __( 'Inverse Sidebar_menus.', 'shoestrap' ),
				'desc'        => __( 'Default: OFF. See https://github.com/twittem/wp-bootstrap-navlist-walker for more details', 'shoestrap' ),
				'id'          => 'inverse_navlist',
				'default'     => 0,
				'type'        => 'switch',
			);

			$section['fields'] = $fields;

			$section = apply_filters( 'shoestrap_module_menus_options_modifier', $section );

			$sections[] = $section;
			return $sections;

		}

		/**
		 * Modify the nav class.
		 */
		function nav_class() {
			global $ss_settings;

			if ( $ss_settings['navbar_nav_right'] == '1' ) {
				return 'navbar-nav nav pull-right';
			} else {
				return 'navbar-nav nav';
			}
		}


		/*
		 * The template for the primary navbar searchbox
		 */
		function navbar_pre_searchbox() {
			global $ss_settings;

			$show_searchbox = $ss_settings['navbar_search'];
			if ( $show_searchbox == '1' ) : ?>
				<form role="search" method="get" id="searchform" class="form-search pull-right navbar-form" action="<?php echo home_url('/'); ?>">
					<label class="hide" for="s"><?php _e('Search for:', 'shoestrap'); ?></label>
					<input type="text" value="<?php if (is_search()) { echo get_search_query(); } ?>" name="s" id="s" class="form-control search-query" placeholder="<?php _e('Search', 'shoestrap'); ?> <?php bloginfo('name'); ?>">
				</form>
			<?php endif;
		}

		/**
		 * Modify the navbar class.
		 */
		public static function navbar_class( $navbar = 'main') {
			global $ss_settings;

			$fixed    = $ss_settings['navbar_fixed'];
			$fixedpos = $ss_settings['navbar_fixed_position'];
			$style    = $ss_settings['navbar_style'];
			$toggle   = $ss_settings['navbar_toggle'];
			$left     = ( $toggle == 'left' ) ? true : false;

			$bp = self::sl_breakpoint();

			$defaults = 'navbar navbar-default topnavbar';

			if ( $fixed != 1 ) {
				$class = ' navbar-static-top';
			} else {
				$class = ( $fixedpos == 1 ) ? ' navbar-fixed-bottom' : ' navbar-fixed-top';
			}

			$class = $defaults . $class;

			if ( $left ) {
				$extra_classes = 'navbar navbar-default static-left ' . $bp .  ' col-' . $bp . '-' . $ss_settings['layout_secondary_width'];
				$class = $extra_classes;
			}

			if ( $navbar != 'secondary' ) {
				return $class . ' ' . $style;
			} else {
				return 'navbar ' . $style;
			}
		}

		/**
		 * Modify the grid-float-breakpoint using Bootstrap classes.
		 */
		public static function sl_breakpoint() {
			global $ss_settings;

			$break    = $ss_settings['grid_float_breakpoint'];

			$bp = ( $break == 'min' || $break == 'screen_xs_min' ) ? 'xs' : 'xs';
			$bp = ( $break == 'screen_sm_min' )                    ? 'sm' : $bp;
			$bp = ( $break == 'screen_md_min' )                    ? 'md' : $bp;
			$bp = ( $break == 'screen_lg_min' || $break == 'max' ) ? 'lg' : $bp;

			return $bp;
		}

		/**
		 * Add some CSS for the navbar when needed.
		 */
		function navbar_css() {
			global $ss_settings;

			$navbar_bg_opacity = $ss_settings['navbar_bg_opacity'];
			$style = '';

			$opacity = ( $navbar_bg_opacity == '' ) ? '0' : ( intval( $navbar_bg_opacity ) ) / 100;

			if ( $opacity != 1 && $opacity != '' ) {
				$bg  = str_replace( '#', '', $ss_settings['navbar_bg'] );
				$rgb = Shoestrap_Color::get_rgb( $bg, true );
				$opacityie = str_replace( '0.', '', $opacity );

				$style .= '.navbar, .navbar-default {';

				if ( $opacity != 1 && $opacity != '') {
					$style .= 'background: transparent; background: rgba(' . $rgb . ', ' . $opacity . '); filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#' . $opacityie . $bg . ',endColorstr=#' . $opacityie . $bg . '); ;';
				} else {
					$style .= 'background: #' . $bg . ';';
				}

				$style .= '}';
			}

			if ( $ss_settings['navbar_margin'] != 1 ) {
				$style .= '.navbar-static-top { margin-top:'. $ss_settings['navbar_margin'] . 'px !important; margin-bottom:' . $ss_settings['navbar_margin'] . 'px !important; }';
			}

			wp_add_inline_style( 'shoestrap_css', $style );
		}

		/**
		 * Will the sidebar be shown?
		 * If yes, then which navbar?
		 */
		function do_navbar() {
			global $ss_settings;

			$navbar_toggle = $ss_settings['navbar_toggle'];

			if ( $navbar_toggle != 'none' ) {
				if ( $navbar_toggle != 'pills' ) {
					if ( ! has_action( 'shoestrap_header_top_navbar_override' ) ) {
						require( 'header-top-navbar.php' );
					} else {
						do_action( 'shoestrap_header_top_navbar_override' );
					}
				} else {
					if ( ! has_action( 'shoestrap_header_override' ) ) {
						require( 'header.php' );
					} else {
						do_action( 'shoestrap_header_override' );
					}
				}
			} else {
				return '';
			}
		}

		/**
		 * get the navbar branding options (if the branding module exists)
		 * and then add the appropriate logo or sitename.
		 */
		function navbar_brand() {
			global $ss_settings, $ss_framework;

			$logo           = $ss_settings['logo'];
			$branding_class = ! empty( $logo['url'] ) ? 'logo' : 'text';

			if ( $ss_settings['navbar_brand'] != 0 ) {
				$branding  = '<a class="navbar-brand ' . $branding_class . '" href="' . home_url('/') . '">';
				$branding .= $ss_settings['navbar_logo'] == 1 ? $ss_framework->logo() : get_bloginfo( 'name' );
				$branding .= '</a>';
			} else {
				$branding = '';
			}
			return $branding;
		}

		/**
		 * Add and remove body_class() classes
		 */
		function navbar_body_class( $classes ) {
			global $ss_settings;

			// Add 'top-navbar' or 'bottom-navabr' class if using Bootstrap's Navbar
			// Used to add styling to account for the WordPress admin bar
			if ( $ss_settings['navbar_fixed'] == 1 && $ss_settings['navbar_fixed_position'] != 1 && $ss_settings['navbar_toggle'] != 'left' ) {
				$classes[] = 'top-navbar';
			} elseif ( $ss_settings['navbar_fixed'] == 1 && $ss_settings['navbar_fixed_position'] == 1 ) {
				$classes[] = 'bottom-navbar';
			}

			return $classes;
		}

		/**
		 * Register sidebars and widgets
		 */
		function sl_widgets_init() {
			register_sidebar( array(
				'name'          => __( 'In-Navbar Widget Area', 'shoestrap' ),
				'id'            => 'navbar',
				'description'   => __( 'This widget area will show up in your NavBars. This is most useful when using a static-left navbar.', 'shoestrap' ),
				'before_widget' => '<div id="in-navbar">',
				'after_widget'  => '</div>',
				'before_title'  => '<h1>',
				'after_title'   => '</h1>',
			) );
		}

		/**
		 * Add the sidebar to the navbar.
		 */
		function navbar_sidebar() {
			dynamic_sidebar( 'navbar' );
		}

		/**
		 * The contents of the secondary navbar
		 */
		function secondary_navbar() {
			global $ss_settings, $ss_framework;

			if ( has_nav_menu( 'secondary_navigation' ) ) : ?>

				<?php echo $ss_framework->make_container( 'div' ); ?>
					<header class="secondary navbar navbar-default <?php echo self::navbar_class( 'secondary' ); ?>" role="banner">
						<button data-target=".nav-secondary" data-toggle="collapse" type="button" class="navbar-toggle">
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<?php
						if ( $ss_settings['navbar_secondary_social'] != 0 ) {
							SS_Framework_Bootstrap::navbar_social_links();
						} ?>
						<nav class="nav-secondary navbar-collapse collapse" role="navigation">
							<?php wp_nav_menu( array( 'theme_location' => 'secondary_navigation', 'menu_class' => apply_filters( 'shoestrap_nav_class', 'navbar-nav nav' ) ) ); ?>
						</nav>
					</header>
				</div>

			<?php endif;
		}

		/**
		 * Add margin to the secondary nvbar if needed
		 */
		function secondary_navbar_margin() {
			global $ss_settings;

			$secondary_navbar_margin = $ss_settings['secondary_navbar_margin'];
			$style = '.secondary { margin-top:' . $secondary_navbar_margin . 'px !important; margin-bottom:'. $secondary_navbar_margin .'px !important; }';

			wp_add_inline_style( 'shoestrap_css', $style );
		}

		/**
		 * Register widget areas for the navbar dropdowns.
		 */
		function slidedown_widgets_init() {
			// Register widgetized areas
			register_sidebar( array(
				'name'          => __( 'Navbar Slide-Down Top', 'shoestrap' ),
				'id'            => 'navbar-slide-down-top',
				'before_widget' => '<section id="%1$s" class="widget %2$s"><div class="widget-inner">',
				'after_widget'  => '</div></section>',
				'before_title'  => '<h3>',
				'after_title'   => '</h3>',
			) );

			register_sidebar( array(
				'name'          => __( 'Navbar Slide-Down 1', 'shoestrap' ),
				'id'            => 'navbar-slide-down-1',
				'before_widget' => '<section id="%1$s" class="widget %2$s"><div class="widget-inner">',
				'after_widget'  => '</div></section>',
				'before_title'  => '<h3>',
				'after_title'   => '</h3>',
			) );

			register_sidebar( array(
				'name'          => __( 'Navbar Slide-Down 2', 'shoestrap' ),
				'id'            => 'navbar-slide-down-2',
				'before_widget' => '<section id="%1$s" class="widget %2$s"><div class="widget-inner">',
				'after_widget'  => '</div></section>',
				'before_title'  => '<h3>',
				'after_title'   => '</h3>',
			) );

			register_sidebar( array(
				'name'          => __( 'Navbar Slide-Down 3', 'shoestrap' ),
				'id'            => 'navbar-slide-down-3',
				'before_widget' => '<section id="%1$s" class="widget %2$s"><div class="widget-inner">',
				'after_widget'  => '</div></section>',
				'before_title'  => '<h3>',
				'after_title'   => '</h3>',
			) );

			register_sidebar( array(
				'name'          => __( 'Navbar Slide-Down 4', 'shoestrap' ),
				'id'            => 'navbar-slide-down-4',
				'before_widget' => '<section id="%1$s" class="widget %2$s"><div class="widget-inner">',
				'after_widget'  => '</div></section>',
				'before_title'  => '<h3>',
				'after_title'   => '</h3>',
			) );
		}

		/*
		 * Calculates the class of the widget areas based on a 12-column bootstrap grid.
		 */
		public static function navbar_widget_area_class() {
			$str = '';
			$str .= ( is_active_sidebar( 'navbar-slide-down-1' ) ) ? '1' : '';
			$str .= ( is_active_sidebar( 'navbar-slide-down-2' ) ) ? '2' : '';
			$str .= ( is_active_sidebar( 'navbar-slide-down-3' ) ) ? '3' : '';
			$str .= ( is_active_sidebar( 'navbar-slide-down-4' ) ) ? '4' : '';

			$strlen = strlen( $str );

			$colwidth = ( $strlen > 0 ) ? 12 / $strlen : 12;

			return $colwidth;
		}

		/*
		 * Prints the content of the slide-down widget areas.
		 */
		function navbar_slidedown_content() {
			global $ss_settings;

			if ( is_active_sidebar( 'navbar-slide-down-1' ) || is_active_sidebar( 'navbar-slide-down-2' ) || is_active_sidebar( 'navbar-slide-down-3' ) || is_active_sidebar( 'navbar-slide-down-4' ) || is_active_sidebar( 'navbar-slide-down-top' ) ) : ?>
				<div class="before-main-wrapper">
					<?php $megadrop_class = ( $ss_settings['site_style'] != 'fluid' ) ? 'top-megamenu container' : 'top-megamenu'; ?>
					<div id="megaDrop" class="' . $megadrop_class . '">
						<?php $widgetareaclass = 'col-sm-' . self::navbar_widget_area_class(); ?>

						<?php dynamic_sidebar( 'navbar-slide-down-top' ); ?>

						<div class="row">
							<?php if ( is_active_sidebar( 'navbar-slide-down-1' ) ) : ?>
								<div class="<?php echo $widgetareaclass; ?>">
									<?php dynamic_sidebar( 'navbar-slide-down-1' ); ?>
								</div>
							<?php endif; ?>

							<?php if ( is_active_sidebar( 'navbar-slide-down-2' ) ) : ?>
								<div class="<?php echo $widgetareaclass; ?>">
									<?php dynamic_sidebar( 'navbar-slide-down-2' ); ?>
								</div>
							<?php endif; ?>

							<?php if ( is_active_sidebar( 'navbar-slide-down-3' ) ) : ?>
								<div class="<?php echo $widgetareaclass; ?>">
									<?php dynamic_sidebar( 'navbar-slide-down-3' ); ?>
								</div>
							<?php endif; ?>

							<?php if ( is_active_sidebar( 'navbar-slide-down-4' ) ) : ?>
								<div class="<?php echo $widgetareaclass; ?>">
									<?php dynamic_sidebar( 'navbar-slide-down-4' ); ?>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			<?php endif;
		}

		/**
		 * When static-left navbar is selected, we need to add a wrapper to the whole content
		 */
		function content_wrapper_static_left_open() {
			global $ss_settings, $ss_framework;

			$breakpoint = self::sl_breakpoint();

			if ( $breakpoint == 'xs' ) {
				$width = 'mobile';
			} elseif ( $breakpoint == 'sm' ) {
				$width = 'tablet';
			} elseif ( $breakpoint == 'md' ) {
				$width = 'medium';
			} elseif ( $breakpoint == 'lg' ) {
				$width = 'large';
			}

			if ( isset( $ss_settings['navbar_toggle'] ) && $ss_settings['navbar_toggle'] == 'left' ) {
				echo $ss_framework->make_col( 'div', array( $width => 12 - $ss_settings['layout_secondary_width'] ), 'content-wrapper-left', 'col-' . $breakpoint . '-offset-' . $ss_settings['layout_secondary_width'] );
			}
		}

		/**
		 * When static-left navbar is selected, we need to close the wrapper opened by the content_wrapper_static_left function.
		 */
		function content_wrapper_static_left_close() {
			global $ss_settings, $ss_framework;

			if ( isset( $ss_settings['navbar_toggle'] ) && $ss_settings['navbar_toggle'] == 'left' ) {
				echo '</div>';
			}
		}

		/**
		 * The icon that helps us open/close the dropdown widgets.
		 */
		function navbar_slidedown_toggle() {
			global $ss_settings;

			$navbar_color = $ss_settings['navbar_bg'];
			$navbar_mode  = $ss_settings['navbar_toggle'];
			$trigger = (
				is_active_sidebar( 'navbar-slide-down-top' ) ||
				is_active_sidebar( 'navbar-slide-down-1' ) ||
				is_active_sidebar( 'navbar-slide-down-2' ) ||
				is_active_sidebar( 'navbar-slide-down-3' ) ||
				is_active_sidebar( 'navbar-slide-down-4' )
			) ? true : false;

			if ( $trigger ) {

				$class = ( $navbar_mode == 'left' ) ? ' static-left' : ' nav-toggle';
				$pre   = ( $navbar_mode != 'left' ) ? '<ul class="nav navbar-nav"><li>' : '';
				$post  = ( $navbar_mode != 'left' ) ? '</li></ul>' : '';

				echo $pre . '<a class="toggle-nav' . $class . '" href="#"><i class="el-icon-chevron-down"></i></a>' . $post;

			}
		}

		/**
		 * The script responsible for showing/hiding the dropdown widget areas from the navbar.
		 */
		function megadrop_script() {
			if ( is_active_sidebar( 'navbar-slide-down-top' ) || is_active_sidebar( 'navbar-slide-down-1' ) || is_active_sidebar( 'navbar-slide-down-2' ) || is_active_sidebar( 'navbar-slide-down-3' ) || is_active_sidebar( 'navbar-slide-down-4' ) ) {
				wp_register_script( 'shoestrap_megadrop', get_template_directory_uri() . '/assets/js/megadrop.js', false, null, false );
				wp_enqueue_script( 'shoestrap_megadrop' );
			}
		}
	}
}