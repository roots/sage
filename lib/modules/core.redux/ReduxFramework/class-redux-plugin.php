<?php
/**
 * Redux Framework Plugin Class
 *
 * @package   ReduxFramework
 * @author    Dovy Paukstys <info@simplerain.com>
 * @license   GPL-2.0+
 * @link      http://simplerain.com
 * @copyright 2013 SimpleRain
 */

/**
 * Plugin class.
 *
 * TODO: Rename this class to a proper name for your plugin.
 *
 * @package ReduxFrameworkPlugin
 * @author  Dovy Paukstys <info@simplerain.com>
 */
/** @noinspection PhpUndefinedClassInspection */
/** @noinspection PhpUndefinedClassInspection */
class ReduxFrameworkPlugin {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	const VERSION = '1.0.0';

	/**
	 * Array of config options saved in the DB
	 *
	 * Used to determine if demo mode or nightly builds are activated.
	 *
	 * @since    1.0.0
	 *
	 * @var      array
	 */
	protected $options = array();		

	/**
	 * Unique identifier for your plugin.
	 *
	 * Use this value (not the variable name) as the text domain when internationalizing strings of text. It should
	 * match the Text Domain file header in the main plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'redux-framework';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Network activated plugins
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_network_activated = false;

	/**
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {


		$defaults = array(
						'demo'			=> false, 
						'nightly'		=> true,
						'api_refresh' 	=> false
					);
		// Grabbing the options if plugin is network activated
		if ( is_multisite() ) {
			$plugins = get_site_option( 'active_sitewide_plugins');
			foreach($plugins as $file => $k) {
				if ( strpos($file,'redux-framework.php') !== false ) {
					$this->plugin_network_activated = true;
					$this->options = get_site_option( 'REDUX_FRAMEWORK_PLUGIN', $defaults );
				}
			}
		}
		if ( empty($this->options) ) {
			$this->options = get_option( 'REDUX_FRAMEWORK_PLUGIN', $defaults );
		}

		// Load plugin text domain
		add_action( 'wp_loaded', array( $this, 'load_plugin_textdomain' ) );

		add_action( 'wp_loaded', array( $this, 'redux_options_toggle_check' )  );

		// Activate plugin when new blog is added
		add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

		add_action('admin_notices', array( $this, 'admin_notices' ) );

		add_filter( 'plugin_row_meta', array($this, 'ts_plugin_meta_links'), 10, 2 );

		if ( !class_exists( 'Redux_Framework' ) && file_exists( dirname( __FILE__ ) . '/ReduxCore/framework.php' ) ) {
			require_once( dirname( __FILE__ ) . '/ReduxCore/framework.php' );
		}

		
		if ($this->options['demo'] && file_exists( dirname( __FILE__ ) . '/sample/sample-config.php' ) ) {
			require_once( dirname( __FILE__ ) . '/sample/sample-config.php' );
		}

		// Include the Github Updater
		if ( !class_exists('Simple_Updater') && file_exists( dirname( __FILE__ ) . '/class-simple-updater.php') ) {
			include_once( dirname( __FILE__ ) . '/class-simple-updater.php' );
		}	

		if (class_exists('Simple_Updater')) {
			$config = array( 'slug' => dirname(__FILE__)."/redux-framework.php");
			if ( $this->options['nightly'] ) {
				$config['mode'] = "commits";
			}
			if ( !empty( $this->options['api_refresh'] ) && $this->options['api_refresh'] == true ) {
				unset($this->options['api_refresh']);
				delete_site_transient('update_plugins');
				update_option( 'REDUX_FRAMEWORK_PLUGIN', $this->options );
				$config['force_update'] = true;
			}

		  	$updater = new Simple_Updater( $config );

		}		

	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog.
	 */
	public static function activate( $network_wide ) {
		if ( function_exists( 'is_multisite' ) && is_multisite() ) {
			if ( $network_wide  ) {
				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {
					switch_to_blog( $blog_id );
					self::single_activate();
				}
				restore_current_blog();
			} else {
				self::single_activate();
			}
		} else {
			self::single_activate();
		}
		delete_site_transient('update_plugins');
	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Deactivate" action, false if WPMU is disabled or plugin is deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {
		if ( function_exists( 'is_multisite' ) && is_multisite() ) {
			if ( $network_wide ) {
				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {
					switch_to_blog( $blog_id );
					self::single_deactivate();
				}
				restore_current_blog();
			} else {
				self::single_deactivate();
			}
		} else {
			self::single_deactivate();
		}
		delete_option( 'REDUX_FRAMEWORK_PLUGIN');
	}

	/**
	 * Fired when a new site is activated with a WPMU environment.
	 *
	 * @since    1.0.0
	 *
	 * @param	int	$blog_id ID of the new blog.
	 */
	public function activate_new_site( $blog_id ) {
		if ( 1 !== did_action( 'wpmu_new_blog' ) )
			return;

		switch_to_blog( $blog_id );
		self::single_activate();
		restore_current_blog();
	}

	/**
	 * Get all blog ids of blogs in the current network that are:
	 * - not archived
	 * - not spam
	 * - not deleted
	 *
	 * @since    1.0.0
	 *
	 * @return	array|false	The blog ids, false if no matches.
	 */
	private static function get_blog_ids() {
		global $wpdb;

		// get an array of blog ids
		$sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";
        /** @noinspection PhpUndefinedMethodInspection */
        return $wpdb->get_col( $sql );
	}

