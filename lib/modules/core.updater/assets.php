<?php

/******************************************************************************\
	http://core.trac.wordpress.org/browser/trunk/wp-admin/includes/update.php?rev=17984#L264
	changes:  
		- disables the iframe popup and uses a new window and makes a pop-up linking to the github project
		- calls 'upgrade-github-theme' vs 'upgrade-theme'
\******************************************************************************/
function github_theme_update_row( $theme_key, $theme ) {
	$current = get_site_transient( 'update_themes' );
	if ( 
		!isset( $current->response[ $theme_key ] ) and
		!isset( $current->up_to_date[ $theme_key ] )	
	)
		return false;

	$wp_list_table = _get_list_table('WP_MS_Themes_List_Table');
	
	
	// custom additions
	if(isset($current->up_to_date[$theme_key])){
		$rollback = $current->up_to_date[$theme_key]['rollback'];
		echo '<tr class="plugin-update-tr"><td colspan="' . $wp_list_table->get_column_count() . '" class="plugin-update colspanchange"><div class="update-message-gtu update-ok">';
		echo 'Theme is up-to-date! ';
		if (current_user_can('update_themes') ){
			if(count($rollback) > 0){
				echo "<strong>Rollback to:</strong> ";
				// display last three tags
				for($i=0; $i<3 ; $i++){
					$tag = array_pop($rollback);
					if(empty($tag)) break;
					if($i>0) echo ", ";
					printf('<a href="%s%s">%s</a>',
						wp_nonce_url( self_admin_url('update.php?action=upgrade-github-theme&theme=') . $theme_key, 'upgrade-theme_' . $theme_key),
						'&rollback=' . urlencode($tag),
						$tag);
				}
			} else {
				echo "No previous tags to rollback to.";
			}
		}
	} else {
		$r = $current->response[ $theme_key ];
		if( isset($r['error']) ){
			echo '<tr class="plugin-update-tr"><td colspan="' . $wp_list_table->get_column_count() . '" class="plugin-update colspanchange"><div class="update-message-gtu update-error">';
			printf('Error with Github Theme Updater. %1$s', $r['error']);
		} else {
			$themes_allowedtags = array('a' => array('href' => array(),'title' => array()),'abbr' => array('title' => array()),'acronym' => array('title' => array()),'code' => array(),'em' => array(),'strong' => array());
			$theme_name = wp_kses( $theme['Name'], $themes_allowedtags );
			$github_url = esc_url($r['url']);
			$diff_url   = esc_url($r['url'] . '/compare/' . $theme['Version'] . '...' . $r['new_version']);
			
			echo '<tr class="plugin-update-tr"><td colspan="' . $wp_list_table->get_column_count() . '" class="plugin-update colspanchange"><div class="update-message-gtu">';
			printf('Github has as a new version of <a href="%s" target="blank">%s</a>. ', $github_url, $theme_name);
			printf('View <a href="%s" target="blank">version diff</a> with %s. ', $diff_url, $r['new_version']);
			if (current_user_can('update_themes')){
				if(empty($r['package'])){
					echo '<em>Automatic update is unavailable for this plugin.</em>';
				} else {
					printf('<a href="%s">Update automatically</a>.', wp_nonce_url( self_admin_url('update.php?action=upgrade-github-theme&theme=') . $theme_key, 'upgrade-theme_' . $theme_key));
				}
			}
		}
		do_action( "in_theme_update_message-$theme_key", $theme, $r );
	}
	echo '</div></td></tr>';
}

// http://codex.wordpress.org/Function_Reference/wp_enqueue_style
add_action( 'admin_init', 'theme_upgrader_stylesheet' );
function theme_upgrader_stylesheet() {
	$style_url  = WP_PLUGIN_URL . '/' . basename(dirname(__FILE__)) . '/admin-style.css';
	wp_register_style('theme_updater_style', $style_url);
	wp_enqueue_style( 'theme_updater_style');
}



