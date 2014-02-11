<?php

if ( !function_exists( 'shoestrap_getLayout' ) ) :
/*
 * Get the layout value, but only set it once!
 */
function shoestrap_getLayout() {
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
endif;


if ( !function_exists( 'shoestrap_setLayout' ) ) :
/*
 *Override the layout value globally
 */
function shoestrap_setLayout( $val ) {
	global $shoestrap_layout, $redux;
	$shoestrap_layout = intval( $val );
}
endif;


if ( !function_exists( 'shoestrap_section_class_extended' ) ) :
/*
 * Calculates the classes of the main area, main sidebar and secondary sidebar
 */
function shoestrap_section_class_extended( $target, $echo = false ) {
	global $redux;
	
	$layout = shoestrap_getLayout();
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
endif;

add_filter( 'shoestrap_section_class_wrapper', 'shoestrap_apply_layout_classes_wrapper' );
function shoestrap_apply_layout_classes_wrapper() {
	return shoestrap_section_class_extended( 'wrapper' );
}


add_filter( 'shoestrap_section_class_main', 'shoestrap_apply_layout_classes_main' );
function shoestrap_apply_layout_classes_main() {
	return shoestrap_section_class_extended( 'main' );
}


add_filter( 'shoestrap_section_class_primary', 'shoestrap_apply_layout_classes_primary' );
function shoestrap_apply_layout_classes_primary() {
	return shoestrap_section_class_extended( 'primary' );
}


add_filter( 'shoestrap_section_class_secondary', 'shoestrap_apply_layout_classes_secondary' );
function shoestrap_apply_layout_classes_secondary() {
	return shoestrap_section_class_extended( 'secondary' );
}


if ( !function_exists( 'shoestrap_layout_body_class' ) ) :
/**
 * Add and remove body_class() classes to accomodate layouts
 */
function shoestrap_layout_body_class( $classes ) {
	$layout     = shoestrap_getLayout();
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
endif;
add_filter( 'body_class', 'shoestrap_layout_body_class' );


if ( !function_exists( 'shoestrap_container_class' ) ) :
/*
 * Return the container class
 */
function shoestrap_container_class() {
	$class = shoestrap_getVariable( 'site_style' ) != 'fluid' ? 'container' : 'fluid';

	return $class;
}
add_filter( 'shoestrap_container_class', 'shoestrap_container_class' );
endif;


if ( !function_exists( 'shoestrap_navbar_container_class' ) ) :
/*
 * Return the container class
 */
function shoestrap_navbar_container_class() {
	$site_style = shoestrap_getVariable( 'site_style' );
	$toggle     = shoestrap_getVariable( 'navbar_toggle' );

	if ( $toggle == 'full' )
		$class = 'fluid';
	else
		$class = ( $site_style != 'fluid' ) ? 'container' : 'fluid';

	return $class;
}
endif;
add_filter( 'shoestrap_navbar_container_class', 'shoestrap_navbar_container_class' );


if ( !function_exists( 'shoestrap_content_width_px' ) ) :
/*
 * Calculate the width of the content area in pixels.
 */
function shoestrap_content_width_px( $echo = false ) {
	global $redux;

	$layout = shoestrap_getLayout();

	$container  = filter_var( shoestrap_getVariable( 'screen_large_desktop' ), FILTER_SANITIZE_NUMBER_INT );
	$gutter     = filter_var( shoestrap_getVariable( 'layout_gutter' ), FILTER_SANITIZE_NUMBER_INT );

	$main_span  = filter_var( shoestrap_section_class_extended( 'main', false ), FILTER_SANITIZE_NUMBER_INT );
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
endif;


if ( !function_exists( 'shoestrap_content_width' ) ) :
/*
 * Set the content width
 */
function shoestrap_content_width() {
	global $content_width;
	$content_width = shoestrap_content_width_px();
}
endif;
add_action( 'template_redirect', 'shoestrap_content_width' );


if ( !function_exists( 'shoestrap_body_margin' ) ) :
/*
 * Body Margins
 */
function shoestrap_body_margin() {
	$body_margin_top = shoestrap_getVariable( 'body_margin_top' );
	$body_margin_bottom = shoestrap_getVariable( 'body_margin_bottom' );

	$style = 'body { margin-top:'. $body_margin_top .'px; margin-bottom:'. $body_margin_bottom .'px; }';

	wp_add_inline_style( 'shoestrap_css', $style );
}
endif;

if ( ( shoestrap_getVariable( 'body_margin_top' ) != '0' ) || ( shoestrap_getVariable( 'body_margin_bottom' ) != '0' ) )
	add_action( 'wp_enqueue_scripts', 'shoestrap_body_margin', 101 );


function shoestrap_boxed_container_div() {
	if ( shoestrap_getVariable( 'site_style' ) == 'boxed' ) echo '<div class="container boxed-container">';
}
add_action( 'get_header', 'shoestrap_boxed_container_div', 1 );
add_action( 'shoestrap_pre_footer', 'shoestrap_boxed_container_div', 1 );

function shoestrap_close_boxed_container_div() {
	if ( shoestrap_getVariable( 'site_style' ) == 'boxed' ) echo '</div>';
}
add_action( 'shoestrap_do_navbar', 'shoestrap_close_boxed_container_div', 99 );
add_action( 'shoestrap_after_footer', 'shoestrap_close_boxed_container_div', 899 );


function shoestrap_control_primary_sidebar_display() {
	$layout_sidebar_on_front = shoestrap_getVariable( 'layout_sidebar_on_front' );

	if ( shoestrap_getLayout() == 0 )
		add_filter( 'shoestrap_display_primary_sidebar', 'shoestrap_return_false' );

	if ( is_front_page() && $layout_sidebar_on_front == 1 && shoestrap_getLayout() != 0 )
		add_filter( 'shoestrap_display_primary_sidebar', 'shoestrap_return_true' );

	if ( !is_front_page() || ( is_front_page() && $layout_sidebar_on_front == 1 ) )
		add_filter( 'shoestrap_display_primary_sidebar', 'shoestrap_return_true' );

}
add_action( 'wp', 'shoestrap_control_primary_sidebar_display' );


function shoestrap_control_secondary_sidebar_display() {
	$layout_sidebar_on_front = shoestrap_getVariable( 'layout_sidebar_on_front' );

	if ( shoestrap_getLayout() < 3 )
		add_filter( 'shoestrap_display_secondary_sidebar', 'shoestrap_return_false' );

	if ( ( !is_front_page() && shoestrap_display_secondary_sidebar() ) || ( is_front_page() && $layout_sidebar_on_front == 1 && shoestrap_getLayout() >= 3 ) )
		add_filter( 'shoestrap_display_secondary_sidebar', 'shoestrap_return_true' );

}
add_action( 'wp', 'shoestrap_control_secondary_sidebar_display' );


add_action( 'after_setup_theme', 'shoestrap_alter_widgets' );
function shoestrap_alter_widgets() {
	$widgets_mode = shoestrap_getVariable( 'widgets_mode' );

	if ( $widgets_mode == 0 || $widgets_mode == 1 ) {
		add_filter( 'shoestrap_widgets_class', 'shoestrap_alter_widgets_class' );
		add_filter( 'shoestrap_widgets_before_title', 'shoestrap_alter_widgets_before_title' );
		add_filter( 'shoestrap_widgets_after_title', 'shoestrap_alter_widgets_after_title' );
	}
}

function shoestrap_alter_widgets_class() {
	return shoestrap_getVariable( 'widgets_mode' ) == 0 ? 'panel panel-default' : 'well';
}

function shoestrap_alter_widgets_before_title() {
	return shoestrap_getVariable( 'widgets_mode' ) == 0 ? '<div class="panel-heading">' : '<h3 class="widget-title">';
}

function shoestrap_alter_widgets_after_title() {
	return shoestrap_getVariable( 'widgets_mode' ) == 0 ? '</div><div class="panel-body">' : '</h3>';
}

function shoestrap_static_meta() {
	if ( shoestrap_getVariable( 'site_style' ) != 'static' ) : ?>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<?php
	endif;
}
add_action( 'wp_head', 'shoestrap_static_meta' );