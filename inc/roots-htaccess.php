<?php  

function roots_htaccess_writable() {
	if (!is_writable(get_home_path() . '.htaccess')) {
		add_action('admin_notices', create_function('', "echo '<div class=\"error\"><p>" . sprintf(__('Please make sure your <a href="%s">.htaccess</a> file is writeable ', 'roots'), admin_url('options-permalink.php')) . "</p></div>';"));
	};
}

add_action('admin_init', 'roots_htaccess_writable');

function roots_add_h5bp_htaccess($rules) {
	global $wp_filesystem;

	if (!defined('FS_METHOD')) define('FS_METHOD', 'direct');
	if (is_null($wp_filesystem)) WP_Filesystem(array(), ABSPATH);
	
	if (!defined('WP_CONTENT_DIR'))
	define('WP_CONTENT_DIR', ABSPATH . 'wp-content');	

	$theme_name = next(explode('/themes/', get_template_directory()));
	$filename = WP_CONTENT_DIR . '/themes/' . $theme_name . '/inc/h5bp-.htaccess';

	$rules .= $wp_filesystem->get_contents($filename);
	
	return $rules;
}

add_action('mod_rewrite_rules', 'roots_add_h5bp_htaccess');

?>