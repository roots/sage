<?php

add_filter( 'shoestrap_compiler', 'shoestrap_admin_widgets_styles' );
function shoestrap_admin_widgets_styles( $bootstrap ) {
	return $bootstrap . '
	@import "' . SHOESTRAP_MODULES_PATH . '/widgets/assets/less/styles.less";';
}