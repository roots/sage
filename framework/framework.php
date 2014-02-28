<?php

global $ss_settings;

require_once dirname( __FILE__ ) . '/class-SS_Framework.php';

if ( ! defined( 'SS_FRAMEWORK' ) ) {
	$active_framework = $ss_settings['framework'];
} else {
	if ( SS_FRAMEWORK != $ss_settings['framework'] ) {
		$ss_settings['framework'] = SS_FRAMEWORK;
		update_option( SHOESTRAP_OPT_NAME, $ss_settings );
	}
}

$frameworks = apply_filters( 'shoestrap_frameworks_array', array() );

// Return the classname of the active framework.
foreach ( $frameworks as $framework ) {
	if ( $active_framework == $framework['shortname'] ) {
		$active   = $framework['classname'];
	}
}

global $ss_framework;
$ss_framework = new $active;