/******************************************************************************\
	Most of this code is pulled directly from the WP source
	modifications are noted.
\******************************************************************************/
include ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
class Github_Theme_Upgrader extends Theme_Upgrader {
	function download_url( $url ) {
		/*
			http://core.trac.wordpress.org/browser/trunk/wp-admin/includes/file.php?rev=17928#L467
			changes:  
				- wanted a timeout < 5 min
				- SSL fails when trying to access github
		*/
		if ( ! $url )
			return new WP_Error('http_no_url', __('Invalid URL Provided.'));

		$tmpfname = wp_tempnam($url);
		if ( ! $tmpfname )
			return new WP_Error('http_no_file', __('Could not create Temporary file.'));

		$handle = @fopen($tmpfname, 'wb');
		if ( ! $handle )
			return new WP_Error('http_no_file', __('Could not create Temporary file.'));

		// This! is the one line I wanted to get at
		$response = wp_remote_get($url , array('sslverify' => false, 'timeout' => 30));
		
		if ( is_wp_error($response) ) {
			fclose($handle);
			unlink($tmpfname);
			return $response;
		}

		if ( $response['response']['code'] != '200' ){
			fclose($handle);
			unlink($tmpfname);
			return new WP_Error('http_404', trim($response['response']['message']));
		}

		fwrite($handle, $response['body']);
		fclose($handle);

		return $tmpfname;
	}
	
	function download_package($package) {
		/*
			http://core.trac.wordpress.org/browser/trunk/wp-admin/includes/class-wp-upgrader.php?rev=17771#L108
			changes:
				- use customized download_url
		*/
		if ( ! preg_match('!^(http|https|ftp)://!i', $package) && file_exists($package) ) //Local file or remote?
			return $package; //must be a local file..

		if ( empty($package) )
			return new WP_Error('no_package', $this->strings['no_package']);

		$this->skin->feedback('downloading_package', $package);
		
		// This! is the one line I wanted to get at
		$download_file = $this->download_url($package);

		if ( is_wp_error($download_file) )
			return new WP_Error('download_failed', $this->strings['download_failed'], $download_file->get_error_message());

		return $download_file;
	}
	
