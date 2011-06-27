<?php 

add_action('roots_head', 'roots_google_analytics');
add_action('roots_head', 'roots_1140_head');
add_action('roots_head', 'roots_adapt_head');
add_action('roots_header_before', 'roots_1140_header_before');
add_action('roots_header_after', 'roots_1140_header_after');
add_action('roots_footer_before', 'roots_1140_footer_before');
add_action('roots_footer_after', 'roots_1140_footer_after');
add_action('roots_post_inside_before', 'roots_page_breadcrumb');

function roots_google_analytics() {
	global $roots_options;
	$google_analytics_id = $roots_options['google_analytics_id'];
	$get_google_analytics_id = esc_attr($roots_options['google_analytics_id']);
	if ($google_analytics_id !== '') {
		echo "\n\t<script>\n";
		echo "\t\tvar _gaq=[['_setAccount','$get_google_analytics_id'],['_trackPageview'],['_trackPageLoadTime']];\n";
		echo "\t\t(function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];g.async=1;\n";
		echo "\t\tg.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';\n";
		echo "\t\ts.parentNode.insertBefore(g,s)}(document,'script'));\n";
		echo "\t</script>\n";
	}	
}

function roots_1140_head() {
	global $roots_options;
	$roots_css_framework = $roots_options['css_framework'];
	$template_uri = get_template_directory_uri();
	if ($roots_css_framework === '1140') {
		echo "\t<script src=\"$template_uri/js/libs/css3-mediaqueries.js\"></script>";
	}	
}

function roots_adapt_head() {
	global $roots_options;
	$roots_css_framework = $roots_options['css_framework'];
	$template_uri = get_template_directory_uri();
	if ($roots_css_framework === 'adapt') {
		echo "\n\t<script>\n";
		echo "\t\tvar ADAPT_CONFIG = {\n";
		echo "\t\t\tpath: '/css/adapt/',\n";
		echo "\t\t\tdynamic: true,\n";
		echo "\t\t\trange: [\n";
		echo "\t\t\t\t'0px    to 760px  = mobile.css',\n";
		echo "\t\t\t\t'760px  to 980px  = 720.css',\n";
		echo "\t\t\t\t'980px  to 1280px = 960.css',\n";
		echo "\t\t\t\t'1280px to 1600px = 1200.css',\n";
		echo "\t\t\t\t'1600px to 1920px = 1560.css',\n";
		echo "\t\t\t\t'1920px           = fluid.css'\n";
		echo "\t\t\t]\n";
		echo "\t\t};\n";	
		echo "\t</script>\n";
		echo "\t<script src=\"$template_uri/js/libs/adapt.min.js\"></script>";
	}	
}

function roots_1140_header_before() {
	global $roots_options;
	$roots_css_framework = $roots_options['css_framework'];
	if ($roots_css_framework === '1140') {
		echo "<div class=\"row\">\n";
	}	
}

function roots_1140_header_after() {
	global $roots_options;
	$roots_css_framework = $roots_options['css_framework'];
	if ($roots_css_framework === '1140') {
		echo "</div><!-- /.row -->\n";
		echo "<div class=\"row\">\n";
	}	
}

function roots_1140_footer_before() {
	global $roots_options;
	$roots_css_framework = $roots_options['css_framework'];
	if ($roots_css_framework === '1140') {
		echo "</div><!-- /.row -->\n";
		echo "<div class=\"row\">\n";
	}	
}

function roots_1140_footer_after() {
	global $roots_options;
	$roots_css_framework = $roots_options['css_framework'];
	if ($roots_css_framework === '1140') {
		echo "</div><!-- /.row -->\n";
	}	
}

function roots_page_breadcrumb() {
	global $post;
	if (function_exists('yoast_breadcrumb')) { 
		if (is_page() && $post->post_parent) { 
			yoast_breadcrumb('<p id="breadcrumbs">','</p>'); 
		} 
	}
}

?>