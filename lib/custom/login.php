<?php function custom_login_logo() {
  $logo = get_field('color_logo', 'options');
	echo '<style type="text/css">h1 a { background: url('. $logo .') 50% 50% no-repeat !important; width: 326px!important; height: 125px!important; }</style>';
}

add_action('login_head', 'custom_login_logo');

function change_wp_login_url() {
  $domain = $_SERVER[ 'SERVER_NAME' ];
	return $domain;
}

add_filter('login_headerurl', 'change_wp_login_url');

function change_wp_login_title() {
	return get_option('blogname');
}

add_filter('login_headertitle', 'change_wp_login_title');