	/**
	 * Fired for each blog when the plugin is activated.
	 *
	 * @since    1.0.0
	 */
	private static function single_activate() {
			$notices = get_option('REDUX_FRAMEWORK_PLUGIN_ACTIVATED_NOTICES', array());
			$notices[]= __("Redux Framework has an embedded demo.", 'redux-framework').' <a href="./plugins.php?redux_framework_plugin=demo">'.__("Click here to activate the sample config file.", 'redux-framework')."</a>";
			update_option('REDUX_FRAMEWORK_PLUGIN_ACTIVATED_NOTICES', $notices);			
	}


	public function admin_notices() {
		do_action('redux_framework_plugin_admin_notice');
		if ($notices= get_option('REDUX_FRAMEWORK_PLUGIN_ACTIVATED_NOTICES')) {
			foreach ($notices as $notice) {
				echo "<div class='updated'><p>$notice</p></div>";
			}
			delete_option('REDUX_FRAMEWORK_PLUGIN_ACTIVATED_NOTICES');
		}
	}

	/**
	 * Fired for each blog when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 */
	private static function single_deactivate() {
		delete_option('REDUX_FRAMEWORK_PLUGIN_ACTIVATED_NOTICES');
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, basename( dirname( __FILE__ ) ) . '/languages' );
	}


	/**
	 * Turn on or off
	 *
	 * @since    1.0.0
	 */
	public function redux_options_toggle_check() {
		global $pagenow;

		if ( $pagenow == "plugins.php" && is_admin() && !empty( $_GET['redux_framework_plugin'] ) ) {
			$url = "./plugins.php";

			if ( $_GET['redux_framework_plugin'] == 'demo') {
				if ( $this->options['demo'] == false ) {
					$this->options['demo'] = true;
					//$url = admin_url( 'admin.php?page=redux_sample_options');
				} else {
					$this->options['demo'] = false;
				}
			} else if ( $_GET['redux_framework_plugin'] == 'nightly') {
				if ( $this->options['nightly'] == false ) {
					$this->options['nightly'] = true;
				} else {
					$this->options['nightly'] = false;
				}
				$this->options['api_refresh'] = true;
			}	
			/*
			echo '<br />is_multisite() '.is_multisite();
			echo '<br />is_network_admin() '.is_network_admin();
			echo '<br />get_current_blog_id() '.get_current_blog_id();
			echo '<br />is_main_site() '.is_main_site();
			echo '<br />is_super_admin() '.is_super_admin();
			echo '<br />is_plugin_active() '.is_plugin_active(dirname(__FILE__)."/redux-framework.php");

			echo '<br />get_current_blog_id() '.get_current_blog_id();
			*/
			if ( is_multisite() && is_network_admin() && $this->plugin_network_activated ) {
				update_site_option( 'REDUX_FRAMEWORK_PLUGIN', $this->options );
			} else {
				update_option( 'REDUX_FRAMEWORK_PLUGIN', $this->options );	
			}
			wp_redirect( $url );

		}

	}	

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */
	public function add_action_links( $links ) {
		// In case we ever want to do that...
		return $links;
		/*
		return array_merge(
			array('redux_plugin_settings' => '<a href="' . admin_url( 'plugins.php?page=' . 'redux_plugin_settings' ) . '">' . __('Settings', 'redux-framework') . '</a>'),
			$links
		);
		*/
	}

	function ts_plugin_meta_links( $links, $file ) {
		if ( strpos($file,'redux-framework.php') === false ) {
    		return $links;
		}

	 	$extra = '<br /><span style="display: block; padding-top: 6px;">';
		
		if ($this->options['demo']) {
			$demoText = '<a href="./plugins.php?redux_framework_plugin=demo" style="color: #bc0b0b;">' . __( 'Deactivate Demo Mode', $this->plugin_slug ) . '</a>';
		} else {
			$demoText = '<a href="./plugins.php?redux_framework_plugin=demo">' . __( 'Activate Demo Mode', $this->plugin_slug ) . '</a>';
		}
		

		
		if ($this->options['nightly']) {
			$nightlyText = '<a href="./plugins.php?redux_framework_plugin=nightly" style="color: #bc0b0b;">' . __( 'Disable Nightly Updates', $this->plugin_slug ) . '</a>';
		} else {
			$nightlyText = '<a href="./plugins.php?redux_framework_plugin=nightly">' . __( 'Enable Nightly Updates', $this->plugin_slug ) . '</a>';
		}

		if ( is_multisite() && $this->plugin_network_activated || !is_network_admin() || !is_multisite()) {
			$extra .= $demoText;
		}
		
		if ( (is_multisite() && is_network_admin()) || !is_multisite() ) {
			if ( is_multisite() && $this->plugin_network_activated || !is_network_admin() || !is_multisite()) {
			$extra .= ' | ';
			}
			$extra .= $nightlyText;
		}

		$extra .='</span>';

		$plugin = str_replace('class-redux-plugin', 'redux-framework', plugin_basename(__FILE__));
		$array = array( '<a href="https://github.com/ReduxCore/ReduxFramework" target="_blank">Github Repo</a>', '<a href="https://github.com/ReduxCore/ReduxCore/issues/" target="_blank">Support Forum</a>'.$extra  );
		// create link
		if ( $file == $plugin ) {
			return array_merge(
				$links,
				$array
			);
		}
		return $links;
	 
	}	


}
