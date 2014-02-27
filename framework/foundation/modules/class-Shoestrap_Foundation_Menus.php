<?php


if ( !class_exists( 'Shoestrap_Foundation_Menus' ) ) {

	/**
	* The "Menus" module
	*/
	class Shoestrap_Foundation_Menus {

		function __construct() {
			global $ss_settings;

			add_filter( 'redux/options/' . SHOESTRAP_OPT_NAME . '/sections', array( $this, 'options' ), 70 );
			add_filter( 'shoestrap_nav_class',        array( $this, 'nav_class'                )      );
			add_action( 'shoestrap_inside_nav_begin', array( $this, 'navbar_pre_searchbox'     ), 11  );
			add_filter( 'shoestrap_navbar_class',     array( $this, 'navbar_class'             )      );
			add_action( 'wp_enqueue_scripts',         array( $this, 'navbar_css'               ), 101 );
			add_action( 'shoestrap_do_navbar',        array( $this, 'do_navbar'                )      );
			// add_action( 'shoestrap_do_navbar',        array( $this, 'sl_main_wrapper_open'     ), 97  );
			// add_action( 'shoestrap_after_footer',     array( $this, 'sl_main_wrapper_close'    ), 901 );
			add_filter( 'shoestrap_navbar_brand',     array( $this, 'navbar_brand'             )      );
			add_filter( 'body_class',                 array( $this, 'navbar_body_class'        )      );
			// add_action( 'widgets_init',               array( $this, 'sl_widgets_init'          ), 40  );
			// add_action( 'shoestrap_post_main_nav',    array( $this, 'navbar_sidebar'           )      ); 
			// add_action( 'shoestrap_pre_wrap',         array( $this, 'secondary_navbar'         )      );
			// add_action( 'widgets_init',               array( $this, 'slidedown_widgets_init'   ), 40  );
			// add_action( 'shoestrap_do_navbar',        array( $this, 'navbar_slidedown_content' ), 99  );
			// add_action( 'wp_enqueue_scripts',         array( $this, 'megadrop_script'          ), 200 );
			add_filter( 'shoestrap_compiler',         array( $this, 'variables_filter'         )      );

			// if ( $ss_settings['secondary_navbar_margin'] != 0 )
			// 	add_action( 'wp_enqueue_scripts', array( $this, 'secondary_navbar_margin' ), 101 );

			// $hook = ( $ss_settings['navbar_toggle'] == 'left' ) ? 'shoestrap_do_navbar' : 'shoestrap_inside_nav_begin';
			// add_action( $hook, array( $this, 'navbar_slidedown_toggle' ) );
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
					'contain'    => __( 'Contain-To-Grid', 'shoestrap' ),
					// 'left'    => __( 'Static-Left', 'shoestrap' ),
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
				'type'        => 'button_set',
				'options'     => array(
					''          => __( 'Scroll', 'shoestrap' ),
					'fixed'     => __( 'Fixed', 'shoestrap' ),
					'sticky'    => __( 'Sticky', 'shoestrap' ),
				),
				'default' => ''
			);

			$fields[] = array( 
				'title'       => __( 'NavBar Height', 'shoestrap' ),
				'desc'        => __( 'Select the height of the NavBar in pixels. Should be equal or greater than the height of your logo if you\'ve added one.', 'shoestrap' ),
				'id'          => 'navbar_height',
				'default'     => 45,
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

			// $fields[] = array( 
			// 	'id'          => 'help9',
			// 	'title'       => __( 'Secondary Navbar', 'shoestrap' ),
			// 	'desc'        => __( 'The secondary navbar is a 2nd navbar, located right above the main wrapper. You can show a menu there, by assigning it from Appearance -> Menus.', 'shoestrap' ),
			// 	'type'        => 'info',
			// );

			// $fields[] = array( 
			// 	'title'       => __( 'Enable the Secondary NavBar', 'shoestrap' ),
			// 	'desc'        => __( 'Display a Secondary NavBar on top of the Main NavBar. Default: ON', 'shoestrap' ),
			// 	'id'          => 'secondary_navbar_toggle',
			// 	'default'     => 0,
			// 	'type'        => 'switch',
			// );

			// $fields[] = array( 
			// 	'title'       => __( 'Display social networks in the navbar', 'shoestrap' ),
			// 	'desc'        => __( 'Enable this option to display your social networks as a dropdown menu on the seondary navbar.', 'shoestrap' ),
			// 	'id'          => 'navbar_secondary_social',
			// 	'required'    => array('secondary_navbar_toggle','=',array('1')),
			// 	'default'     => 0,
			// 	'type'        => 'switch',
			// );

			// $fields[] = array( 
			// 	'title'       => __( 'Secondary NavBar Margin', 'shoestrap' ),
			// 	'desc'        => __( 'Select the top and bottom margin of header in pixels. Default: 0px.', 'shoestrap' ),
			// 	'id'          => 'secondary_navbar_margin',
			// 	'default'     => 0,
			// 	'min'         => 0,
			// 	'max'         => 200,
			// 	'type'        => 'slider',
			// 	'required'    => array('secondary_navbar_toggle','=',array('1')),
			// );

			// $fields[] = array( 
			// 	'id'          => 'helpsidebarmenus',
			// 	'title'       => __( 'Sidebar Menus', 'shoestrap' ),
			// 	'desc'        => __( 'If you\'re using the "Custom Menu" widgets in your sidebars, you can control their styling here', 'shoestrap' ),
			// 	'type'        => 'info',
			// );

			// $fields[] = array( 
			// 	'title'       => __( 'Color for sidebar menus', 'shoestrap' ),
			// 	'desc'        => __( 'Select a style for menus added to your sidebars using the custom menu widget', 'shoestrap' ),
			// 	'id'          => 'menus_class',
			// 	'default'     => 1,
			// 	'type'        => 'select',
			// 	'options'     => array( 
			// 		'default' => __( 'Default', 'shoestrap' ),
			// 		'primary' => __( 'Branding-Primary', 'shoestrap' ),
			// 		'success' => __( 'Branding-Success', 'shoestrap' ),
			// 		'warning' => __( 'Branding-Warning', 'shoestrap' ),
			// 		'info'    => __( 'Branding-Info', 'shoestrap' ),
			// 		'danger'  => __( 'Branding-Danger', 'shoestrap' ),
			// 	),
			// );

			// $fields[] = array( 
			// 	'title'       => __( 'Inverse Sidebar_menus.', 'shoestrap' ),
			// 	'desc'        => __( 'Default: OFF. See https://github.com/twittem/wp-bootstrap-navlist-walker for more details', 'shoestrap' ),
			// 	'id'          => 'inverse_navlist',
			// 	'default'     => 0,
			// 	'type'        => 'switch',
			// );

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
				return 'right';
			} else {
				return 'left';
			}
		}


		/*
		 * The template for the primary navbar searchbox
		 */
		function navbar_pre_searchbox() {
			global $ss_settings;

			$show_searchbox = $ss_settings['navbar_search'];
			if ( $show_searchbox == '1' ) : ?>
				<form role="search" method="get" id="searchform" class="form-search right navbar-form" action="<?php echo home_url('/'); ?>">
					<label class="hide" for="s"><?php _e('Search for:', 'shoestrap'); ?></label>
					<input type="text" value="<?php if (is_search()) { echo get_search_query(); } ?>" name="s" id="s" class="search-query" placeholder="<?php _e('Search', 'shoestrap'); ?> <?php bloginfo('name'); ?>">
				</form>
				<?php
			endif;
		}

		/**
		 * Modify the navbar class.
		 */
		public static function navbar_class( $navbar = 'main') {
			global $ss_settings;
			$toggle   = $ss_settings['navbar_toggle'];

			$fixed    = $ss_settings['navbar_fixed'];
			$contain  = ( $toggle == 'contain' ) ? true : false;

			$classes = $fixed;

			if ( $contain ) 
				$classes .= ' contain-to-grid';

			return $classes;
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

				$style .= '.navbar {';

				if ( $opacity != 1 && $opacity != '')
					$style .= 'background: transparent; background: rgba(' . $rgb . ', ' . $opacity . '); filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#' . $opacityie . $bg . ',endColorstr=#' . $opacityie . $bg . '); ;';
				else
					$style .= 'background: #' . $bg . ';';

				$style .= '}';

			}

			if ( $ss_settings['navbar_margin'] != 1 )
				$style .= '.navbar { margin-top:'. $ss_settings['navbar_margin'] . 'px !important; margin-bottom:' . $ss_settings['navbar_margin'] . 'px !important; }';

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
				if ( !has_action( 'shoestrap_header_top_navbar_override' ) )
					require( 'top-bar.php' );
				else
					do_action( 'shoestrap_header_top_navbar_override' );
			} else {
				return '';
			}
		}

		/**
		 * When the navbar is set to static-left, we need to add some wrappers
		 */
		// function sl_main_wrapper_open() {
		// 	global $ss_settings;

		// 	$left = $ss_settings['navbar_toggle'] == 'left' ? true : false;

		// 	if ( $left ) {
		// 		echo '<section class="static-menu-main ' . self::sl_breakpoint() . ' col-static-' . ( 12 - $ss_settings['layout_secondary_width'] ) . '">';
		// 	}
		// }


		/**
		 * Close the wrapper div that the 'sl_main_wrapper_open' opens.
		 */
		// function sl_main_wrapper_close() {
		// 	global $ss_settings;

		// 	$left = $ss_settings['navbar_toggle'] == 'left' ? true : false;

		// 	if ( $left ) {
		// 		echo '</section>';
		// 	}
		// }

		/**
		 * get the navbar branding options (if the branding module exists)
		 * and then add the appropriate logo or sitename.
		 */
		function navbar_brand() {
			// Make sure the branding module exists.
			if ( class_exists( 'ShoestrapBranding' ) ) {
				global $ss_settings;

				$logo           = $ss_settings['logo'];
				$branding_class = !empty( $logo['url'] ) ? 'logo' : 'text';

				if ( $ss_settings['navbar_brand'] != 0 ) {
					$branding  = '<a class="' . $branding_class . '" href="' . home_url('/') . '">';
					$branding .= $ss_settings['navbar_logo'] == 1 ? ShoestrapBranding::logo() : get_bloginfo( 'name' );
					$branding .= '</a>';
				} else {
					$branding = '';
				}
			} else {
				// If the branding module does not exist, return the defaults.
				$branding = '<a class="text" href="' . home_url('/') . '">' . get_bloginfo( 'name' ) . '</a>';
			}

			return $branding;
		}

		/**
		 * Add and remove body_class() classes
		 */
		function navbar_body_class( $classes ) {
			global $ss_settings;

			// Add 'top-navbar' class if using fixed navbar
			// Used to add styling to account for the WordPress admin bar
			if ( $ss_settings['navbar_fixed'] == 'fixed' ) 
				$classes[] = 'top-navbar';

			return $classes;
		}


		/**
		 * Register sidebars and widgets
		 */
		// function sl_widgets_init() {
		// 	register_sidebar( array(
		// 		'name'          => __( 'In-Navbar Widget Area', 'shoestrap' ),
		// 		'id'            => 'navbar',
		// 		'description'   => __( 'This widget area will show up in your NavBars. This is most useful when using a static-left navbar.', 'shoestrap' ),
		// 		'before_widget' => '<div id="in-navbar">',
		// 		'after_widget'  => '</div>',
		// 		'before_title'  => '<h1>',
		// 		'after_title'   => '</h1>',
		// 	));
		// }

		/**
		 * Add the sidebar to the navbar.
		 */
		// function navbar_sidebar() {
		// 	dynamic_sidebar( 'navbar' );
		// }


		/**
		 * Add margin to the secondary nvbar if needed
		 */
		// function secondary_navbar_margin() {
		// 	global $ss_settings;

		// 	$secondary_navbar_margin = $ss_settings['secondary_navbar_margin'];
		// 	$style = '.secondary { margin-top:' . $secondary_navbar_margin . 'px !important; margin-bottom:'. $secondary_navbar_margin .'px !important; }';

		// 	wp_add_inline_style( 'shoestrap_css', $style );
		// }

		/**
		 * Register widget areas for the navbar dropdowns.
		 */
		// function slidedown_widgets_init() {
		// 	// Register widgetized areas
		// 	register_sidebar( array( 
		// 		'name'          => __( 'Navbar Slide-Down Top', 'shoestrap' ),
		// 		'id'            => 'navbar-slide-down-top',
		// 		'before_widget' => '<section id="%1$s" class="widget %2$s"><div class="widget-inner">',
		// 		'after_widget'  => '</div></section>',
		// 		'before_title'  => '<h3>',
		// 		'after_title'   => '</h3>',
		// 	) );

		// 	register_sidebar( array( 
		// 		'name'          => __( 'Navbar Slide-Down 1', 'shoestrap' ),
		// 		'id'            => 'navbar-slide-down-1',
		// 		'before_widget' => '<section id="%1$s" class="widget %2$s"><div class="widget-inner">',
		// 		'after_widget'  => '</div></section>',
		// 		'before_title'  => '<h3>',
		// 		'after_title'   => '</h3>',
		// 	) );

		// 	register_sidebar( array( 
		// 		'name'          => __( 'Navbar Slide-Down 2', 'shoestrap' ),
		// 		'id'            => 'navbar-slide-down-2',
		// 		'before_widget' => '<section id="%1$s" class="widget %2$s"><div class="widget-inner">',
		// 		'after_widget'  => '</div></section>',
		// 		'before_title'  => '<h3>',
		// 		'after_title'   => '</h3>',
		// 	) );

		// 	register_sidebar( array( 
		// 		'name'          => __( 'Navbar Slide-Down 3', 'shoestrap' ),
		// 		'id'            => 'navbar-slide-down-3',
		// 		'before_widget' => '<section id="%1$s" class="widget %2$s"><div class="widget-inner">',
		// 		'after_widget'  => '</div></section>',
		// 		'before_title'  => '<h3>',
		// 		'after_title'   => '</h3>',
		// 	) );

		// 	register_sidebar( array( 
		// 		'name'          => __( 'Navbar Slide-Down 4', 'shoestrap' ),
		// 		'id'            => 'navbar-slide-down-4',
		// 		'before_widget' => '<section id="%1$s" class="widget %2$s"><div class="widget-inner">',
		// 		'after_widget'  => '</div></section>',
		// 		'before_title'  => '<h3>',
		// 		'after_title'   => '</h3>',
		// 	) );
		// }

		/*
		 * Calculates the class of the widget areas based on a 12-column bootstrap grid.
		 */
		// public static function navbar_widget_area_class() {
		// 	$str = '';
		// 	$str .= ( is_active_sidebar( 'navbar-slide-down-1' ) ) ? '1' : '';
		// 	$str .= ( is_active_sidebar( 'navbar-slide-down-2' ) ) ? '2' : '';
		// 	$str .= ( is_active_sidebar( 'navbar-slide-down-3' ) ) ? '3' : '';
		// 	$str .= ( is_active_sidebar( 'navbar-slide-down-4' ) ) ? '4' : '';

		// 	$strlen = strlen( $str );

		// 	$colwidth = ( $strlen > 0 ) ? 12 / $strlen : 12;

		// 	return $colwidth;
		// }



		/**
		 * The icon that helps us open/close the dropdown widgets.
		 */
		// function navbar_slidedown_toggle() {
		// 	global $ss_settings;

		// 	$navbar_color = $ss_settings['navbar_bg'];
		// 	$navbar_mode  = $ss_settings['navbar_toggle'];
		// 	$trigger = (
		// 		is_active_sidebar( 'navbar-slide-down-top' ) ||
		// 		is_active_sidebar( 'navbar-slide-down-1' ) ||
		// 		is_active_sidebar( 'navbar-slide-down-2' ) ||
		// 		is_active_sidebar( 'navbar-slide-down-3' ) ||
		// 		is_active_sidebar( 'navbar-slide-down-4' ) 
		// 	) ? true : false;
			
		// 	if ( $trigger ) {

		// 		$class = ( $navbar_mode == 'left' ) ? ' static-left' : ' nav-toggle';
		// 		$pre   = ( $navbar_mode != 'left' ) ? '<ul class="nav navbar-nav"><li>' : '';
		// 		$post  = ( $navbar_mode != 'left' ) ? '</li></ul>' : '';

		// 		echo $pre . '<a class="toggle-nav' . $class . '" href="#"><i class="el-icon-chevron-down"></i></a>' . $post;

		// 	}
		// }

		/**
		 * The script responsible for showing/hiding the dropdown widget areas from the navbar.
		 */
		// function megadrop_script() {
		// 	if ( is_active_sidebar( 'navbar-slide-down-top' ) || is_active_sidebar( 'navbar-slide-down-1' ) || is_active_sidebar( 'navbar-slide-down-2' ) || is_active_sidebar( 'navbar-slide-down-3' ) || is_active_sidebar( 'navbar-slide-down-4' ) ) {
		// 		wp_register_script( 'shoestrap_megadrop', get_template_directory_uri() . '/assets/js/megadrop.js', false, null, false );
		// 		wp_enqueue_script( 'shoestrap_megadrop' );
		// 	}
		// }

		/**
		 * Variables to use for the compiler.
		 * These override the default Bootstrap Variables.
		 */
		// function variables() {
		// 	global $ss_settings;

		// 	$font_brand        = shoestrap_process_font( $ss_settings['font_brand'] );

		// 	$font_navbar       = shoestrap_process_font( $ss_settings['font_navbar'] );
		// 	$navbar_bg         = '#' . str_replace( '#', '', Shoestrap_Color::sanitize_hex( $ss_settings['navbar_bg'] ) );
		// 	$navbar_height     = filter_var( $ss_settings['navbar_height'], FILTER_SANITIZE_NUMBER_INT );
		// 	$navbar_text_color = '#' . str_replace( '#', '', $font_navbar['color'] );
		// 	$brand_text_color  = '#' . str_replace( '#', '', $font_brand['color'] );
		// 	$navbar_border     = ( Shoestrap_Color::get_brightness( $navbar_bg ) < 50 ) ? 'lighten(@navbar-default-bg, 6.5%)' : 'darken(@navbar-default-bg, 6.5%)';
		// 	$gfb = $ss_settings['grid_float_breakpoint'];

		// 	if ( Shoestrap_Color::get_brightness( $navbar_bg ) < 165 ) {
		// 		$navbar_link_hover_color    = 'darken(@navbar-default-color, 26.5%)';
		// 		$navbar_link_active_bg      = 'darken(@navbar-default-bg, 6.5%)';
		// 		$navbar_link_disabled_color = 'darken(@navbar-default-bg, 6.5%)';
		// 		$navbar_brand_hover_color   = 'darken(@navbar-default-brand-color, 10%)';
		// 	} else {
		// 		$navbar_link_hover_color    = 'lighten(@navbar-default-color, 26.5%)';
		// 		$navbar_link_active_bg      = 'lighten(@navbar-default-bg, 6.5%)';
		// 		$navbar_link_disabled_color = 'lighten(@navbar-default-bg, 6.5%)';
		// 		$navbar_brand_hover_color   = 'lighten(@navbar-default-brand-color, 10%)';
		// 	}

		// 	$grid_float_breakpoint = ( isset( $gfb ) )           ? $gfb             : '@screen-sm-min';
		// 	$grid_float_breakpoint = ( $gfb == 'min' )           ? '10px'           : $grid_float_breakpoint;
		// 	$grid_float_breakpoint = ( $gfb == 'screen_xs_min' ) ? '@screen-xs-min' : $grid_float_breakpoint;
		// 	$grid_float_breakpoint = ( $gfb == 'screen_sm_min' ) ? '@screen-sm-min' : $grid_float_breakpoint;
		// 	$grid_float_breakpoint = ( $gfb == 'screen_md_min' ) ? '@screen-md-min' : $grid_float_breakpoint;
		// 	$grid_float_breakpoint = ( $gfb == 'screen_lg_min' ) ? '@screen-lg-min' : $grid_float_breakpoint;
		// 	$grid_float_breakpoint = ( $gfb == 'max' )           ? '9999px'         : $grid_float_breakpoint;

		// 	$grid_float_breakpoint = ( $gfb == 'screen-lg-min' ) ? '0 !important' : $grid_float_breakpoint;

		// 	$variables = '';

		// 	$variables .= '@navbar-height:         ' . $navbar_height . 'px;';

		// 	$variables .= '@navbar-default-color:  ' . $navbar_text_color . ';';
		// 	$variables .= '@navbar-default-bg:     ' . $navbar_bg . ';';
		// 	$variables .= '@navbar-default-border: ' . $navbar_border . ';';

		// 	$variables .= '@navbar-default-link-color:          @navbar-default-color;';
		// 	$variables .= '@navbar-default-link-hover-color:    ' . $navbar_link_hover_color . ';';
		// 	$variables .= '@navbar-default-link-active-color:   mix(@navbar-default-color, @navbar-default-link-hover-color, 50%);';
		// 	$variables .= '@navbar-default-link-active-bg:      ' . $navbar_link_active_bg . ';';
		// 	$variables .= '@navbar-default-link-disabled-color: ' . $navbar_link_disabled_color . ';';

		// 	$variables .= '@navbar-default-brand-color:         @navbar-default-link-color;';
		// 	$variables .= '@navbar-default-brand-hover-color:   ' . $navbar_brand_hover_color . ';';

		// 	$variables .= '@navbar-default-toggle-hover-bg:     ' . $navbar_border . ';';
		// 	$variables .= '@navbar-default-toggle-icon-bar-bg:  ' . $navbar_text_color . ';';
		// 	$variables .= '@navbar-default-toggle-border-color: ' . $navbar_border . ';';

		// 	// Shoestrap-specific variables
		// 	// --------------------------------------------------

		// 	$variables .= '@navbar-font-size:        ' . $font_navbar['font-size'] . 'px;';
		// 	$variables .= '@navbar-font-weight:      ' . $font_navbar['font-weight'] . ';';
		// 	$variables .= '@navbar-font-style:       ' . $font_navbar['font-style'] . ';';
		// 	$variables .= '@navbar-font-family:      ' . $font_navbar['font-family'] . ';';
		// 	$variables .= '@navbar-font-color:       ' . $navbar_text_color . ';';

		// 	$variables .= '@brand-font-size:         ' . $font_brand['font-size'] . 'px;';
		// 	$variables .= '@brand-font-weight:       ' . $font_brand['font-weight'] . ';';
		// 	$variables .= '@brand-font-style:        ' . $font_brand['font-style'] . ';';
		// 	$variables .= '@brand-font-family:       ' . $font_brand['font-family'] . ';';
		// 	$variables .= '@brand-font-color:        ' . $brand_text_color . ';';

		// 	$variables .= '@navbar-margin-top:       ' . $ss_settings['navbar_margin_top'] . 'px;';

		// 	$variables .= '@grid-float-breakpoint: ' . $grid_float_breakpoint . ';';

		// 	return $variables;
		// }
		/**
		 * Add the variables to the compiler
		 */
		// function variables_filter( $variables ) {
		// 	return $variables . self::variables();
		// }
	}
}

// include_once( dirname( __FILE__ ) . '/includes/functions.navlist-walker.php' );
// include_once( dirname( __FILE__ ) . '/includes/functions.navlist.php' );

$menus = new Shoestrap_Foundation_Menus();