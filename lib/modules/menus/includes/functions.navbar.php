<?php


/**
 * Modify the nav class.
 */
function shoestrap_nav_class() {
	return ( shoestrap_getVariable( 'navbar_nav_right' ) == '1' ) ? 'navbar-nav nav pull-right' : 'navbar-nav nav';
}
add_filter( 'shoestrap_nav_class', 'shoestrap_nav_class' );


if ( !function_exists( 'shoestrap_navbar_pre_searchbox' ) ) :
/*
 * The template for the primary navbar searchbox
 */
function shoestrap_navbar_pre_searchbox() {
	$show_searchbox = shoestrap_getVariable( 'navbar_search' );
	if ( $show_searchbox == '1' ) : ?>
		<form role="search" method="get" id="searchform" class="form-search pull-right navbar-form" action="<?php echo home_url('/'); ?>">
			<label class="hide" for="s"><?php _e('Search for:', 'shoestrap'); ?></label>
			<input type="text" value="<?php if (is_search()) { echo get_search_query(); } ?>" name="s" id="s" class="form-control search-query" placeholder="<?php _e('Search', 'shoestrap'); ?> <?php bloginfo('name'); ?>">
		</form>
		<?php
	endif;
}
endif;
add_action( 'shoestrap_inside_nav_begin', 'shoestrap_navbar_pre_searchbox', 11 );


if ( !function_exists( 'shoestrap_navbar_class' ) ) :
/**
 * Modify the navbar class.
 */
function shoestrap_navbar_class( $navbar = 'main') {
	$fixed    = shoestrap_getVariable( 'navbar_fixed' );
	$fixedpos = shoestrap_getVariable( 'navbar_fixed_position' );
	$style    = shoestrap_getVariable( 'navbar_style' );
	$toggle   = shoestrap_getVariable( 'navbar_toggle' );
	$left     = ( $toggle == 'left' ) ? true : false;

	$bp = shoestrap_static_left_breakpoint();

	$defaults = 'navbar navbar-default topnavbar';

	if ( $fixed != 1 )
		$class = ' navbar-static-top';
	else
		$class = ( $fixedpos == 1 ) ? ' navbar-fixed-bottom' : ' navbar-fixed-top';

	$class = $defaults . $class;

	if ( $left ) {
		$extra_classes = 'navbar navbar-default static-left ' . $bp .  ' col-' . $bp . '-' . shoestrap_getVariable( 'layout_secondary_width' );
		$class = $extra_classes;
	}

	if ( $navbar != 'secondary' )
		return $class . ' ' . $style;
	else
		return 'navbar ' . $style;
}
endif;
add_filter( 'shoestrap_navbar_class', 'shoestrap_navbar_class' );


if ( !function_exists( 'shoestrap_static_left_breakpoint' ) ) :
/**
 * Modify the grid-float-breakpoint using Bootstrap classes.
 */
function shoestrap_static_left_breakpoint() {
	$break    = shoestrap_getVariable( 'grid_float_breakpoint' );

	$bp = ( $break == 'min' || $break == 'screen_xs_min' ) ? 'xs' : 'xs';
	$bp = ( $break == 'screen_sm_min' )                    ? 'sm' : $bp;
	$bp = ( $break == 'screen_md_min' )                    ? 'md' : $bp;
	$bp = ( $break == 'screen_lg_min' || $break == 'max' ) ? 'lg' : $bp;

	return $bp;
}
endif;


if ( !function_exists( 'shoestrap_navbar_css' ) ) :
/**
 * Add some CSS for the navbar when needed.
 */
function shoestrap_navbar_css() {
	$navbar_bg_opacity = shoestrap_getVariable( 'navbar_bg_opacity' );
	$style = "";

	$opacity = ( $navbar_bg_opacity == '' ) ? '0' : ( intval( $navbar_bg_opacity ) ) / 100;

	if ( $opacity != 1 && $opacity != '' ) {
		$bg  = str_replace( '#', '', shoestrap_getVariable( 'navbar_bg' ) );
		$rgb = shoestrap_get_rgb( $bg, true );
		$opacityie = str_replace( '0.', '', $opacity );

		$style .= '.navbar, .navbar-default {';

		if ( $opacity != 1 && $opacity != '')
			$style .= 'background: transparent; background: rgba('.$rgb.', '.$opacity.'); filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#'.$opacityie.$bg.',endColorstr=#'.$opacityie.$bg.'); ;';
		else
			$style .= 'background: #'.$bg.';';

		$style .= '}';

	}

	if ( shoestrap_getVariable( 'navbar_margin' ) != 1 )
		$style .= '.navbar-static-top { margin-top:'. shoestrap_getVariable( 'navbar_margin' ) .'px !important; margin-bottom:'. shoestrap_getVariable( 'navbar_margin' ) .'px !important; }';

	wp_add_inline_style( 'shoestrap_css', $style );
}
endif;
add_action( 'wp_enqueue_scripts', 'shoestrap_navbar_css', 101 );


