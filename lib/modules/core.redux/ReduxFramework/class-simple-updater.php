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
		 * @var $github_data temporarily store the data fetched from GitHub, allows us to only load the data once per class instance
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
				'slug' 					=> '',
				'proper_folder_name' 	=> '',
				'access_token' 			=> '',
				'github_url' 			=> '',
				'sslverify' 			=> false,
				'github_user' 			=> '',
				'force_update' 			=> false,
				'mode'					=> 'releases', // releases, commit
				'github_repo' 			=> '',
			);

			$this->config = wp_parse_args( $config, $defaults );
			$this->set_defaults();

			// if the minimum config isn't set, issue a warning and bail
			if ( ! $this->has_minimum_config() ) {
				$message = 'SimpleUpdater was initialized without the minimum required configuration, please check the config in your plugin. The following params are missing: ';
				$message .= implode( ',',   $this->missing_config );
				_doing_it_wrong( __CLASS__, $message , self::VERSION );
                echo $message;
                return;
			}

			add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'api_check' ) );

			// Hook into the plugin details screen
			add_filter( 'plugins_api', array( $this, 'get_plugin_info' ), 10, 3 );
			add_filter( 'upgrader_post_install', array( $this, 'upgrader_post_install' ), 10, 3 );

			// set timeout
			add_filter( 'http_request_timeout', array( $this, 'http_request_timeout' ) );

			// set sslverify for zip download
			add_filter( 'http_request_args', array( $this, 'http_request_sslverify' ), 10, 2 );

			add_action('install_plugins_pre_plugin-information', array( $this, 'bypass_plugin_details' ), 10, 8 );

		}

		public function bypass_plugin_details() {
			
			if ( $_GET['plugin'] == $this->config["proper_folder_name"] ) {
				
				$url = sprintf('https://api.github.com/repos/%s/%s/commits?since=%s', urlencode($this->config['github_user']), urlencode($this->config['github_repo'] ), date("c", filemtime( dirname( __FILE__ )."/index.php" )));	
				$response = $this->github_api($url);

				if ( empty( $response ) || !empty( $reponse->message ) ) {
					$this->config['force_update'] = true;
					$response = $this->github_api($url);
				}

				if ( !empty( $response->message ) ) {
					echo '<h4 style="text-align: center;width: 60%;margin: 0px auto;padding: 30px;">'.$response->message.'</h4>';
					exit();
				}
				
				if( count( $response ) == 0 || strtotime( $response[0]->commit->author->date ) <= filemtime( dirname( __FILE__ ).'/index.php' ) ){
					echo '<h4 style="text-align: center;width: 60%;margin: 0px auto;padding: 30px;">No new updates exist</h4>';
				} else {
					echo '<link href="https://github.global.ssl.fastly.net/assets/github-40dbdfedaeb30d1adccdc9a437de4819a3b9c098.css" media="all" rel="stylesheet" type="text/css" />';
					?>
					<style type="text/css">
						body { margin: 0 20px; min-width: inherit;}
						.commit .commit-desc { display: inherit; }
						.commit-group-item .commit-desc pre { padding-right: 140px; }
					</style>
					<?php
					echo '<h2>Updating to: '.$this->config['new_version'].'</h2>';
					echo '<div class="js-navigation-container js-active-navigation-container" data-navigation-scroll="page">';
					echo '<h3 class="commit-group-heading">'.count($response).' changes have been made since your version was last updated on '.date("M j Y", filemtime( dirname( __FILE__ ).'/index.php' )).'.</h3>';
					echo '<ol class="commit-group">';
					
					foreach ( $response as $commit ) { 

						$dStart = new DateTime($commit->commit->author->date);
   						$dEnd  = new DateTime('NOW');
   						$dDiff = $dStart->diff($dEnd);
   
						?>
					  <li class="commit commit-group-item js-navigation-item js-details-container">
			            <img class="gravatar" height="36" src="<?php echo $commit->author->avatar_url; ?>&amp;s=140" width="36">
			            <div class="commit-desc"><pre><a href="https://github.com/ReduxFramework/ReduxFramework/commit/<?php echo $commit->sha; ?>" target="_blank" class="message" data-pjax="true" title="<?php echo $commit->commit->message; ?>"><?php echo $commit->commit->message; ?></a></pre></div>
			            <div class="commit-meta">
			              <div class="commit-links">
			                <a href="https://github.com/ReduxFramework/ReduxFramework/commit/<?php echo $commit->sha; ?>" class="gobutton " target="_blank">
			                  <span class="sha"><?php echo substr($commit->sha, 0, 10); ?>10<span class="octicon octicon-arrow-small-right"></span></span>
			                </a>
			                <a href="https://github.com/ReduxFramework/ReduxFramework/tree/<?php echo $commit->sha; ?>" class="browse-button" title="Browse the code at this point in the history" rel="nofollow" target="_blank">Browse code <span class="octicon octicon-arrow-right"></span></a>
			              </div>
			              <div class="authorship">
			                <span class="author-name"><a href="https://github.com/<?php echo $commit->commit->author->name; ?>" rel="author" target="_blank"><?php echo $commit->commit->author->name; ?></a></span>
			                authored <time class="js-relative-date" datetime="<?php echo date('Y-m-d H:i:s',strtotime($commit->commit->author->date)); ?>" title="<?php echo $commit->commit->author->date; ?>"><?php 
			                if ($dDiff->days < 1) {
			                	if ($dDiff->h == 1) {
			                		echo __('an hour ago', 'redux-framework');
			                	} else if ($dDiff->h > 1) {
			                		echo $dDiff->h.' '.__('hours ago', 'redux-framework');
			                	} else {
			                		echo $dDiff->m.' '.__('mins ago', 'redux-framework');	
			                	}
			                	
			                } else if ($dDiff->days == 1) {
			                	echo __('a day ago', 'redux-framework');
			                } else {
			                	echo $dDiff->days .' '. __('days ago', 'redux-framework');
			                }
			                ?></time>
			              </div>
			            </div>
			          </li>

					<?php
					}
				}
				exit();	
			}
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
		public function override_transients() {
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

			if ( ! isset( $this->config['version'] ) || $this->config['mode'] == "releases" ) {
				$this->config['version'] = $plugin_data['Version'];
			}

			// Failsafe
			if ( $this->config['mode'] != "releases" && $this->config['mode'] != "commits" ) {
				$this->config['mode'] = "releases";
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
			if ( empty( $this->config['github_url'] ) && strpos($this->config['homepage'],"github.com") === false ) {
				$this->config['github_url'] = $plugin_data['GithubURI'];
			}			
			
			// Check if Github is found as the URL.
			if ( empty( $this->config['github_url'] ) && strpos($this->config['homepage'],"github.com") === false ) {
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


		public function github_api($url) {

			if (empty($url)) {
				return;
			}

			if (!$this->override_transients()) {
				$response = get_site_transient($this->config['mode'].'-'.$this->config['slug'].'-'.md5($url)); // Note: WP transients fail if key is long than 45 characters
			}

			if ( !isset( $response ) ) {
				$raw_response = wp_remote_get($url, array('sslverify' => false, 'timeout' => 10));
				if ( is_wp_error( $raw_response ) ) {
					$data->response['error'] = "Error response from " . $url;
					set_site_transient($this->config['mode'].'-'.$this->config['slug'].'-'.md5($url), false, 60*60*2);
					return false;
				}
				$response = json_decode($raw_response['body']);
				if ( empty( $response->message ) ) {
					set_site_transient($this->config['mode'].'-'.$this->config['slug'].'-'.md5($url), $response, 60*60*2);
				}
			}

			if ( !empty( $response ) ) {
				return $response;
			}

		}

		/**
		 * Get GitHub Data from the specified repository
		 *
		 * @since 1.0
		 * @return array $github_data the data
		 */
		public function get_github_data() {
			if ( isset( $this->github_data ) ) {
				$github_data = $this->github_data;
			} else {
				if (!$this->override_transients()) {
					$github_data = get_site_transient( $this->config['slug'].'-'.$this->config['slug'].'_github_data' );
				}
				
				if ( !isset( $github_data ) || !$github_data || '' == $github_data ) {

					if ( $this->config['mode'] == "releases" ) {
						$url = sprintf('https://api.github.com/repos/%s/%s/tags', urlencode($this->config['github_user']), urlencode($this->config['github_repo']));	
					} else {
						$url = sprintf('https://api.github.com/repos/%s/%s/commits?since=%s', urlencode($this->config['github_user']), urlencode($this->config['github_repo'] ), date("c", filemtime( dirname( __FILE__ ).'/index.php' )));	
					}

					$response = $this->github_api($url);

					if (empty($response)) {
						return;
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
						$data->response['error'] = sprintf('While <a href="%s">fetching versions</a> api error</a>: <span class="error">%s</span>', $url, $errors);
					}

					if (!empty($data->response['error'])) {
						echo $data->response['error'];
						$this->github_data = false;
						return $this->github_data;
					}

					if ( $this->config['mode'] == "releases" ) {

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
						if(version_compare($this->config['version'],  $newest_tag, '<=')){
							// up-to-date!
							$this->github_data = false;
							return $this->github_data;
						}


						$github_data->new_version = $newest_tag;

						$github_data->package = $this->config['github_url'] . '/zipball/' . $newest_tag;

					} else { // Commits
						if ( strtotime($response[0]->commit->author->date) <= filemtime( dirname( __FILE__ ).'/index.php' ) ) {
							$this->github_data = false; // Up to date
							return $this->github_data;							
						}
						$newest_tag = $response[0]->sha;
						$github_data->current = $newest_tag;
						$github_data->new_version = $newest_tag;
						$github_data->package = $this->config['github_url'] . '/archive/' . $newest_tag.'.zip';
					}

					// refresh every 2 hours
					set_site_transient( $this->config['slug'].'-'.$this->config['slug'].'_github_data', $github_data, 60*60*2 );
					delete_site_transient('update_plugins');
				}

				// Store the data in this class instance for future calls
				if ( !empty( $this->github_data ) ) {
					$this->github_data = $github_data;	
				}
				
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

			$default_headers = array(
	                'Name' => 'Plugin Name',
	                'PluginURI' => 'Plugin URI',
	                'GithubURI' => 'Github URI',
	                'Version' => 'Version',
	                'Description' => 'Description',
	                'Author' => 'Author',
	                'AuthorURI' => 'Author URI',
	                'TextDomain' => 'Text Domain',
	                'DomainPath' => 'Domain Path',
	                'Network' => 'Network',
	                // Site Wide Only is deprecated in favor of Network.
	                '_sitewide' => 'Site Wide Only',
	        );	        
	
	        return get_file_data( WP_PLUGIN_DIR.'/'.$this->config['slug'], $default_headers, 'plugin' );

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
			$response = false;		

			if ( empty( $this->config['new_version'] ) || ( empty( $transient->checked ) && !$this->override_transients() ) ) {
				return $transient;
			}
			// check the version and decide if it's new
			if ( $this->config['mode'] == "release" ) {
				$update = version_compare( $this->config['new_version'], $this->config['version'] );
				if ( 1 === $update ) {
					$response = new stdClass;
					$response->new_version = $this->config['new_version'];
					$response->slug = $this->config['proper_folder_name'];
					$response->url = add_query_arg( array( 'access_token' => $this->config['access_token'] ), $this->config['github_url'] );
					$response->package = $this->config['package'];
				}
			} else {
				if ( $this->config['new_version'] != $this->config['version'] ) {
					$response = new stdClass;
					$response->new_version = $this->config['new_version'];
					$response->slug = $this->config['proper_folder_name'];
					$response->url = add_query_arg( array( 'access_token' => $this->config['access_token'] ), $this->config['github_url'] );
					$response->package = $this->config['package'];
				}
			}

			// If response is false, don't alter the transient
			if ( false !== $response ) {
				$transient->response[ $this->config['slug'] ] = $response;
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