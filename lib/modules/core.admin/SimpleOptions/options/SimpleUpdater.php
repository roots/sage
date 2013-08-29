<?php

// Include automated updating if framework is in a plugin
//if (strpos(dirname(__FILE__),WP_PLUGIN_DIR) !== false) {
//	define('INCLUDE_TYPE', "plugin");
//} else if (strpos(dirname(__FILE__),TEMPLATEPATH) !== false) {
	//define('INCLUDE_TYPE', "theme");
//	return; // For now...
//}

/**
 *
 * @version 1.0.0
 * @author Dovy Paukstys <info@simplerain.com>
 * @link http://simplerain.com
 * @package SimpleUpdater
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @copyright Copyright (c) 2013, SimpleRain
 *
 */

if ( ! class_exists( 'Simple_Updater' ) ):

	class Simple_Updater {

		/**
		 * Simple Updater version
		 */
		const VERSION = '1.0.0';

		/**
		 * @var $config the config for the updater
		 * @access public
		 */
		var $config;

		/**
		 * @var $missing_config any config that is missing from the initialization of this instance
		 * @access public
		 */
		var $missing_config;

		/**
		 * @var $github_data temporiraly store the data fetched from GitHub, allows us to only load the data once per class instance
		 * @access private
		 */
		private $github_data;


		/**
		 * Class Constructor
		 *
		 * @since 1.0
		 * @param array $config the configuration required for the updater to work
		 * @see has_minimum_config()
		 * @return void
		 */
		public function __construct( $config = array() ) {

			$defaults = array(
				'slug' 								=> '',
				'proper_folder_name' 	=> '',
				'access_token' 				=> '',
				'github_url' 					=> '',
				'sslverify' 					=> false,
				'github_user' 				=> '',
				'force_update' 				=> false,
				'github_repo' 				=> '',
			);

			$this->config = wp_parse_args( $config, $defaults );

			$this->set_defaults();

			// if the minimum config isn't set, issue a warning and bail
			if ( ! $this->has_minimum_config() ) {
				$message = 'SimpleUpdater was initialized without the minimum required configuration, please check the config in your plugin. The following params are missing: ';
				$message .= implode( ',', $this->missing_config );
				_doing_it_wrong( __CLASS__, $message , self::VERSION );
				return;
				echo $message;
			}

			add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'api_check' ) );

			// Hook into the plugin details screen
			add_filter( 'plugins_api', array( $this, 'get_plugin_info' ), 10, 3 );
			add_filter( 'upgrader_post_install', array( $this, 'upgrader_post_install' ), 10, 3 );

			// set timeout
			add_filter( 'http_request_timeout', array( $this, 'http_request_timeout' ) );

			// set sslverify for zip download
			add_filter( 'http_request_args', array( $this, 'http_request_sslverify' ), 10, 2 );

		}



		public function has_minimum_config() {
			$this->missing_config = array();

			$required_config_params = array(
				'github_url',
				'slug',
			);

			foreach ( $required_config_params as $required_param ) {
				if ( empty( $this->config[$required_param] ) ) {
					$this->missing_config[] = $required_param;
				}
			}

			return ( empty( $this->missing_config ) );
		}


		/**
		 * Check wether or not the transients need to be overruled and API needs to be called for every single page load
		 *
		 * @return bool overrule or not
		 */
		public function overrule_transients() {
			return $this->config['force_update'];
		}


		/**
		 * Set defaults
		 *
		 * @since 1.2
		 * @return void
		 */
		public function set_defaults() {

			// Get proper folder names and slug
			$this->config['slug'] = plugin_basename( $this->config['slug'] );
			if ( empty( $this->config['proper_folder_name'] ) ) {
				$this->config['proper_folder_name'] = dirname( plugin_basename( $this->config['slug'] ) );
			}

			$plugin_data = $this->get_plugin_data(); // Get plugin data

			if ( ! isset( $this->config['plugin_name'] ) ) {
				$this->config['plugin_name'] = $plugin_data['Name'];
			}

			if ( ! isset( $this->config['version'] ) ) {
				$this->config['version'] = $plugin_data['Version'];
			}

			if ( ! isset( $this->config['author'] ) ) {
				$this->config['author'] = $plugin_data['Author'];
			}

			if ( ! isset( $this->config['homepage'] ) ) {
				$this->config['homepage'] = $plugin_data['PluginURI'];
			}

			// If homepage defined
			if ( !empty( $this->config['homepage'] ) && strpos($this->config['homepage'],"github.com") !== false ) {
				$this->config['github_url'] = $this->config['homepage'];
			}
			
			// Check if Github is found as the URL.
			if ( empty( $this->config['github_url'] ) || strpos($this->config['homepage'],"github.com") === false ) {
				echo "No GitHub URL defined. Please fix!";
				return;
			}
			
			// Grab Github user and repo if not set
			if ( empty($this->config['github_user']) || empty($this->config['github_repo']) ) {
				preg_match(
					'/http(s)?:\/\/github.com\/(?<username>[\w-]+)\/(?<repo>[\w-]+)$/',
					rtrim($this->config['github_url'],"/"),
					$matches);
				if (	!empty($matches['username']) && ( empty($this->config['github_user'])) || ($matches['username'] != $this->config['github_user']) ) {
					$this->config['github_user'] = urlencode($matches['username']);
				}
				if (	!empty($matches['repo']) && ( empty($this->config['github_repo'])) || ($matches['repo'] != $this->config['github_repo']) ) {		
					$this->config['github_repo'] = urlencode($matches['repo']);
				}
			}		
			
			if ( ! isset( $this->config['new_version'] ) ) {
				$this->config['new_version'] = $this->get_new_version();
			}

			// Get the new download package
			if ( ! isset( $this->config['package'] ) ) {
				$this->config['package'] = $this->get_package();		
			}

			if ( ! isset( $this->config['description'] ) ) {
				$this->config['description'] = $plugin_data['Description'];
			}

			if ( ! isset( $this->config['name'] ) ) {
				$this->config['name'] = $plugin_data['Name'];
			}

		}


		/**
		 * Callback fn for the http_request_timeout filter
		 *
		 * @since 1.0
		 * @return int timeout value
		 */
		public function http_request_timeout() {
			return 2;
		}


		/**
		 * Callback fn for the http_request_args filter
		 *
		 * @param unknown $args
		 * @param unknown $url
		 *
		 * @return mixed
		 */
		public function http_request_sslverify( $args, $url ) {
			if ( $this->config[ 'package' ] == $url ) {
				$args[ 'sslverify' ] = $this->config[ 'sslverify' ];
			}
			return $args;
		}


		/**
		 * Get GitHub Data from the specified repository
		 *
		 * @since 1.0
		 * @return array $github_data the data
		 */
		public function get_github_data() {

			if ( isset( $this->github_data ) && ! empty( $this->github_data ) ) {
				$github_data = $this->github_data;
			} else {
				$github_data = get_site_transient( $this->config['slug'].'_github_data' );
				if ( $this->overrule_transients() || ( ! isset( $github_data ) || ! $github_data || '' == $github_data ) ) {

					$url = sprintf('https://api.github.com/repos/%s/%s/tags', urlencode($this->config['github_user']), urlencode($this->config['github_repo']));

					$response = get_transient(md5($url)); // Note: WP transients fail if key is long than 45 characters

					if(empty($response)){
						$raw_response = wp_remote_get($url, array('sslverify' => false, 'timeout' => 10));
						if ( is_wp_error( $raw_response ) ){
							$data->response['error'] = "Error response from " . $url;
							return;
						}
						$response = json_decode($raw_response['body']);

						if(count($response) == 0){
							$data->response['error'] = "Github theme does not have any tags";
						}

						//set cache, just 60 seconds
						set_transient(md5($url), $response, 60);
					}

					if(isset($response->message)){

						if(is_array($response->message)){
							$errors = '';
							foreach ( $response->message as $error) {
								$errors .= ' ' . $error;
							}
						} else {
							$errors = print_r($response->message, true);
						}
						$data->response['error'] = sprintf('While <a href="%s">fetching tags</a> api error</a>: <span class="error">%s</span>', $url, $errors);
					}

					if (!empty($data->response['error'])) {
						return;
					}

					// Sort and get latest tag
					$tags = array_map(create_function('$t', 'return $t->name;'), $response);
					usort($tags, "version_compare");

					// check for rollback
					if(isset($_GET['rollback'])){
						$data->response[$this->config['slug']]['package'] = 
							$this->config['github_url'] . '/zipball/' . urlencode($_GET['rollback']);
						echo "No Rollback version found!"; // DEBUG
						continue;
					}
					// check and generate download link
					$newest_tag = array_pop($tags);
					if(version_compare($this->config['version'],  $newest_tag, '>=')){
						// up-to-date!
						return false;
					}
					
					$github_data->new_version = $newest_tag;
					$github_data->package = $this->config['github_url'] . '/zipball/' . $newest_tag;

					// refresh every 6 hours
					set_site_transient( $this->config['slug'].'_github_data', $github_data, 60*60*6 );
					//set_site_transient( $this->config['slug'].'_github_data', $github_data, 6 ); // DEBUG
				}

				// Store the data in this class instance for future calls
				$this->github_data = $github_data;
			}

			return $github_data;
		}


		/**
		 * Get new version
		 *
		 * @since 1.0
		 * @return string $new_version the new version number
		 */
		public function get_new_version() {
			$_version = $this->get_github_data();
			return ( !empty( $_version->new_version ) ) ? $_version->new_version : false;
		}


		/**
		 * Get package link
		 *
		 * @since 1.0
		 * @return string $package the url for the latest zipball
		 */
		public function get_package() {		
			$_download = $this->get_github_data();
			return ( !empty( $_download->package ) ) ? $_download->package : false;
		}


		/**
		 * Get plugin description
		 *
		 * @since 1.0
		 * @return string $description the description
		 */
		public function get_description() {
					return;		
			$_description = $this->get_github_data();
			return ( !empty( $_description->description ) ) ? $_description->description : false;
		}


		/**
		 * Get Plugin data
		 *
		 * @since 1.0
		 * @return object $data the data
		 */
		public function get_plugin_data() {
			include_once ABSPATH.'/wp-admin/includes/plugin.php';
			$data = get_plugin_data( WP_PLUGIN_DIR.'/'.$this->config['slug'] );
			return $data;
		}


		/**
		 * Hook into the plugin update check and connect to github
		 *
		 * @since 1.0
		 * @param object  $transient the plugin data transient
		 * @return object $transient updated plugin data transient
		 */
		public function api_check( $transient ) {
			// Check if the transient contains the 'checked' information
			// If not, just return its value without hacking it
			if ( empty( $transient->checked ) && !$this->overrule_transients() ) {
				return $transient;
			}

			// check the version and decide if it's new
			$update = version_compare( $this->config['new_version'], $this->config['version'] );

			if ( 1 === $update ) {
				$response = new stdClass;
				$response->new_version = $this->config['new_version'];
				$response->slug = $this->config['proper_folder_name'];
				$response->url = add_query_arg( array( 'access_token' => $this->config['access_token'] ), $this->config['github_url'] );
				$response->package = $this->config['package'];
				// If response is false, don't alter the transient
				if ( false !== $response ) {
					$transient->response[ $this->config['slug'] ] = $response;
				}
			}

			return $transient;
		}


		/**
		 * Get Plugin info
		 *
		 * @since 1.0
		 * @param bool    $false  always false
		 * @param string  $action the API function being performed
		 * @param object  $args   plugin arguments
		 * @return object $response the plugin info
		 */
		public function get_plugin_info( $false, $action, $response ) {
			if (!isset($response->slug)) {
				return false;
			}
			// Check if this call API is for the right plugin
			if ( $response->slug != $this->config['slug'] ) {
				return false;
			}

			$response->slug = $this->config['slug'];
			$response->plugin_name  = $this->config['plugin_name'];
			$response->version = $this->config['new_version'];
			$response->author = $this->config['author'];
			$response->homepage = $this->config['homepage'];
			$response->requires = $this->config['requires'];
			$response->tested = $this->config['tested'];
			$response->downloaded = 0;
			$response->sections = array( 'description' => $this->config['description'] );
			$response->download_link = $this->config['package'];

			return $response;

		}


		/**
		 * Upgrader/Updater
		 * Move & activate the plugin, echo the update message
		 *
		 * @since 1.0
		 * @param boolean $true       always true
		 * @param mixed   $hook_extra not used
		 * @param array   $result     the result of the move
		 * @return array $result the result of the move
		 */
		public function upgrader_post_install( $true, $hook_extra, $result ) {

			global $wp_filesystem;

			// Move & Activate
			$proper_destination = WP_PLUGIN_DIR.'/'.$this->config['proper_folder_name'];
			$wp_filesystem->move( $result['destination'], $proper_destination );
			$result['destination'] = $proper_destination;
			$activate = activate_plugin( WP_PLUGIN_DIR.'/'.$this->config['slug'] );

			// Output the update message
			$fail  = __( 'The plugin has been updated, but could not be reactivated. Please reactivate it manually.', 'simple-updater' );
			$success = __( 'Plugin reactivated successfully.', 'simple-updater' );
			echo is_wp_error( $activate ) ? $fail : $success;
			return $result;

		}

	}

endif;