<?php function custom_login_logo() {
  $logo = get_field('color_logo', 'options');
	// $attachment_id = get_post_thumbnail_id();
	// $size = "full";
	// $image_attributes = wp_get_attachment_image_src( $attachment_id, $size );
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

function my_login_stylesheet() { ?>
    <link rel="stylesheet" id="custom_wp_admin_css"  href="<?php echo get_bloginfo( 'stylesheet_directory' ) . '/assets/css/login.css'; ?>" type="text/css" media="all" />
<?php }
add_action( 'login_enqueue_scripts', 'login_stylesheet' );