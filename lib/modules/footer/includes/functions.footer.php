<?php

if ( !function_exists( 'shoestrap_footer_icon' ) ) :
/*
 * Creates the customizer icon on the bottom-left corner of our site
 * (visible only by admins)
 */
function shoestrap_footer_icon() {
	global $wp_customize;

	if ( current_user_can( 'edit_theme_options' ) && !isset( $wp_customize ) ) : ?>
		<div id="shoestrap_icon" class="visible-lg">
			<a href="<?php echo admin_url( 'themes.php?page=shoestrap' ); ?>"><i class="icon el-icon-cogs"></i></a>
		</div>
	<?php endif; ?>
	</div>
	<?php
}
endif;
add_action( 'shoestrap_after_footer', 'shoestrap_footer_icon' );

