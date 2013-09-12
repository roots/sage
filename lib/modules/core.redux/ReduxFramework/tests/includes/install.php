<?php
/**
 * Installs Redux Framework for the purpose of the unit-tests
 */
error_reporting( E_ALL & ~E_DEPRECATED & ~E_STRICT );

echo "Welcome to the Redux Framework Test Suite" . PHP_EOL;
echo "Version: 1.0" . PHP_EOL;

$config_file_path = $argv[1];
$multisite = ! empty( $argv[2] );

require_once $config_file_path;
require_once dirname( $config_file_path ) . '/includes/functions.php';

// Always load admin bar
tests_add_filter( 'show_admin_bar', '__return_true' );

$_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.1';
$_SERVER['HTTP_HOST'] = WP_TESTS_DOMAIN;
$PHP_SELF = $GLOBALS['PHP_SELF'] = $_SERVER['PHP_SELF'] = '/index.php';

require_once ABSPATH . '/wp-settings.php';

global $current_user;
$current_user = new WP_User(1);
$current_user->set_role('administrator');
