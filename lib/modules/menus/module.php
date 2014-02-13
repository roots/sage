<?php

/*
 * The header core options for the Shoestrap theme
 */
if ( !function_exists( 'shoestrap_module_menus_options' ) ) :
function shoestrap_module_menus_options( $sections ) {

	// Branding Options
	$section = array( 
		'title' => __( 'Menus', 'shoestrap' ),
		'icon'  => 'el-icon-lines icon-large'
	);

	$url = admin_url( 'nav-menus.php' );
	$fields[] = array( 
		'id'          => 'help7',
		'title'       => __( 'Advanced NavBar Options', 'shoestrap' ),
		'desc'        => __( "You can activate or deactivate your Primary NavBar here, and define its properties. Please note that you might have to manually create a menu if it doesn't already exist and add items to it from <a href='$url'>this page</a>.", 'shoestrap' ),
		'type'        => 'info'
	);

	$url = admin_url( 'nav-menus.php?action=locations' );
	$fields[] = array( 
		'title'       => __( 'Type of NavBar', 'shoestrap' ),
		'desc'        => __( 'Choose the type of Navbar you want. Off completely hides the navbar, Alternative uses an alternative walker for the navigation menus. See <a target="_blank"href="https://github.com/twittem/wp-bootstrap-navwalker">here</a> for more details.', 'shoestrap' ) . '<br>' . __( '<strong>WARNING:</strong> The "Static-Left" option is ONLY compatible with fluid layouts. The width of the static-left navbar is controlled by the secondary sidebar width.', 'shoestrap' ),
		'id'          => 'navbar_toggle',
		'default'     => 'normal',
		'options'     => array(
			'none'      => __( 'Off', 'shoestrap' ),
			'normal'    => __( 'Normal', 'shoestrap' ),
			'pills'     => __( 'Pills', 'shoestrap' ),
			'full'      => __( 'Full-Width', 'shoestrap' ),
			'left'      => __( 'Static-Left', 'shoestrap' ),
		),
		'customizer'  => array(),
		'type'        => 'button_set'
	);

	$fields[] = array( 
		'id'          => 'helpnavbarbg',
		'title'       => __( 'NavBar Styling Options', 'shoestrap' ),
		'desc'   	    => __( 'Customize the look and feel of your navbar below.', 'shoestrap' ),
		'type'        => 'info'
	);    

	$fields[] = array( 
		'title'       => __( 'NavBar Background Color', 'shoestrap' ),
		'desc'        => __( 'Pick a background color for the NavBar. Default: #eeeeee.', 'shoestrap' ),
		'id'          => 'navbar_bg',
		'default'     => '#f8f8f8',
		'compiler'    => true,
		'customizer'  => array(),
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
		'customizer'  => array(),
		'options'     => array( 
			'default'   => __( 'Default', 'shoestrap' ),
			'style1'    => __( 'Style', 'shoestrap' ) . ' 1',
			'style2'    => __( 'Style', 'shoestrap' ) . ' 2',
			'style3'    => __( 'Style', 'shoestrap' ) . ' 3',
			'style4'    => __( 'Style', 'shoestrap' ) . ' 4',
			'style5'    => __( 'Style', 'shoestrap' ) . ' 5',
			'style6'    => __( 'Style', 'shoestrap' ) . ' 6',
			'metro'     => __( 'Metro', 'shoestrap' ),
		)
	);

	$fields[] = array( 
		'title'       => __( 'Display Branding ( Sitename or Logo ) on the NavBar', 'shoestrap' ),
		'desc'        => __( 'Default: ON', 'shoestrap' ),
		'id'          => 'navbar_brand',
		'default'     => 1,
		'customizer'  => array(),
		'type'        => 'switch'
	);

	$fields[] = array( 
		'title'       => __( 'Use Logo ( if available ) for branding on the NavBar', 'shoestrap' ),
		'desc'        => __( 'If this option is OFF, or there is no logo available, then the sitename will be displayed instead. Default: ON', 'shoestrap' ),
		'id'          => 'navbar_logo',
		'default'     => 1,
		'customizer'  => array(),
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
			'color'     => '#333333',
			'google'    => 'false',
		),
		'preview'     => array( 
			'text'      => __( 'This is my preview text!', 'shoestrap' ), //this is the text from preview box
			'size'      => 30 //this is the text size from preview box
		),
		'type'        => 'typography',
	);

	$fields[] = array( 
		'title'       => __( 'Branding Font', 'shoestrap' ),
		'desc'        => __( 'The branding font for your site.', 'shoestrap' ),
		'id'          => 'font_brand',
		'compiler'    => true,
		'default'     => array( 
			'font-family'  => 'Arial, Helvetica, sans-serif',
			'font-size'    => 18,
			'google'    => 'false',
			'color'     => '#333333',
		),
		'preview'     => array( 
			'text'      => __( 'This is my preview text!', 'shoestrap' ), //this is the text from preview box
			'size'      => 30 //this is the text size from preview box
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
		'customizer'  => array(),
		'default'     => 0,
		'type'        => 'switch'
	);

	$fields[] = array( 
		'title'       => __( 'Display social links as a Dropdown list or an Inline list.', 'shoestrap' ),
		'desc'        => __( 'How to display social links. Default: Dropdown list', 'shoestrap' ),
		'id'          => 'navbar_social_style',
		'customizer'  => array(),
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
		'customizer'  => array(),
		'default'     => 1,
		'type'        => 'switch'
	);

	$fields[] = array( 
		'title'       => __( 'Float NavBar menu to the right', 'shoestrap' ),
		'desc'        => __( 'Floats the primary navigation to the right. Default: On', 'shoestrap' ),
		'id'          => 'navbar_nav_right',
		'default'     => 1,
		'customizer'  => array(),
		'type'        => 'switch'
	);

	$fields[] = array( 
		'id'          => 'help9',
		'title'       => __( 'Secondary Navbar', 'shoestrap' ),
		'desc'        => __( 'The secondary navbar is a 2nd navbar, located right above the main wrapper. You can show a menu there, by assigning it from Appearance -> Menus.', 'shoestrap' ),
		'type'        => 'info',
	);

	$fields[] = array( 
		'title'       => __( 'Enable the Secondary NavBar', 'shoestrap' ),
		'desc'        => __( 'Display a Secondary NavBar on top of the Main NavBar. Default: ON', 'shoestrap' ),
		'id'          => 'secondary_navbar_toggle',
		'customizer'  => array(),
		'default'     => 0,
		'type'        => 'switch',
	);

	$fields[] = array( 
		'title'       => __( 'Display social networks in the navbar', 'shoestrap' ),
		'desc'        => __( 'Enable this option to display your social networks as a dropdown menu on the seondary navbar.', 'shoestrap' ),
		'id'          => 'navbar_secondary_social',
		'required'    => array('secondary_navbar_toggle','=',array('1')),
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
		'required'    => array('secondary_navbar_toggle','=',array('1')),
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
			'default'   => __( 'Default', 'shoestrap' ),
			'primary'   => __( 'Branding-Primary', 'shoestrap' ),
			'success'   => __( 'Branding-Success', 'shoestrap' ),
			'warning'   => __( 'Branding-Warning', 'shoestrap' ),
			'info'      => __( 'Branding-Info', 'shoestrap' ),
			'danger'    => __( 'Branding-Danger', 'shoestrap' ),
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
endif;
add_filter( 'redux/options/' . SHOESTRAP_OPT_NAME . '/sections', 'shoestrap_module_menus_options', 70 );  

include_once( dirname( __FILE__ ) . '/includes/functions.navbar.php' );
include_once( dirname( __FILE__ ) . '/includes/functions.secondary.navbar.php' );
include_once( dirname( __FILE__ ) . '/includes/functions.slide-down.php' );
include_once( dirname( __FILE__ ) . '/includes/functions.navlist-walker.php' );
include_once( dirname( __FILE__ ) . '/includes/functions.navlist.php' );
include_once( dirname( __FILE__ ) . '/includes/variables.php' );
include_once( dirname( __FILE__ ) . '/includes/functions.left-static-extras.php' );


add_filter( 'shoestrap_compiler', 'shoestrap_admin_menus_styles' );
function shoestrap_admin_menus_styles( $bootstrap ) {
	return $bootstrap . '
	@import "' . SHOESTRAP_MODULES_PATH . '/menus/assets/less/styles.less";';
}