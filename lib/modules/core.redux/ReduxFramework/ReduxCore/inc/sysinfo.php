<?php
/**
 * Simple System Info
 *
 * @package     Simple System Info
 * @author      Daniel J Griffiths
 * @since       1.0.0
 */


// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;


// We need to make sure plugin.php is loaded!
require_once ABSPATH . 'wp-admin/includes/plugin.php';


if( !class_exists( 'Simple_System_Info' ) ) {

    /**
     * Main System Info class
     *
     * @author      Daniel J Griffiths
     * @since       1.0.0
     */
    class Simple_System_Info {

        /**
         * Get system info
         *
         * Returns the system info for a WordPress instance
         * 
         * @access      public
         * @author      Daniel J Griffiths
         * @since       1.0.0
         * @global      $wpdb
         * @global      object $wpdb Used to query the database
         * @param       bool $show_inactive Whether or not to show inactive plugins
         * @param       string $id The ID to assign to the returned textarea (Default: system-info-box)
         * @param       string $class The class to assign to the returned textarea (Default: none)
         * @return      string $return A string containing all system info
         */
        public function get( $show_inactive = false, $id = 'system-info-box', $class = null ) {
            global $wpdb;

            if( !defined( 'SSINFO_VERSION' ) )
                define( 'SSINFO_VERSION', '1.0.0' );

            // We need the Browser class!
            if( !class_exists( 'Browser' ) )
                require_once 'browser.php';

            $browser = new Browser();

            // Get theme info for this WordPress version
            $theme_data = wp_get_theme();
            $theme      = $theme_data->Name . ' ' . $theme_data->Version;
            
            $return = '<textarea readonly="readonly" onclick="this.focus(); this.select()" id="' . $id . '"' . ( $class != null ? ' class="' . $class . '"' : '' ) . ' title="To copy the system info, click below and press Ctrl+C (PC) or Cmd+C (Mac).">';

            $return .= '### Begin System Info ###' . "\n\n";

            do_action( 'simple_system_info_before' );

            // Start with the basice...
            $return .= '-- Site Info' . "\n\n";
            $return .= 'Site URL:                 ' . site_url() . "\n";
            $return .= 'Home URL:                 ' . home_url() . "\n";
            $return .= 'Multisite:                ' . ( is_multisite() ? 'Yes' : 'No' ) . "\n";

            if( has_filter( 'ssi_after_site_info' ) )
                $return .= apply_filters( 'ssi_after_site_info', $return );

            // The local users' browser information, handled by the Browser class
            $return .= "\n" . '-- User Browser' . "\n\n";
            $return .= $browser;

            if( has_filter( 'ssi_after_user_browser' ) )
                $return .= apply_filters( 'ssi_after_user_browser', $return );

            // WordPress configuration
            $return .= "\n" . '-- WordPress Configuration' . "\n\n";
            $return .= 'Version:                  ' . get_bloginfo( 'version' ) . "\n";
            $return .= 'Permalink Structure:      ' . ( get_option( 'permalink_structure' ) ? get_option( 'permalink_structure' ) : 'Default' ) . "\n";
            $return .= 'Active Theme:             ' . $theme . "\n";
            $return .= 'Show On Front:            ' . get_option( 'show_on_front' ) . "\n";

            // Only show page specs if frontpage is set to 'page'
            if( get_option( 'show_on_front' ) == 'page' ) {
                $front_page_id = get_option( 'page_on_front' );
                $blog_page_id = get_option( 'page_for_posts' );

                $return .= 'Page On Front:            ' . ( $front_page_id != 0 ? get_the_title( $front_page_id ) . ' (#' . $front_page_id . ')' : 'Unset' ) . "\n";
                $return .= 'Page For Posts:           ' . ( $blog_page_id != 0 ? get_the_title( $blog_page_id ) . ' (#' . $blog_page_id . ')' : 'Unset' ) . "\n";
            }

            // Make sure wp_remote_post() is working
            $request['cmd'] = '_notify-validate';

            $params = array(
                'sslverify'     => false,
                'timeout'       => 60,
                'user-agent'    => 'SSInfo/' . SSINFO_VERSION,
                'body'          => $request
            );

            $response = wp_remote_post( 'https://www.paypal.com/cgi-bin/webscr', $params );

            if( !is_wp_error( $response ) && $response['response']['code'] >= 200 && $response['response']['code'] < 300 ) {
                $WP_REMOTE_POST = 'wp_remote_post() works';
            } else {
                $WP_REMOTE_POST = 'wp_remote_post() does not work';
            }

            $return .= 'Remote Post:              ' . $WP_REMOTE_POST . "\n";
            $return .= 'Table Prefix:             ' . 'Length: ' . strlen( $wpdb->prefix ) . '   Status: ' . ( strlen( $wpdb->prefix ) > 16 ? 'ERROR: Too long' : 'Acceptable' ) . "\n";
            $return .= 'WP_DEBUG:                 ' . ( defined( 'WP_DEBUG' ) ? WP_DEBUG ? 'Enabled' : 'Disabled' : 'Not set' ) . "\n";
            $return .= 'Memory Limit:             ' . WP_MEMORY_LIMIT . "\n";

            if( has_filter( 'ssi_after_wordpress_config' ) )
                $return .= apply_filters( 'ssi_after_wordpress_config', $return );

            // WordPress active plugins
            $return .= "\n" . '-- WordPress Active Plugins' . "\n\n";

            $plugins = get_plugins();
            $active_plugins = get_option( 'active_plugins', array() );

            foreach( $plugins as $plugin_path => $plugin ) {
                if( !in_array( $plugin_path, $active_plugins ) )
                    continue;

                //if( $plugin['Name'] !== 'Redux Framework' ) continue;

                $return .= $plugin['Name'] . ': ' . $plugin['Version'] . "\n";
            }

            if( has_filter( 'ssi_after_wordpress_plugins' ) )
                $return .= apply_filters( 'ssi_after_wordpress_plugins', $return );

            // WordPress inactive plugins
            if( $show_inactive == true ) {
                $return .= "\n" . '-- WordPress Inactive Plugins' . "\n\n";

                foreach( $plugins as $plugin_path => $plugin ) {
                    if( in_array( $plugin_path, $active_plugins ) )
                        continue;

                    $return .= $plugin['Name'] . ': ' . $plugin['Version'] . "\n";
                }
            }

            if( has_filter( 'ssi_after_wordpress_plugins_inactive' ) )
                $return .= apply_filters( 'ssi_after_wordpress_plugins_inactive', $return );

            // WordPress Multisite active plugins
            if( is_multisite() ) {
                $return .= "\n" . '-- Network Active Plugins' . "\n\n";
        
                $plugins = wp_get_active_network_plugins();
                $active_plugins = get_site_option( 'active_sitewide_plugins', array() );

                foreach( $plugins as $plugin_path ) {
                    $plugin_base = plugin_basename( $plugin_path );

                    if( !array_key_exists( $plugin_base, $active_plugins ) )
                        continue;

                    $return .= $plugin['Name'] . ': ' . $plugin['Version'] . "\n";
                }
                
                if( has_filter( 'ssi_after_wordpress_ms_plugins' ) )
                    $return .= apply_filters( 'ssi_after_wordpress_ms_plugins', $return );

                // WordPress Multisite inactive plugins
                if( $show_inactive == true ) {
                    $return .= "\n" . '-- Network Inactive Plugins' . "\n\n";
        
                    foreach( $plugins as $plugin_path ) {
                        $plugin_base = plugin_basename( $plugin_path );

                        if( array_key_exists( $plugin_base, $active_plugins ) )
                            continue;

                        $return .= $plugin['Name'] . ': ' . $plugin['Version'] . "\n";
                    }
                }    
            }
            
            if( has_filter( 'ssi_after_wordpress_ms_plugins_inactive' ) )
                $return .= apply_filters( 'ssi_after_wordpress_ms_plugins_inactive', $return );

            // Server configuration (really just versioning)
            $return .= "\n" . '-- Webserver Configuration' . "\n\n";
            $return .= 'PHP Version:              ' . PHP_VERSION . "\n";
            $return .= 'MySQL Version:            ' . mysql_get_server_info() . "\n";
            $return .= 'Webserver Info:           ' . $_SERVER['SERVER_SOFTWARE'] . "\n";

            if( has_filter( 'ssi_after_webserver_config' ) )
                $return .= apply_filters( 'ssi_after_webserver_config', $return );

            // PHP configs... now we're getting to the important stuff
            $return .= "\n" . '-- PHP Configuration' . "\n\n";
            $return .= 'Safe Mode:                ' . ( ini_get( 'safe_mode' ) ? 'Yes' : 'No' ) . "\n";
            $return .= 'Memory Limit:             ' . ini_get( 'memory_limit' ) . "\n";
            $return .= 'Upload Max Size:          ' . ini_get( 'upload_max_filesize' ) . "\n";
            $return .= 'Post Max Size:            ' . ini_get( 'post_max_size' ) . "\n";
            $return .= 'Upload Max Filesize:      ' . ini_get( 'upload_max_filesize' ) . "\n";
            $return .= 'Time Limit:               ' . ini_get( 'max_execution_time' ) . "\n";
            $return .= 'Max Input Vars:           ' . ini_get( 'max_input_vars' ) . "\n";
            $return .= 'Display Errors:           ' . ( ini_get( 'display_errors' ) ? 'On (' . ini_get( 'display_errors' ) . ')' : 'N/A' ) . "\n";

            if( has_filter( 'ssi_after_php_config' ) )
                $return .= apply_filters( 'ssi_after_php_config', $return );

            // PHP extensions and such
            $return .= "\n" . '-- PHP Extensions' . "\n\n";
            $return .= 'cURL:                     ' . ( function_exists( 'curl_init' ) ? 'Supported' : 'Not Supported' ) . "\n";
            $return .= 'fsockopen:                ' . ( function_exists( 'fsockopen' ) ? 'Supported' : 'Not Supported' ) . "\n";
            $return .= 'SOAP Client:              ' . ( class_exists( 'SoapClient' ) ? 'Installed' : 'Not Installed' ) . "\n";
            $return .= 'Suhosin:                  ' . ( extension_loaded( 'suhosin' ) ? 'Installed' : 'Not Installed' ) . "\n";

            if( has_filter( 'ssi_after_php_ext' ) )
                $return .= apply_filters( 'ssi_after_php_ext', $return );

            // Session stuff
            $return .= "\n" . '-- Session Configuration' . "\n\n";
            $return .= 'Session:                  ' . ( isset( $_SESSION ) ? 'Enabled' : 'Disabled' ) . "\n";

            // The rest of this is only relevant is session is enabled
            if( isset( $_SESSION ) ) {
                $return .= 'Session Name:             ' . esc_html( ini_get( 'session.name' ) ) . "\n";
                $return .= 'Cookie Path:              ' . esc_html( ini_get( 'session.cookie_path' ) ) . "\n";
                $return .= 'Save Path:                ' . esc_html( ini_get( 'session.save_path' ) ) . "\n";
                $return .= 'Use Cookies:              ' . ( ini_get( 'session.use_cookies' ) ? 'On' : 'Off' ) . "\n";
                $return .= 'Use Only Cookies:         ' . ( ini_get( 'session.use_only_cookies' ) ? 'On' : 'Off' ) . "\n";
            }

            if( has_filter( 'ssi_after_session_config' ) )
                $return .= apply_filters( 'ssi_after_session_config', $return );

            do_action( 'ssi_after' );
            
            $return .= "\n" . '### End System Info ###';

            $return .= '</textarea>';
        
            return $return;
        }
    }
}
