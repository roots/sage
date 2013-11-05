<?php
/**
 * GitHub Updater
 *
 * @package   GitHubUpdater
 * @author    Andy Fragen, Seth Carstens, UCF Web Communications
 * @license   GPL-2.0+
 * @link      https://github.com/afragen/github-updater
 */

/**
 * Update a WordPress theme from a GitHub repo.
 *
 * @package   GitHubUpdater
 * @author    Andy Fragen, Seth Carstens
 */
class GitHub_Theme_Updater {

	/**
	 * Store details of all GitHub-sourced themes that are installed.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $config;

	public function __construct() {
		add_filter( 'extra_theme_headers', array( $this, 'add_headers') );
		$this->get_github_themes();

		if ( ! empty($_GET['action'] ) && ( $_GET['action'] == 'do-core-reinstall' || $_GET['action'] == 'do-core-upgrade') ); else {
			add_filter( 'pre_set_site_transient_update_themes', array( $this, 'transient_update_themes_filter' ) );
		}

		add_filter( 'upgrader_source_selection', array( $this, 'upgrader_source_selection_filter' ), 10, 3 );
		add_action( 'http_request_args', array( $this, 'no_ssl_http_request_args' ) );
	}

	/**
	 * Add GitHub headers to wp_get_theme
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function add_headers( $extra_headers ) {
		$gtu_extra_headers = array( 'GitHub Theme URI' );
		$extra_headers = array_merge( (array) $extra_headers, (array) $gtu_extra_headers );

		return $extra_headers;
	}

	/**
	* Get array of all themes in multisite
	*
	* wp_get_themes doesn't seem to work under network activation in the same way as in a single install.
	* http://core.trac.wordpress.org/changeset/20152
	*
	* @since 1.7.0
	*
	* @return array
	*/
	private function multisite_get_themes() {
		$themes     = array();
		$theme_dirs = scandir( get_theme_root() );
		$theme_dirs = array_diff( $theme_dirs, array( '.', '..', '.DS_Store' ) );

		foreach ( $theme_dirs as $theme_dir ) {
			$themes[] = wp_get_theme( $theme_dir );
		}

		return $themes;
	}

	/**
	 * Reads in WP_Theme class of each theme.
	 * Populates variable array
	 *
	 * @since 1.0.0
	 */
	private function get_github_themes() {

		$this->config = array();
		$themes = wp_get_themes();

		if ( is_multisite() )
			$themes = $this->multisite_get_themes();

		foreach ( $themes as $theme ) {
			$github_uri = $theme->get( 'GitHub Theme URI' );
			if ( empty( $github_uri ) ) continue;

			$owner_repo = parse_url( $github_uri, PHP_URL_PATH );
			$owner_repo = trim( $owner_repo, '/' );  // strip surrounding slashes

			$this->config['theme'][]                                = $theme->stylesheet;
			$this->config[ $theme->stylesheet ]['theme_key']        = $theme->stylesheet;
			$this->config[ $theme->stylesheet ]['GitHub_Theme_URI'] = 'https://github.com/' . $owner_repo;
			$this->config[ $theme->stylesheet ]['GitHub_API_URI']   = 'https://api.github.com/repos/' . $owner_repo;
			$this->config[ $theme->stylesheet ]['version']          = $theme->get( 'Version' );
		}
	}

