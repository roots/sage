<?php

locate_template(array('inc/roots-activation.php'), true, true);	// activation
locate_template(array('inc/roots-admin.php'), true, true);		// admin additions/mods
locate_template(array('inc/roots-options.php'), true, true);	// theme options menu
locate_template(array('inc/roots-cleanup.php'), true, true);	// code cleanup/removal
locate_template(array('inc/roots-htaccess.php'), true, true);	// h5bp htaccess
locate_template(array('inc/roots-hooks.php'), true, true);		// hooks
locate_template(array('inc/roots-actions.php'), true, true);	// actions
locate_template(array('inc/roots-widgets.php'), true, true);	// widgets
locate_template(array('inc/roots-custom.php'), true, true);		// custom functions

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
	    default: 			$content_width = 950;	break;
	}
}

// tell the TinyMCE editor to use editor-style.css
// if you have issues with getting the editor to show your changes then use the following line:
// add_editor_style('editor-style.css?' . time());
add_editor_style('editor-style.css');

add_theme_support('post-thumbnails');

// http://codex.wordpress.org/Post_Formats
// add_theme_support('post-formats', array('aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat'));

add_theme_support('menus');
register_nav_menus(array(
	'primary_navigation' => __('Primary Navigation', 'roots'),
	'utility_navigation' => __('Utility Navigation', 'roots')
));

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

// add to robots.txt
// http://codex.wordpress.org/Search_Engine_Optimization_for_WordPress#Robots.txt_Optimization
add_action('do_robots', 'roots_robots');

function roots_robots() {
	echo "Disallow: /cgi-bin\n";
	echo "Disallow: /wp-admin\n";
	echo "Disallow: /wp-includes\n";
	echo "Disallow: /wp-content/plugins\n";
	echo "Disallow: /plugins\n";
	echo "Disallow: /wp-content/cache\n";
	echo "Disallow: /wp-content/themes\n";
	echo "Disallow: /trackback\n";
	echo "Disallow: /feed\n";
	echo "Disallow: /comments\n";
	echo "Disallow: /category/*/*\n";
	echo "Disallow: */trackback\n";
	echo "Disallow: */feed\n";
	echo "Disallow: */comments\n";
	echo "Disallow: /*?*\n";
	echo "Disallow: /*?\n";
	echo "Allow: /wp-content/uploads\n";
	echo "Allow: /assets";
}

function roots_author_link($link) {
  return str_replace('<a ', '<a class="fn" rel="author"', $link);
}

add_filter('the_author_posts_link', 'roots_author_link');

?>