	function install_package($args = array()) {
		/*
			This funciton can go away once my patch has been applied:
			http://core.trac.wordpress.org/ticket/17680
		*/
		
		global $wp_filesystem;
		$defaults = array( 'source' => '', 'destination' => '', //Please always pass these
						'clear_destination' => false, 'clear_working' => false,
						'hook_extra' => array());

		$args = wp_parse_args($args, $defaults);
		extract($args);

		@set_time_limit( 300 );

		if ( empty($source) || empty($destination) )
			return new WP_Error('bad_request', $this->strings['bad_request']);

		$this->skin->feedback('installing_package');

		$res = apply_filters('upgrader_pre_install', true, $hook_extra);
		if ( is_wp_error($res) )
			return $res;

		//Retain the Original source and destinations
		$remote_source = $source;
		$local_destination = $destination;

		$source_files = array_keys( $wp_filesystem->dirlist($remote_source) );
		$remote_destination = $wp_filesystem->find_folder($local_destination);

		//Locate which directory to copy to the new folder, This is based on the actual folder holding the files.
		if ( 1 == count($source_files) && $wp_filesystem->is_dir( trailingslashit($source) . $source_files[0] . '/') ) //Only one folder? Then we want its contents.
			$source = trailingslashit($source) . trailingslashit($source_files[0]);
		elseif ( count($source_files) == 0 )
			return new WP_Error('bad_package', $this->strings['bad_package']); //There are no files?
		//else //Its only a single file, The upgrader will use the foldername of this file as the destination folder. foldername is based on zip filename.

		//Hook ability to change the source file location..
		$source = apply_filters('upgrader_source_selection', $source, $remote_source, $this);
		if ( is_wp_error($source) )
			return $source;

		//Has the source location changed? If so, we need a new source_files list.
		if ( $source !== $remote_source )
			$source_files = array_keys( $wp_filesystem->dirlist($source) );

		//Protection against deleting files in any important base directories.
		if ( in_array( $destination, array(ABSPATH, WP_CONTENT_DIR, WP_PLUGIN_DIR, WP_CONTENT_DIR . '/themes') ) ) {
			$remote_destination = trailingslashit($remote_destination) . trailingslashit(basename($source));
			$destination = trailingslashit($destination) . trailingslashit(basename($source));
		}
		
		$tempdir = untrailingslashit($remote_destination) . ".tmp-" . time() . "/"; 
		if ( $wp_filesystem->exists($remote_destination) ) {
			if ( $clear_destination ) {
				
				//Try to rename original theme (also works as a backup) 
				$moved = @rename($remote_destination, $tempdir);
				if ( ! $moved )
					return new WP_Error('remove_old_failed', $this->strings['remove_old_failed']);
				
				//We're going to clear the destination if theres something there
				$this->skin->feedback('remove_old');
				$removed = $wp_filesystem->delete($remote_destination, true);
				$removed = apply_filters('upgrader_clear_destination', $removed, $local_destination, $remote_destination, $hook_extra);

				if ( is_wp_error($removed) )
					return $removed;
				else if ( ! $removed )
					return new WP_Error('remove_old_failed', $this->strings['remove_old_failed']);
			} else {
				//If we're not clearing the destination folder and something exists there allready, Bail.
				//But first check to see if there are actually any files in the folder.
				$_files = $wp_filesystem->dirlist($remote_destination);
				if ( ! empty($_files) ) {
					$wp_filesystem->delete($remote_source, true); //Clear out the source files.
					return new WP_Error('folder_exists', $this->strings['folder_exists'], $remote_destination );
				}
			}
		}

		//Create destination if needed
		if ( !$wp_filesystem->exists($remote_destination) )
			if ( !$wp_filesystem->mkdir($remote_destination, FS_CHMOD_DIR) )
				return new WP_Error('mkdir_failed', $this->strings['mkdir_failed'], $remote_destination);

		// Copy new version of item into place.
		$result = copy_dir($source, $remote_destination);
		if ( is_wp_error($result) ) {
			if ( $clear_working )
				$wp_filesystem->delete($remote_source, true);
			return $result;
		}

		//Clear the Working folder?
		if ( $clear_working )
			$wp_filesystem->delete($remote_source, true);

		$destination_name = basename( str_replace($local_destination, '', $destination) );
		if ( '.' == $destination_name )
			$destination_name = '';

		$this->result = compact('local_source', 'source', 'source_name', 'source_files', 'destination', 'destination_name', 'local_destination', 'remote_destination', 'clear_destination', 'delete_source_dir');

		$res = apply_filters('upgrader_post_install', true, $hook_extra, $this->result);
		if ( is_wp_error($res) ) {
			$this->result = $res;
			return $res;
		}
		
		// Remove temporary backup 
		$removed = $wp_filesystem->delete($tempdir, true); 
		if( !$removed ) $this->skin->feedback("Could not remove the temporary theme directory.");
		
		//Bombard the calling function will all the info which we've just used.
		return $this->result;
	}
	
}

add_action('update-custom_upgrade-github-theme', 'github_theme_updater', 10, 2);
function github_theme_updater(){
	/*
		http://core.trac.wordpress.org/browser/trunk/wp-admin/update.php?rev=17632#L145
		changes:  
			- use customized theme upgrader
	*/ 
	if ( ! current_user_can('update_themes') )
		wp_die(__('You do not have sufficient permissions to update themes for this site.'));
	
	$theme = isset($_REQUEST['theme']) ? urldecode($_REQUEST['theme']) : '';
	check_admin_referer('upgrade-theme_' . $theme);
	
	add_thickbox();
	wp_enqueue_script('theme-preview');
	$title = __('Update Theme');
	$parent_file = 'themes.php';
	$submenu_file = 'themes.php';
	require_once(ABSPATH . 'wp-admin/admin-header.php');

	$nonce = 'upgrade-theme_' . $theme;
	$url = 'update.php?action=upgrade-theme&theme=' . $theme;

	$upgrader = new Github_Theme_Upgrader( new Theme_Upgrader_Skin( compact('title', 'nonce', 'url', 'theme') ) );
	$upgrader->upgrade($theme);
	
	include(ABSPATH . 'wp-admin/admin-footer.php');
}