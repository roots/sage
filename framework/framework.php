<?php

global $ss_settings;

require_once dirname( __FILE__ ) . '/class-SS_Framework.php';
do_action( 'shoestrap_include_frameworks' );

if ( ! defined( 'SS_FRAMEWORK' ) ) {
	if ( ! is_null( $ss_settings['framework'] ) && ! empty( $ss_settings['framework'] ) ) {
		$active_framework = $ss_settings['framework'];
	} else {
		$active_framework = 'bootstrap';
	}
} else {
	if ( SS_FRAMEWORK != $ss_settings['framework'] ) {
		$ss_settings['framework'] = SS_FRAMEWORK;
		update_option( SHOESTRAP_OPT_NAME, $ss_settings );
	}
	$active_framework = SS_FRAMEWORK;
}

$frameworks = apply_filters( 'shoestrap_frameworks_array', array() );

// Return the classname of the active framework.
foreach ( $frameworks as $framework ) {
	if ( $active_framework == $framework['shortname'] ) {
		$active   = $framework['classname'];
	}
}

if ( ! class_exists( 'SS_Framework_Bootstrap' ) ) {
	require_once dirname( __FILE__ ) . '/bootstrap/class-SS_Framework_Bootstrap.php';
}

if ( ! isset( $active ) ) {
	$active = 'SS_Framework_Bootstrap';
}

global $ss_framework;
$ss_framework = new $active;

/**
 * Remove the demo link and the notice of integrated demo from the redux-framework plugin
 */
function shoestrap_remove_demo() {

	// Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
	if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
		remove_filter( 'plugin_row_meta', array( ReduxFrameworkPlugin::instance(), 'plugin_metalinks' ), null, 2 );

		// Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
		remove_action( 'admin_notices', array( ReduxFrameworkPlugin::instance(), 'admin_notices' ) );
	}
}
add_action( 'redux/loaded', 'shoestrap_remove_demo' );
