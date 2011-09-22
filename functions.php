<?php // https://github.com/retlehs/roots/wiki

require_once get_template_directory() . '/inc/roots-activation.php'; 	// activation
require_once get_template_directory() . '/inc/roots-options.php'; 		// theme options
require_once get_template_directory() . '/inc/roots-cleanup.php'; 		// cleanup
require_once get_template_directory() . '/inc/roots-htaccess.php'; 		// rewrites for assets, h5bp htaccess
require_once get_template_directory() . '/inc/roots-hooks.php'; 		// hooks
require_once get_template_directory() . '/inc/roots-actions.php'; 		// actions
require_once get_template_directory() . '/inc/roots-widgets.php'; 		// widgets
require_once get_template_directory() . '/inc/roots-custom.php'; 		// custom functions

$roots_options = roots_get_theme_options();

// set the maximum 'Large' image width to the maximum grid width
if (!isset($content_width)) {
	global $roots_options;
	$roots_css_framework = $roots_options['css_framework'];
	switch ($roots_css_framework) {
	    case 'blueprint': 	$content_width = 950;	break;
	    case '960gs_12': 	$content_width = 940;	break;
	    case '960gs_16': 	$content_width = 940;	break;
	    case '960gs_24': 	$content_width = 940;	break;
	    case '1140': 		$content_width = 1140;	break;
	    case 'adapt':	 	$content_width = 940;	break;	    
	    default: 			$content_width = 950;	break;
	}
}

function roots_setup() {
	load_theme_textdomain('roots', get_template_directory() . '/lang');
	
	// tell the TinyMCE editor to use editor-style.css
	// if you have issues with getting the editor to show your changes then use the following line:
	// add_editor_style('editor-style.css?' . time());
	add_editor_style('editor-style.css');
	
	// http://codex.wordpress.org/Post_Thumbnails
	add_theme_support('post-thumbnails');
	// set_post_thumbnail_size(150, 150, false);
	
	// http://codex.wordpress.org/Post_Formats
	// add_theme_support('post-formats', array('aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat'));
	
	// http://codex.wordpress.org/Function_Reference/add_custom_image_header
	if (!defined('HEADER_TEXTCOLOR')) { define('HEADER_TEXTCOLOR', '');	}
	if (!defined('NO_HEADER_TEXT')) { define('NO_HEADER_TEXT', true); }	
	if (!defined('HEADER_IMAGE')) { define('HEADER_IMAGE', get_template_directory_uri() . '/img/logo.png'); }
	if (!defined('HEADER_IMAGE_WIDTH')) { define('HEADER_IMAGE_WIDTH', 300); }
	if (!defined('HEADER_IMAGE_HEIGHT')) { define('HEADER_IMAGE_HEIGHT', 75); }

	function roots_custom_image_header_site() { }
	function roots_custom_image_header_admin() { ?>
		<style type="text/css">
			.appearance_page_custom-header #headimg { min-height: 0; }
		</style>
	<?php }
	add_custom_image_header('roots_custom_image_header_site', 'roots_custom_image_header_admin');
		
	add_theme_support('menus');
	register_nav_menus(array(
		'primary_navigation' => __('Primary Navigation', 'roots'),
		'utility_navigation' => __('Utility Navigation', 'roots')
	));	
}

add_action('after_setup_theme', 'roots_setup');

// create widget areas: sidebar, footer
$sidebars = array('Sidebar', 'Footer');
foreach ($sidebars as $sidebar) {
	register_sidebar(array('name'=> $sidebar,
		'before_widget' => '<article id="%1$s" class="widget %2$s"><div class="container">',
		'after_widget' => '</div></article>',
		'before_title' => '<h3>',
		'after_title' => '</h3>'
	));
}

// return post entry meta information
function roots_entry_meta() {
	echo '<time class="updated" datetime="'. get_the_time('c') .'" pubdate>'. sprintf(__('Posted on %s at %s.', 'roots'), get_the_time('l, F jS, Y'), get_the_time()) .'</time>';
	echo '<p class="byline author vcard">'. __('Written by', 'roots') .' <a href="'. get_author_posts_url(get_the_author_meta('id')) .'" rel="author" class="fn">'. get_the_author() .'</a></p>';
}

?>