<?php

/**
 * Register sidebars and widgets
 */
function shoestrap_header_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Header Area', 'shoestrap' ),
		'id'            => 'header-area',
		'before_widget' => '<div class="container">',
		'after_widget'  => '</div>',
		'before_title'  => '<h1>',
		'after_title'   => '</h1>',
	));
}
add_action( 'widgets_init', 'shoestrap_header_widgets_init', 30 );


if ( !function_exists( 'shoestrap_branding' ) ) :
/*
 * The Header template
 */
function shoestrap_branding() {
	if ( shoestrap_getVariable( 'header_toggle' ) == 1 ) { ?>
		<div class="before-main-wrapper">

			<?php if ( shoestrap_getVariable( 'site_style' ) == 'boxed' ) : ?>
				<div class="container">
			<?php endif; ?>

				<div class="header-wrapper">

					<?php if ( shoestrap_getVariable( 'site_style' ) == 'wide' ) : ?>
						<div class="container">
					<?php endif; ?>

						<?php if ( shoestrap_getVariable( 'header_branding' ) == 1 ) : ?>
							<a class="brand-logo" href="<?php echo home_url(); ?>/">
								<h1><?php if ( function_exists( 'shoestrap_logo' ) ) echo shoestrap_logo(); ?></h1>
							</a>
						<?php endif; ?>

						<?php $pullclass = ( shoestrap_getVariable( 'header_branding' ) == 1 ) ? ' class="pull-right"' : ''; ?>

						<div<?php echo $pullclass; ?>>
							<?php dynamic_sidebar( 'header-area' ); ?>
						</div >

					<?php if ( shoestrap_getVariable( 'site_style' ) == 'wide' ) : ?>
						</div>
					<?php endif; ?>
				</div>

			<?php if ( shoestrap_getVariable( 'site_style' ) == 'boxed' ) : ?>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}
}
endif;
add_action( 'shoestrap_pre_wrap', 'shoestrap_branding', 3 );

if ( !function_exists( 'shoestrap_header_css' ) ) :
/*
 * Any necessary extra CSS is generated here
 */
function shoestrap_header_css() {
	$bg = shoestrap_getVariable( 'header_bg' );
	$cl = shoestrap_getVariable( 'header_color' );
	
	$header_margin_top    = shoestrap_getVariable( 'header_margin_top' );
	$header_margin_bottom = shoestrap_getVariable( 'header_margin_bottom' );
	
	$opacity  = (intval(shoestrap_getVariable( 'header_bg_opacity' )))/100;
	$rgb      = ShoestrapColor::get_rgb( $bg, true );

	if ( shoestrap_getVariable( 'header_toggle' ) == 1 ) {
		$style = '.header-wrapper{ color: '.$cl.';';

		$style .= ( $opacity != 1 && $opacity != '' ) ? 'background: rgb('.$rgb.'); background: rgba('.$rgb.', '.$opacity.');' : 'background: '.$bg.';';
		$style .= 'margin-top:'.$header_margin_top.'px; margin-bottom:'.$header_margin_bottom.'px; }';

		wp_add_inline_style( 'shoestrap_css', $style );
	}
}
endif;
add_action( 'wp_enqueue_scripts', 'shoestrap_header_css', 101 );