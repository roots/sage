<?php
/**
 * The Redux Framework Plugin
 *
 * A simple, truly extensible and fully responsive options framework 
 * for WordPress themes and plugins. Developed with WordPress coding
 * standards and PHP best practices in mind.
 *
 * Plugin Name:     Redux Framework
 * Plugin URI:      http://wordpress.org/plugins/redux-framework
 * Github URI:      https://github.com/ReduxFramework/ReduxFramework
 * Description:     Redux is a simple, truly extensible options framework for WordPress themes and plugins.
 * Author:          Redux Team
 * Author URI:      http://reduxframework.com
 * Version:         3.1.4
 * Text Domain:     redux-framework
 * License:         GPL2+
 * License URI:     http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:     /ReduxFramework/ReduxCore/languages
 *
 * @package         ReduxFramework
 * @author          Daniel J Griffiths <ghost1227@reduxframework.com>
 * @author          Dovy Paukstys <info@simplerain.com>
 * @author          Lee Mason <lee@reduxframework.com>
 * @license         GNU General Public License, version 2
 * @copyright       2012-2013 Redux Framework
 */


// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) {
    die;
}


// Require the main plugin class
require_once( plugin_dir_path( __FILE__ ) . 'class.redux-plugin.php' );

// Register hooks that are fired when the plugin is activated and deactivated, respectively.
register_activation_hook( __FILE__, array( 'ReduxFrameworkPlugin', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'ReduxFrameworkPlugin', 'deactivate' ) );

// Get plugin instance
add_action( 'plugins_loaded', array( 'ReduxFrameworkPlugin', 'instance' ) );