	/**
	 * Call the GitHub API and return a json decoded body.
	 *
	 * @since 1.0.0
	 *
	 * @param string $url
	 * @see http://developer.github.com/v3/
	 * @return boolean|object
	 */
	protected function api( $url ) {

		$response = wp_remote_get( $url );

		if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) != '200' )
			return false;

		return json_decode( wp_remote_retrieve_body( $response ) );
	}

	/**
	 * Reads the remote plugin file.
	 *
	 * Uses a transient to limit the calls to the API.
	 *
	 * @since 1.0.0
	 */
	protected function get_remote_info( $url ) {

		$remote = get_site_transient( md5( $url . 'theme' ) ) ;

		if ( ! $remote ) {
			$remote = $this->api( $url );

			if ( $remote )
				set_site_transient( md5( $url . 'theme' ), $remote, HOUR_IN_SECONDS );
		}

		return $remote;
	}

	/**
	 * Finds newest tag and compares to current tag
	 *
	 * @since 1.0.0
	 *
	 * @param array $data
	 * @return array|object
	 */
	public function transient_update_themes_filter( $data ){

		foreach ( $this->config as $theme => $theme_data ) {
			if ( empty( $theme_data['GitHub_API_URI'] ) ) continue;
			$url      = trailingslashit( $theme_data['GitHub_API_URI'] ) . 'tags';
			$response = $this->get_remote_info( $url );

			// Sort and get latest tag
			$tags = array();
			if ( false !== $response )
				foreach ( $response as $num => $tag ) {
					if ( isset( $tag->name ) ) $tags[] = $tag->name;
				}

			// if no tag set or no version number then abort
			if ( empty( $tags ) || is_null( $theme_data['version'] ) ) return false;
			usort( $tags, 'version_compare' );

			// check and generate download link
			$newest_tag     = null;
			$newest_tag_key = key( array_slice( $tags, -1, 1, true ) );
			$newest_tag     = $tags[ $newest_tag_key ];

			$download_link = trailingslashit( $theme_data['GitHub_Theme_URI'] ) . trailingslashit( 'archive' ) . $newest_tag . '.zip';

			// setup update array to append version info
			$update = array();
			$update['new_version'] = $newest_tag;
			$update['url']         = $theme_data['GitHub_Theme_URI'];
			$update['package']     = $download_link;

			if ( version_compare( $theme_data['version'],  $newest_tag, '>=' ) ) {
				// up-to-date!
				$data->up_to_date[ $theme_data['theme_key'] ]['rollback'] = $tags;
				$data->up_to_date[ $theme_data['theme_key'] ]['response'] = $update;
			} else {
				$data->response[ $theme_data['theme_key'] ] = $update;
			}
		}
		return $data;
	}

	/**
	 * Rename the zip folder to be the same as the existing theme folder.
	 *
	 * Github delivers zip files as <Repo>-<Tag>.zip
	 *
	 * @since 1.0.0
	 *
	 * @global WP_Filesystem $wp_filesystem
	 *
	 * @param string $source
	 * @param string $remote_source Optional.
	 * @param object $upgrader      Optional.
	 *
	 * @return string
	 */
	public function upgrader_source_selection_filter( $source, $remote_source = null, $upgrader = null ) {

		global $wp_filesystem;
		$update = array( 'update-selected', 'update-selected-themes', 'upgrade-theme', 'upgrade-plugin' );

		if ( isset( $source, $this->config['theme'] ) ) {
			for ( $i = 0; $i < count( $this->config['theme'] ); $i++ ) {
				if ( stristr( basename( $source ), $this->config['theme'][$i] ) )
					$theme = $this->config['theme'][$i];
			}
		}

		// If there's no action set, or not one we recognise, abort
		if ( ! isset( $_GET['action'] ) || ! in_array( $_GET['action'], $update, true ) )
			return $source;

		// If the values aren't set, or it's not GitHub-sourced, abort
		if ( ! isset( $source, $remote_source, $theme ) || false === stristr( basename( $source ), $theme ) )
			return $source;

		$corrected_source = trailingslashit( $remote_source ) . trailingslashit( $theme );
		$upgrader->skin->feedback(
			sprintf(
				__( 'Renaming %s to %s&#8230;', 'github-updater' ),
				'<span class="code">' . basename( $source ) . '</span>',
				'<span class="code">' . basename( $corrected_source ) . '</span>'
			)
		);

		// If we can rename, do so and return the new name
		if ( $wp_filesystem->move( $source, $corrected_source, true ) ) {
			$upgrader->skin->feedback( __( 'Rename successful&#8230;', 'github-updater' ) );
			return $corrected_source;
		}

		// Otherwise, return an error
		$upgrader->skin->feedback( __( 'Unable to rename downloaded theme.', 'github-updater' ) );
		return new WP_Error();
	}

	/**
	 * Fixes {@link https://github.com/UCF/Theme-Updater/issues/3}.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $args Existing HTTP Request arguments.
	 *
	 * @return array Amended HTTP Request arguments.
	 */
	public function no_ssl_http_request_args( $args ) {
		$args['sslverify'] = false;
		return $args;
	}
}
