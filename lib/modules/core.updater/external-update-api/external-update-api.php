<?php
/*
Plugin Name:  External Update API
Plugin URI:   https://github.com/cftp/external-update-api
Description:  Add support for updating themes and plugins via external sources instead of wordpress.org
Version:      0.3.5
Author:       Code for the People
Author URI:   http://codeforthepeople.com/
Text Domain:  euapi
Domain Path:  /languages/
License:      GPL v2 or later

Copyright © 2013 Code for the People Ltd

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

*/

defined( 'ABSPATH' ) or die();

/**
 * Class autoloader
 *
 * @author John Blackbourn
 * @param  string $class Class name
 * @return null
 */
function euapi_autoloader( $class ) {

	if ( 0 !== strpos( $class, 'EUAPI' ) )
		return;

	$name = str_replace( 'EUAPI_', '', $class );
	$name = str_replace( '_', '-', $name );
	$name = strtolower( $name );

	$file = sprintf( '%1$s/external-update-api/%2$s.php',
		dirname( __FILE__ ),
		$name
	);

	if ( is_readable( $file ) )
		include $file;

}

/**
 * Flush the site's plugin and theme update transients. Fired on activation and deactivation.
 *
 * @author John Blackbourn
 * @return null
 */
function euapi_flush_transients() {
	delete_site_transient( 'update_plugins' );
	delete_site_transient( 'update_themes' );
}
register_activation_hook( __FILE__,   'euapi_flush_transients' );
register_deactivation_hook( __FILE__, 'euapi_flush_transients' );

spl_autoload_register( 'euapi_autoloader' );

global $euapi;

$euapi = new EUAPI;
