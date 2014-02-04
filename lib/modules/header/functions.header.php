<?php

if ( !function_exists( 'shoestrap_branding' ) ) :
/*
 * The Header template
 */
function shoestrap_branding() { ?>
	<?php if ( shoestrap_getVariable( 'header_toggle' ) == 1 ) : ?>
		<?php if ( shoestrap_getVariable( 'site_style' ) == 'boxed' ) : ?>
			<div class="container">
		<?php endif; ?>

		<div class="header-wrapper">
			<?php if ( shoestrap_getVariable( 'site_style' ) == 'wide' ) : ?>
				<div class="container">
			<?php endif; ?>

			<?php if ( shoestrap_getVariable( 'header_branding' ) == 1 ) : ?>
				<a class="brand-logo" href="<?php echo home_url(); ?>/">
					<h1>
						<?php if ( function_exists( 'shoestrap_logo' ) ) : ?>
							<?php shoestrap_logo(); ?>
						<?php endif; ?>
					</h1>
				</a>
			<?php endif; ?>

			<?php if ( shoestrap_getVariable( 'header_branding' ) == 1 ) : ?>
				<div class="pull-right">
			<?php else : ?>
				<div>
			<?php endif; ?>

			<?php dynamic_sidebar( 'header-area' ); ?>
		</div>
		</div>

		<?php if ( shoestrap_getVariable( 'site_style' ) != 'fluid' ) : ?>
			</div>
		<?php endif; ?>

	<?php endif;
}
endif;
add_action( 'shoestrap_below_top_navbar', 'shoestrap_branding', 5 );

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
	$rgb      = shoestrap_get_rgb($bg, true);

	if ( shoestrap_getVariable( 'header_toggle' ) == 1 ) {
		$style = '.header-wrapper{ color: '.$cl.';';

		$style .= ( $opacity != 1 && $opacity != '' ) ? 'background: rgb('.$rgb.'); background: rgba('.$rgb.', '.$opacity.');' : $style .= 'background: '.$bg.';';
		$style .= 'margin-top:'.$header_margin_top.'px; margin-bottom:'.$header_margin_bottom.'px; }';

		wp_add_inline_style( 'shoestrap_css', $style );
	}
}
endif;
add_action( 'wp_enqueue_scripts', 'shoestrap_header_css', 101 );