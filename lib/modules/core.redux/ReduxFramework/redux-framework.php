<?php
/**
 * The Redux Framework Plugin
 *
 * A great way to start using the Redux Framework immediately.
 * WordPress coding standards and PHP best practices have been kept.
 *
 * @package   ReduxFramework
 * @author    Dovy Paukstys <info@simplerain.com>
 * @license   GPL-2.0+
 * @link      http://simplerain.com
 * @copyright 2013 SimpleRain, Inc.
 *
 * @wordpress-plugin
 * Plugin Name: Redux Framework
 * Plugin URI:  http://wordpress.org/plugins/redux-framework/
 * Github URI:  https://github.com/ReduxFramework/ReduxFramework
 * Description: Redux is a simple, truly extensible options framework for WordPress themes and plugins.
 * Version:     3.0.1
 * Author:      ReduxFramework
 * Author URI:  http://reduxframework.com
 * Text Domain: redux-framework
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /ReduxFramework/lang
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once( plugin_dir_path( __FILE__ ) . 'class-redux-plugin.php' );

// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
register_activation_hook( __FILE__, array( 'ReduxFrameworkPlugin', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'ReduxFrameworkPlugin', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'ReduxFrameworkPlugin', 'get_instance' ) );