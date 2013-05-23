<?php

// TinyMCE popup CSS
function wpbandit_tinymce_css() {
	wp_enqueue_script('wpb-popup', get_template_directory_uri() . '/lib/shortcodes/tinymce/wpbandit.popup.js',
		array('jquery'));
	wp_enqueue_style('wpb-tinymce', get_template_directory_uri() . '/lib/shortcodes/tinymce/tinymce.wpbandit.css');
}
add_action('admin_enqueue_scripts', 'wpbandit_tinymce_css');

// TinyMCE init
function wpbandit_tinymce_init() {
	// Check permissions
	if ( !current_user_can('edit_posts') && !current_user_can('edit_pages') )
		return;

	// Add in rich editor mode only
	if ( get_user_option('rich_editing') == 'true') {
		add_filter('mce_buttons', 'wpbandit_mce_buttons');
		add_filter('mce_external_plugins', 'wpbandit_mce_plugins');
	}
}
add_action('init', 'wpbandit_tinymce_init');

// Add TinyMCE buttons
function wpbandit_mce_buttons($buttons) {
	array_push($buttons, 'separator', 'wpbandit_button');
	return $buttons;
}

// Add TinyMCE plugins
function wpbandit_mce_plugins($plugin_array) {
	$plugin_array['wpbanditShortcodes'] = get_template_directory_uri() . '/lib/shortcodes/tinymce/tinymce.wpbandit.js';
	return $plugin_array;
}