/**
 * Will the sidebar be shown?
 * If yes, then which navbar?
 */
function shoestrap_do_navbar() {
	$navbar_toggle = shoestrap_getVariable( 'navbar_toggle' );

	if ( $navbar_toggle != 'none' ) {
		if ( $navbar_toggle != 'pills' ) {
			if ( !has_action( 'shoestrap_header_top_navbar_override' ) )
				get_template_part( 'templates/header-top-navbar' );
			else
				do_action( 'shoestrap_header_top_navbar_override' );
		} else {
			if ( !has_action( 'shoestrap_header_override' ) )
				get_template_part( 'templates/header' );
			else
				do_action( 'shoestrap_header_override' );
		}
	} else {
		return '';
	}
}
add_action( 'shoestrap_do_navbar', 'shoestrap_do_navbar' );


add_action( 'shoestrap_do_navbar', 'shoestrap_static_left_main_wrapper_open', 97 );
/**
 * When the navbar is set to static-left, we need to add some wrappers
 */
function shoestrap_static_left_main_wrapper_open() {
	$left = ( shoestrap_getVariable( 'navbar_toggle' ) == 'left' ) ? true : false;

	if ( $left )
		echo '<section class="static-menu-main ' . shoestrap_static_left_breakpoint() . ' col-static-' . ( 12 - shoestrap_getVariable( 'layout_secondary_width' ) ) . '">';
}


/**
 * Close the wrapper div that the 'shoestrap_static_left_main_wrapper_open' opens.
 */
function shoestrap_static_left_main_wrapper_close() {
	$left = ( shoestrap_getVariable( 'navbar_toggle' ) == 'left' ) ? true : false;

	if ( $left )
		echo '</section>';
}
add_action( 'shoestrap_after_footer', 'shoestrap_close_boxed_container_div', 901 );


/**
 * get the navbar branding options (if the branding module exists)
 * and then add the appropriate logo or sitename.
 */
function shoestrap_navbar_brand() {
	// Make sure the branding module exists.
	if ( function_exists( 'shoestrap_logo' ) ) {
		$logo           = shoestrap_getVariable( 'logo' );
		$branding_class = !empty( $logo['url'] ) ? 'logo' : 'text';

		if ( shoestrap_getVariable( 'navbar_brand' ) != 0 ) {
			$branding  = '<a class="navbar-brand ' . $branding_class . '" href="' . home_url('/') . '">';
			$branding .= shoestrap_getVariable( 'navbar_logo' ) == 1 ? shoestrap_logo() : get_bloginfo( 'name' );
			$branding .= '</a>';
		} else {
			$branding = '';
		}
	} else {
		// If the branding module does not exist, return the defaults.
		$branding = '<a class="navbar-brand text" href="' . home_url('/') . '">' . get_bloginfo( 'name' ) . '</a>';
	}

	return $branding;
}
add_filter( 'shoestrap_navbar_brand', 'shoestrap_navbar_brand' );


/**
 * Add and remove body_class() classes
 */
function shoestrap_navbar_body_class( $classes ) {
	// Add 'top-navbar' or 'bottom-navabr' class if using Bootstrap's Navbar
	// Used to add styling to account for the WordPress admin bar
	if ( shoestrap_getVariable( 'navbar_fixed' ) == 1 && shoestrap_getVariable( 'navbar_fixed_position' ) != 1 )
		$classes[] = 'top-navbar';
	elseif ( shoestrap_getVariable( 'navbar_fixed' ) == 1 && shoestrap_getVariable( 'navbar_fixed_position' ) == 1 )
		$classes[] = 'bottom-navbar';

	return $classes;
}
add_filter( 'body_class', 'shoestrap_navbar_body_class' );
