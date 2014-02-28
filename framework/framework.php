<?php

global $ss_settings;

require_once dirname( __FILE__ ) . '/class-SS_Framework.php';

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

global $ss_framework;
$ss_framework = new $active;