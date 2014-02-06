<?php

add_filter( 'shoestrap_compiler', 'shoestrap_admin_widgets_styles' );
function shoestrap_admin_widgets_styles( $bootstrap ) {
	return $bootstrap . '
	@import "' . get_template_directory() . '/lib/modules/widgets/styles.less";';
}