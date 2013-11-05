<?php
/*
Plugin Name: Theme Updater
Plugin URI: https://github.com/UCF/Theme-Updater
Description: A theme updater for GitHub hosted Wordpress themes.  This Wordpress plugin automatically checks GitHub for theme updates and enables automatic install.  For more information read <a href="https://github.com/UCF/Theme-Updater/blob/master/readme.markdown">plugin documentation</a>.
Author: Douglas Beck
Author: UCF Web Communications
Version: 1.3.7
*/

// register the custom stylesheet header
add_action( 'extra_theme_headers', 'github_extra_theme_headers' );
function github_extra_theme_headers( $headers ) {
    $headers['Github Theme URI'] = 'Github Theme URI';
    return $headers;
}

//disable updater during core wordpress updates
if(!empty($_GET['action']) && $_GET['action'] == 'do-core-reinstall'); 
else {
	add_filter('site_transient_update_themes', 'transient_update_themes_filter');
	require_once('assets.php');
}

function transient_update_themes_filter($data){
	global $wp_version;

	if(function_exists('wp_get_themes')) $installed_themes = wp_get_themes( );
	else $installed_themes = get_themes( );
	foreach ( (array) $installed_themes as $theme_title => $_theme ) {
		
		// the WP_Theme object is very different now...
		// This whole function should be refactored to not directly
		// rely on the $theme variable the way it does
		if(version_compare($wp_version, '3.4', '>=')) {
			if(!$_theme->get('Github Theme URI')) {
				continue;
			} else {
				$theme = array(
					'Github Theme URI' => $_theme->get('Github Theme URI'),
					'Stylesheet'       => $_theme->stylesheet,
					'Version'          => $_theme->version
				);
			}
		} else {
			// get the Github URI header, skip if not set
			$theme = $_theme;
			if(isset($theme['Stylesheet Files'][0]) && is_readable($theme['Stylesheet Files'][0])){
				$stylesheet = $theme['Stylesheet Dir'] . '/style.css';
				
				$theme_data = get_theme_data($stylesheet);
				if(empty($theme_data['Github Theme URI'])){
					continue;
				} else {
					$theme['Github Theme URI'] = $theme_data['Github Theme URI'];
				}
			};
		}
		
		$theme_key = $theme['Stylesheet'];
		
		// Add Github Theme Updater to return $data and hook into admin
		remove_action( "after_theme_row_" . $theme['Stylesheet'], 'wp_theme_update_row');
		add_action( "after_theme_row_" . $theme['Stylesheet'], 'github_theme_update_row', 11, 2 );
		
		// Grab Github Tags
		preg_match(
			'/http(s)?:\/\/github.com\/(?<username>[\w-]+)\/(?<repo>[\w-]+)$/',
			$theme['Github Theme URI'],
			$matches);
		if(!isset($matches['username']) or !isset($matches['repo'])){
			$data->response[$theme_key]['error'] = 'Incorrect github project url.  Format should be (no trailing slash): <code style="background:#FFFBE4;">https://github.com/&lt;username&gt;/&lt;repo&gt;</code>';
			continue;
		}
		$url = sprintf('https://api.github.com/repos/%s/%s/tags', urlencode($matches['username']), urlencode($matches['repo']));
		
		$response = get_transient(md5($url)); // Note: WP transients fail if key is long than 45 characters
		if(empty($response)){
			$raw_response = wp_remote_get($url, array('sslverify' => false, 'timeout' => 10));
			if ( is_wp_error( $raw_response ) ){
				$data->response[$theme_key]['error'] = "Error response from " . $url;
				continue;
			}
			$response = json_decode($raw_response['body']);

			if(isset($response->message)){
				if(is_array($response->message)){
					$errors = '';
					foreach ( $response->message as $error) {
						$errors .= ' ' . $error;
					}
				} else {
					$errors = print_r($response->message, true);
				}
				$data->response[$theme_key]['error'] = sprintf('While <a href="%s">fetching tags</a> api error</a>: <span class="error">%s</span>', $url, $errors);
				continue;
			}
			
			if(count($response) == 0){
				$data->response[$theme_key]['error'] = "Github theme does not have any tags";
				continue;
			}
			
			//set cache, just 60 seconds
			set_transient(md5($url), $response, 30);
		}
		
		// Sort and get latest tag
		$tags = array_map(create_function('$t', 'return $t->name;'), $response);
		usort($tags, "version_compare");
		
		
		// check for rollback
		if(isset($_GET['rollback'])){
			$data->response[$theme_key]['package'] = 
				$theme['Github Theme URI'] . '/zipball/' . urlencode($_GET['rollback']);
			continue;
		}
		
		
		// check and generate download link
		$newest_tag = array_pop($tags);
		if(version_compare($theme['Version'],  $newest_tag, '>=')){
			// up-to-date!
			$data->up_to_date[$theme_key]['rollback'] = $tags;
			continue;
		}
		
		
		// new update available, add to $data
		$download_link = $theme['Github Theme URI'] . '/zipball/' . $newest_tag;
		$update = array();
		$update['new_version'] = $newest_tag;
		$update['url']         = $theme['Github Theme URI'];
		$update['package']     = $download_link;
		$data->response[$theme_key] = $update;
		
	}
	
	return $data;
}


add_filter('upgrader_source_selection', 'upgrader_source_selection_filter', 10, 3);
function upgrader_source_selection_filter($source, $remote_source=NULL, $upgrader=NULL){
	/*
		Github delivers zip files as <Username>-<TagName>-<Hash>.zip
		must rename this zip file to the accurate theme folder
	*/
	$upgrader->skin->feedback("Executing upgrader_source_selection_filter function...");
	if(isset($upgrader->skin->theme)) 
		$correct_theme_name = $upgrader->skin->theme;
	elseif(isset($upgrader->skin->theme_info->stylesheet))
		$correct_theme_name = $upgrader->skin->theme_info->stylesheet;
	elseif(isset($upgrader->skin->theme_info->template))
		$correct_theme_name = $upgrader->skin->theme_info->template;
	else 
		$upgrader->skin->feedback('Theme name not found. Unable to rename downloaded theme.');
			
	if(isset($source, $remote_source, $correct_theme_name)){				
		$corrected_source = $remote_source . '/' . $correct_theme_name . '/';
		if(@rename($source, $corrected_source)){
			$upgrader->skin->feedback("Renamed theme folder successfully.");
			return $corrected_source;
		} else {
			$upgrader->skin->feedback("**Unable to rename downloaded theme.");
			return new WP_Error();
		}
	}
	else
		$upgrader->skin->feedback('**Source or Remote Source is unavailable.');
		
	return $source;
}

/*
   Function to address the issue that users in a standalone WordPress installation
   were receiving SSL errors and were unable to install themes.
   https://github.com/UCF/Theme-Updater/issues/3
*/
add_action('http_request_args', 'no_ssl_http_request_args', 10, 2);
function no_ssl_http_request_args($args, $url) {
	$args['sslverify'] = false;
	return $args;
}
